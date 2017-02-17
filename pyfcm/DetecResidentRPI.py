import  requests
import  json
import blescan
import sys

import bluetooth._bluetooth as bluez

if __name__ == '__main__' :
    url = 'http://128.199.93.67/WeTrack/api/web/index.php/v1/user/login'
    post_data = {'username':'admin', 'password':'qw1234er'}
    get_response = requests.post(url=url,data=post_data)
    listReceiveBeacon = []
    listReceiveBeaconID = []
    result = json.loads(get_response.text)
    token = result["token"]
    auth = "Bearer " + token

    dev_id = 0
    try:
        sock = bluez.hci_open_dev(dev_id)
        print
        "ble thread started"

    except:
        print
        "error accessing bluetooth device..."
        sys.exit(1)

    blescan.hci_le_set_scan_parameters(sock)
    blescan.hci_enable_le_scan(sock)
    if (result["result"] == "correct") :
        while True:
            listReceiveBeacon = blescan.parse_events(sock, 100)
            print
            "----------"
            headers = {'Authorization': '%s' % auth}
            url = 'http://128.199.93.67/WeTrack/api/web/index.php/v1/beacon?expand=resident,location,locationHistory'
            lBc = requests.get(url, headers=headers)
            listBeacon = json.loads(lBc.text)
            temp = []
            tempID = []

            for a in listBeacon :
                temp.append(a['uuid'] + " " + str(a['major']) + " " + str(a['minor']))
                tempID.append(a['id'])

            for a in listReceiveBeacon :
                t = a['uuid'] + " " + str(a['major']) + " " + str(a['minor'])
                if (t in temp) :
                    index = temp.index(t)
                    listReceiveBeaconID.append(tempID[index])

            url = 'http://128.199.93.67/WeTrack/api/web/index.php/v1/resident/missing?expand=beacons,relatives,locations'

            get_response = requests.get(url, headers=headers)
            listMissingResident = json.loads(get_response.text)
            # print(listMissingResident)
            url = 'http://128.199.93.67/WeTrack/api/web/index.php/v1/location-history/new'
            for a in listMissingResident :
                t = a['beacons']
                userID = a['id']
                for b in t :
                    ResidentMissingBeaconID = b['id']
                    if ResidentMissingBeaconID in listReceiveBeaconID :
                        post_data = {
                                        "beacon_id" : ResidentMissingBeaconID,
                                        "user_id" : userID
                                     }


    else :
        print("This device unregistered!")
