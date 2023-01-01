Program Features:
=====================================
-Validated Registration Form
-User Login
-Password Meter and Feedback
-reCaptcha
-Email Verification
-Two Factor Authentication
-Password Expiration

How to run Program:
========================================
-Extract the zip file

-Install XAMPP.

-Place the file inside 'htdocs' folder of XAMPP

-edit php configuration (php.ini) file as well as sendmail configuration file present in 'sendmail' folder inside XAMPP 
(configured files are attached for reference).

CHANGES TO BE MADE:
==================
inside php.ini file
-------------------
SMTP = smtp.gmail.com
smtp_port=465
sendmail_from = your email address from which mail can be sent
sendmail_path = "\"C:\xampp\sendmail\sendmail.exe\" -t"

------------------------
inside sendmail.ini file
------------------------
smtp_server=smtp.gmail.com
smtp_port=465
error_logfile=error.log
debug_logfile=debug.log
auth_username= your email address from which mail can be sent
auth_password= can be obtained from google account setting
force_sender= your email address from which mail can be sent

-Go to the browser and type 'http://localhost/advanced_cybersecurity/register.php'


