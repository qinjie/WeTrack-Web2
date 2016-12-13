import urllib2

def internet_on():
    try:
        urllib2.urlopen('http://google.com', timeout=1)
        return True
    except urllib2.URLError as err:
        return False

if __name__ == "__main__" :
    print(internet_on())
