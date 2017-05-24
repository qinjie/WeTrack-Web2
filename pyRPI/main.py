import json
import sys
import time

import bluetooth._bluetooth as bluez
import requests

import blescan
import getSerial
import utilsOther

time_sleep_after_no_connection = 10

URL_SERVER = 'http://128.199.93.67'
URL_LOGIN = 'http://128.199.93.67/WeTrack/api/web/index.php/v1/locator/login'
URL_RESIDENTS_MISSING = 'http://128.199.93.67/WeTrack/api/web/index.php/v1/resident/missing?expand=beacons,relatives,locations'
URL_BEACONS = 'http://128.199.93.67/WeTrack/api/web/index.php/v1/beacon?expand=resident,location,locationHistory'
URL_BEACONS_ACTIVE = 'http://128.199.93.67/WeTrack/api/web/index.php/v1/beacon/active?expand=resident'
URL_LOCATION_NEW = 'http://128.199.93.67/WeTrack/api/web/index.php/v1/location-history/new'
URL_LOCATION_ALIVE = "http://128.199.93.67/WeTrack/api/web/index.php/v1/location-history/alive"


def convertUuid(uuid):
    uuid = uuid.lower()
    result = ""
    for a in uuid:
        if (a != '-'):
            result += a
    return result


if __name__ == '__main__':

    # Initialize BLE
    dev_id = 0
    try:
        sock = bluez.hci_open_dev(dev_id)
        print("ble thread started")
    except:
        print("error accessing bluetooth device...")
        sys.exit(1)

    blescan.hci_le_set_scan_parameters(sock)
    blescan.hci_enable_le_scan(sock)

    # Scanning all the time
    while True:

        while (utilsOther.web_site_online(URL_SERVER) == False):
            print("waiting for network connection")
            time.sleep(time_sleep_after_no_connection)
            continue

        # Get device auth token
        serial_number = getSerial.getserial()
        post_data = {'serial_number': serial_number}
        get_response = requests.post(url=URL_LOGIN, data=post_data)
        if get_response.status_code != 200:
            print("Failed to contact server.")
            time.sleep(time_sleep_after_no_connection)
            continue
        else:
            result = json.loads(get_response.text)
            if result["result"] != "correct":
                print("This device is not registered!")
                time.sleep(time_sleep_after_no_connection)
                continue

        token = result["token"]
        device_user_id = result['user_id']
        auth = "Bearer " + token

        # Heart beat of device
        headers = {'Authorization': '%s' % auth}
        post_data = {
            "status": 10,
            "user_id": device_user_id
        }
        get_response = requests.post(url=URL_LOCATION_ALIVE, data=post_data, headers=headers)

        while True:

            while (utilsOther.web_site_online(URL_SERVER) == False):
                print("waiting for network connection")
                time.sleep(time_sleep_after_no_connection)
                continue

            # Get beacon list of missing residents. returned beacon signature and beacon id
            missing_beacons = {}
            lBc = requests.get(URL_BEACONS_ACTIVE, headers=headers)
            if (lBc.status_code == 200):
                listBeacon = lBc.json()
                for a in listBeacon:
                    signature = convertUuid((a['uuid'])) + "," + str(a['major']) + "," + str(a['minor'])
                    missing_beacons[signature] = a['id']
            else:
                print("Failed to contact server.")
                time.sleep(time_sleep_after_no_connection)
                continue

            print "Missing beacons:"
            for key, value in missing_beacons.iteritems():
                print "\t", key, value

            # Scan for beacon. returned beacon signature and mac
            scanned_beacons = blescan.parse_events(sock, 10)
            print "Scanned beacons:"
            for key, value in scanned_beacons.iteritems():
                print "\t", key, value

            # Match to beacons of missing residents
            found_beacon_ids = []
            for key, value in scanned_beacons.iteritems():
                if missing_beacons.has_key(key):
                    found_beacon_ids.append(missing_beacons[key])
                    print "Found missing beacon: ", key, missing_beacons[key]

            get_response = requests.get(URL_RESIDENTS_MISSING, headers=headers)
            listMissingResident = json.loads(get_response.text)
            # print(listMissingResident)

            for beacon_id in found_beacon_ids:
                post_data = {
                    "beacon_id": beacon_id,
                    "user_id": device_user_id
                }
                get_response = requests.post(url=URL_LOCATION_NEW, data=post_data, headers=headers)
                print(get_response.text)

            time.sleep(2)
