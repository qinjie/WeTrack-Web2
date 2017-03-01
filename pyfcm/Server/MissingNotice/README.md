# WeTrack-Python

##Introduction

Python background process have a application which is running in Server 
    
## Server

Python application has reponsibility to find resident’s ID who had ‘Missing’ status 1 day ago and change those status become ‘Available’ send push notification to those user

### Instruction

1.	If you use remote server, SSH to server;
2.	Recommend to go to: /var/www/html (Root directory of Apache);
3.	Install library for those python library: pyfcm, pymysql (If need);
4.	Run: git@github.com:qinjie/WeTrack-Web2.git
5.	Go to: WeTrack-Web2\pyfcm\Server\MissingNotice
6.	Config database, loop time, PatientTracking-Web address in main.py;
	<br> ![Config Image](https://github.com/qinjie/WeTrack-Web2/blob/hiepBH/pyfcm/Img/W1.PNG)
7.	Run python main.py or python3 main.py.
