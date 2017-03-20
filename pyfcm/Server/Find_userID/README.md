# WeTrack-Python

## Introduction

Python background process have a application which is running in Server 
    
## Server

Python application has reponsibility to find resident’s ID who has new location 10 minutes ago. After find resident’s ID, Python application will then find user_id who are relatives of the resident_id and send push notification to those user_id

### Instruction

1.	If you use remote server, SSH to server;
2.	Recommend to go to: /var/www/html (Root directory of Apache);
3.	Install library for those python library: pyfcm, pymysql (If need);
4.	Run: git@github.com:qinjie/WeTrack-Web2.git
5.	Go to: WeTrack-Web2\pyfcm\Server\Find_userID
6.	Config database, loop time, PatientTracking-Web address in main.py;
	<br> ![Config Image](https://github.com/qinjie/WeTrack-Web2/blob/hiepBH/pyfcm/Img/W1.PNG)
	<br> ![Config Image](https://github.com/qinjie/WeTrack-Web2/blob/hiepBH/pyfcm/Img/W2.PNG)
7.	Run python main.py or python3 main.py.
