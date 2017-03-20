import  pymysql
import  datetime

from datetime import  datetime, timedelta
#from firebase import firebase
from pyfcm import FCMNotification

_host = 'localhost'
_user = 'root'
_password = 'abcd1234'
_database = 'we_track'
_projlink = 'https://we-track-50c51.firebaseio.com/'
_web_api_key = 'AAAArfBwPGI:APA91bHX754Y6Smm3YCDSYh6mLoPJAmWNb8s8qI55CIGCI9BoaPWtZ1qKXyQlSNis7c89GvVo8-FzZtidGrsN1_lcBBnC_U7DsPnD7VzFYm8d3cIPVNhwQyUhM3AFj31-08_P5v00dbs'


def getData(name, cursor):
    sql = "SELECT * FROM %s;" % (name)
    cursor.execute(sql)
    return cursor.fetchall()

if __name__ == "__main__":
    #firebase = firebase.FirebaseApplication(_projlink, authentication=None)
    connection = pymysql.connect(host=_host,
                                 user=_user,
                                 password=_password,
                                 database=_database
                                 )
    cursor = connection.cursor()
    location_table = getData('location', cursor)
    beacon_table = getData('beacon', cursor)
    user_resident_table = getData('user_resident', cursor)
    user_table = getData('user', cursor)
    device_token = getData('device_token', cursor)
    resident_table = getData('resident', cursor)

    current_time = datetime.now()
    # current_time = datetime.strptime("2017-02-06 14:20:08", "%Y-%m-%d %H:%M:%S")
    previous_time = current_time - timedelta(minutes=10)
    list_beacon_id = []
    list_resident_id = []
    # print(current_time, previous_time)
    for a in location_table:
        if (previous_time <= a[7]) and (a[7] <= current_time) :
            list_beacon_id.append(a[1])
            
    for a in beacon_table:
        if (a[0] in list_beacon_id) :
            list_resident_id.append(a[1])

    push_service = FCMNotification(api_key=_web_api_key)
    for user in user_table :
        user_id = user[0]
        list = []
        for a in user_resident_table :
            if (a[1] == user_id) :
                resident_id = a[2]
                if (resident_id in list_resident_id) :
                    list.append(resident_id)
        if (len(list) > 0) :
 #           print(user_id, list)
            # for i in list:
            #     firebase.patch('UserID/' + str(user_id), {i: i})
            for dt in device_token :
                if (dt[1] == user_id) :
                    registration_id = dt[2]
                    message_title = "We Track"
                    for reID in list :
                        for aa in resident_table :
                            if (aa[0] == reID) :
                                message_body = str(aa[1]) + " has a new location."
                                data_message = {
                                                    'data' : message_body,
                                                    'id': reID
                                                }
                                result = push_service.notify_single_device(registration_id=registration_id, data_message=data_message)
#                                result = push_service.notify_single_device(registration_id=registration_id, message_title=message_title,
 #                                                                          message_body=message_body)
#    print(1)
    cursor.close()
    connection.commit()
    connection.close()