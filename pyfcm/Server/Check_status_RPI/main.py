import pymysql
import datetime

_host = 'localhost'
_user = ''
_password = ''
_database = 'test'

def updateData(cursor, id) :
    sql = 'UPDATE user ' \
          'SET user.status = -1 ' \
          'WHERE user.id = %s' %(id)
    # print(sql)
    cursor.execute(sql)

if __name__ == '__main__' :
    connection = pymysql.connect(host=_host,
                                 user=_user,
                                 password=_password,
                                 database=_database)
    cursor = connection.cursor()

    sql = 'SELECT user.id, user.role, user.updated_at FROM user'
    cursor.execute(sql)
    result = cursor.fetchall()
    print(result)
    for a in result :
        if a[1] == 2 :
            updated_at = a[2]
            id = a[0]
            k = datetime.datetime.now() - datetime.timedelta(hours=1)
            if (updated_at < k) :
                updateData(cursor, id)

    cursor.close()
    connection.commit()
    connection.close()