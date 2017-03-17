import urllib2

def internet_on():
    try:
        urllib2.urlopen('http://128.199.93.67', timeout=1)
        return True
    except urllib2.URLError as err:
        return False

if __name__ == "__main__" :
    print(internet_on())
