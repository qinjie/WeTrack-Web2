# WeTrack-Raspberry-Python

## Introduction

Python background process have 3 files which is running in Raspberry Pi
    
## Raspberry Pi

Python application has reponsibility to monitor nearby  Beacons, if it belongs to any missing residents, upload it to server. 

### Instruction

1.	Enable bluetooth of RaspberryPi
2.	Recommend to go to Desktop;
3.	Install library for those python library: requests, python-bluez, json, bluetooth (If need);
4.	Run: git clone https://github.com/qinjie/WeTrack-Web2;
5.	Go to: WeTrack-Web2\pyRPI;
6.  Run: ```python main.py``` or ```python3 main.py```.

### Crontab Setup
Run ```sudo crontab -e```

Add following configuration
```
*/1 * * * * python /var/www/html/WeTrack/pyfcm/Server/Find_userID/main.py >/dev/null 2>&1
```
```
*/10 * * * * python /var/www/html/WeTrack/pyfcm/Server/MissingNotice/main.py >/dev/null 2>&1
```
```
*/60 * * * * python /var/www/html/WeTrack/pyfcm/Server/Check_status_RPI/main.py >/dev/null 2>&1
```
