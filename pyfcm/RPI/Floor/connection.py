from urllib import request

def internet_on():
    try:
        request.urlopen('http://128.199.93.67', timeout=1)
        return True
    except request.URLError as err:
        return False

if __name__ == "__main__" :
    print(internet_on())