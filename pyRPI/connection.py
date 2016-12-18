import urllib2
import requests

def internet_on():
    try:
        reponse = requests.get('http://128.199.93.67')
        #urllib2.urlopen('http://google.com', timeout=2)
        #return True
    except Exception as e :
        return False
        print e
    #urllib2.URLError as err:
    return True

if __name__ == "__main__" :
    print(internet_on())
