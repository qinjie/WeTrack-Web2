# Send to single device.
from pyfcm import FCMNotification

push_service = FCMNotification(api_key="AIzaSyARAOwgK19KD8IdQ0NKXH97W533I_f0caY")

# OR initialize with proxies

proxy_dict = {
          "http"  : "http://128.199.93.67",
          "https" : "http://128.199.93.67",
        }
push_service = FCMNotification(api_key="AIzaSyARAOwgK19KD8IdQ0NKXH97W533I_f0caY", proxy_dict=proxy_dict)

# Your api-key can be gotten from:  https://console.firebase.google.com/project/<project-name>/settings/cloudmessaging

registration_id = "fI5aS1Lk_98:APA91bFs5nsyGkvEzn8K6cThdXNncwdoWeEIYFcGXB_h9rVK-XWn7loPOmJDzonJF6F5mmu9rU6R17kTdLOnlmz1OxCd1mH248VrVJ4PYxtAUEC5WyIkq35KR-q9IS-pauUdBuhUyjSS"
message_title = "Notification from server"
message_body = "Hi guys, how are you today"
result = push_service.notify_single_device(registration_id=registration_id, message_title=message_title, message_body=message_body)

# Send to multiple devices by passing a list of ids.
registration_ids = ["<device registration_id 1>", "<device registration_id 2>", ...]
message_title = "Sent to multiple devices"
message_body = "Hi guys, how are you today, hahaha"
result = push_service.notify_multiple_devices(registration_ids=registration_ids, message_title=message_title, message_body=message_body)

print result