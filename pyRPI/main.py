import requests
import json
import sys
import time

import blescan
import getSerial
import connection

import bluetooth._bluetooth as bluez

time_sleep_after_no_connection = 10


def convertUuid(uuid):
    uuid = uuid.lower()
    result = ""
    for a in uuid:
        if (a != '-'):
            result += a
    return result


if __name__ == '__main__':
    while (1):
        
        while (connection.internet_on() == False):
            print("No network connection, restart after 1 hour!")
            time.sleep(time_sleep_after_no_connection)
            continue
        url = 'http://128.199.93.67/WeTrack/api/web/index.php/v1/locator/login'
        serial_number = getSerial.getserial()
        post_data = {'serial_number': serial_number}
        get_response = requests.post(url=url, data=post_data)

        listReceiveBeacon = []
        listReceiveBeaconID = []
        result = json.loads(get_response.text)
        token = result["token"]
        auth = "Bearer " + token

        dev_id = 0
        try:
            sock = bluez.hci_open_dev(dev_id)
            print "ble thread started"

        except:
            print "error accessing bluetooth device..."
            sys.exit(1)

        blescan.hci_le_set_scan_parameters(sock)
        blescan.hci_enable_le_scan(sock)
        if (result["result"] == "correct"):
            userID = result['user_id']
            while True:
                while (connection.internet_on() == False):
                    print("No network connection, restart after 1 hour!")
                    time.sleep(time_sleep_after_no_connection)
                    continue

                listReceiveBeacon = []
                listReceiveBeaconID = []
                listReceiveBeacon = blescan.parse_events(sock, 10)

                print ("----------")


                headers = {'Authorization': '%s' % auth}
                url = "http://128.199.93.67/WeTrack/api/web/index.php/v1/location-history/alive"
                post_data = {
                    "status": 10,
                    "user_id": userID
                }
                get_response = requests.post(url=url, data=post_data, headers=headers)

		while (connection.internet_on() == False):
                    print("No network connection, restart after 1 hour!")
                    time.sleep(time_sleep_after_no_connection)
                    continue

                url = 'http://128.199.93.67/WeTrack/api/web/index.php/v1/beacon?expand=resident,location,locationHistory'
                lBc = requests.get(url, headers=headers)
                listBeacon = json.loads(lBc.text)
                temp = []
                tempID = []


                for a in listBeacon:
                    #print(a['uuid'], " ", a['major'], " ", a['minor'])
                    temp.append(convertUuid((a['uuid'])) + " " + str(a['major']) + " " + str(a['minor']))
                    tempID.append(a['id'])
                # print temp
                for a in listReceiveBeacon:
                    t = str(a['uuid']) + " " + str(a['major']) + " " + str(a['minor'])

                    if (t in temp):
                        index = temp.index(t)
                        listReceiveBeaconID.append(tempID[index])

                # print listReceiveBeaconID
                url = 'http://128.199.93.67/WeTrack/api/web/index.php/v1/resident/missing?expand=beacons,relatives,locations'
                while (connection.internet_on() == False):
                    print("No network connection, restart after 1 hour!")
                    time.sleep(time_sleep_after_no_connection)
                    continue
                get_response = requests.get(url, headers=headers)
                listMissingResident = json.loads(get_response.text)
                # print(listMissingResident)
		while (connection.internet_on() == False):
                    print("No network connection, restart after 1 hour!")
                    time.sleep(time_sleep_after_no_connection)
                    continue

                url = 'http://128.199.93.67/WeTrack/api/web/index.php/v1/location-history/new'
                for a in listMissingResident:
                    t = a['beacons']
                    for b in t:
                        ResidentMissingBeaconID = b['id']
                        if ResidentMissingBeaconID in listReceiveBeaconID:
                            post_data = {
                                "beacon_id": ResidentMissingBeaconID,
                                "user_id": userID
                            }
                            print(post_data)
                            get_response = requests.post(url=url, data=post_data, headers=headers)
                            print(get_response.text)
                time.sleep(2)

        else:
            print("This device unregistered!")
