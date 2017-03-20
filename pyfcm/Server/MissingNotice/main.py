import  pymysql
import  datetime

from datetime import  datetime, timedelta

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

def updateData(id, status, cursor) :
    sql = "UPDATE resident  SET status = %s, reported_at = NULL WHERE id = %s; " % (status, id)
    cursor.execute(sql)

if __name__ == "__main__":
    connection = pymysql.connect(host=_host,
                                 user=_user,
                                 password=_password,
                                 database=_database
                                 )
    cursor = connection.cursor()
    resident_table = getData('resident', cursor)
    user_resident_table = getData('user_resident', cursor)
    device_token_table = getData('device_token', cursor)
    push_service = FCMNotification(api_key=_web_api_key)
    time = datetime.now()
    time = time - timedelta(days=1)
    for resident in resident_table :
        if (resident[7] == 1) :
            resident_id = resident[0]
            resident_name = resident[1]
            reported_at = resident[9]
            if (reported_at > time) :
                continue
            # print(resident_id)
            updateData(resident_id, 0, cursor)
            for user in user_resident_table :
                if (user[2] == resident_id) :
                    user_id = user[1]

                    for dt in device_token_table:
                        if (dt[1] == user_id):
                            registration_id = dt[2]
                            message_title = "We Track"
                            message_body = str(resident_name) + " has been changed to available. Click here to modify"
                            data_message = {
                                            'data': message_body,
                                            'id': resident_id
                            }
                            result = push_service.notify_single_device(registration_id=registration_id,
                                                                       data_message=data_message)

                            #result = push_service.notify_single_device(registration_id=registration_id,
                             #                                          message_title=message_title,
                              #                                         message_body=message_body)

    print(1)
    cursor.close()
    connection.commit()
    connection.close()
