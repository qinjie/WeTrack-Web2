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
5.	Go to: WeTrack-Web2\RPI;
6.  Run: ```python main.py``` or ```python3 main.py```.


### Install Bluez software on RPi
```Reference https://learn.adafruit.com/install-bluez-on-the-raspberry-pi/installation```

```
wget  wget http://www.kernel.org/pub/linux/bluetooth/bluez-5.45.tar.xz
tar xvf bluez-5.45.tar.xz
cd bluez-5.45/
sudo apt-get update
sudo apt-get install -y libusb-dev libdbus-1-dev libglib2.0-dev libudev-dev libical-dev libreadline-dev
```

sudo apt-get update
sudo apt-get install bluetooth
sudo apt-get install bluez
sudo apt-get install python-bluez

### Reference
    http://www.instructables.com/id/iBeacon-Entry-System-with-the-Raspberry-Pi-and-Azu/

