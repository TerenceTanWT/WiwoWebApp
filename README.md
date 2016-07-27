# WiwoWebApp
WiwoWebApp is a simple PHP web interface to control the Orvibo Wiwo S20 switches (turn on/off). You may view screenshots of the 
web interface at image*.jpg located at the root directory.

This web application is very basic - it only allows you to toggle the Wiwo S20 switches on and off. 
This reason for creating this project was because I want to toggle the switch on/off via the internet when I am outside. I know that the Wiwo app on the AppStore allows for that, however it works by connecting through Orvibo's proxy server.
As a cyber security student, I do not like the fact that my Wiwo switch at home is constantly communicating to an unknown proxy server in China. Therefore I blocked internet access to the switch on my router.
I've tried connecting using the Wiwo app from the internet via VPN, but it doesn't work. 
This is the reason why I decided to quickly create a very simple web application to toggle my Wiwo switch on/off, that can be accessed via VPN from the internet.

This web application is very lightweight, and can be run on any web server with PHP. I run this on my Raspberry Pi with apache and PHP installed. MySQL is not required as the username/password are bcrypt hashed and hard coded in [index.php](./index.php). 

WiwoWebApp was developed purely for my own use. After creating this web application, I found out that there was another similar project by Fernando M. Silva with much more advanced features. Do visit his [project](https://github.com/fernadosilva/orvfms) if you require more advanced features.


# Disclaimer
This web application was developed independently for my own use and it is not supported or endorsed in any way by Orvibo (C).
Please use this web application at your own risk. There is no security measures in place to secure this web application, so it may have many vulnerabilities. 
One vulnerability I can think of is Cross-Site Request Forgery (CSRF), but I did not fix it because this web application is only intended for intranet use (VPN for Internet use).
Therefore I highly recommended that you **do not expose this web application to the internet!** Use a VPN to access this web application from the internet.

# Acknowledgments
The function of this web application (on/off the switch) is performed using [Branislav Vartik's perl code](http://pastebin.com/7wwe64m9).

Do check out [Andrius Štikonas reverse engineering analysis](https://stikonas.eu/wordpress/2015/02/24/reverse-engineering-orvibo-s20-socket/) of the Orvibo Wiwo S20 switch for more information regarding how the perl code works.

# Usage
Setting up this web application is very straight forward. However there are a few lines of code you will have to edit from [index.php](./index.php) as the variables for username, password, switch name, and switch MAC address are hard coded.

## Setting up the Web Server
This web application can be installed on any web server. However, the below instructions are based on Linux debian as I installed it on a Raspberry Pi running Raspbian.

### Installing Apache and PHP
Run the command `sudo apt-get install apache2 php5 libapache2-mod-php5` to install apache and PHP. MySQL is not needed as no database is used (username/password are bcrypt hashed and hard coded in [index.php](./index.php)).

### Copying the files onto the web server
Copy all my project files and folders into the root directory of the web server. If you are using apache, the root directory should be located at `/var/www/html`.

## Editing variables in index.php
Edit index.php using your favourite editor. In this example I'll be using nano. `nano /var/www/html/index.php`. If you are using nano, you can use `ctrl` + `-` to jump to line number.

### Adding Wiwo Switch's MAC address (complusory)
This web application requires your Wiwo switch's MAC address in order to toggle it on/off. The MAC address is hard coded in the code and you need to modify it.
You can find out your switch's MAC address in your router's gateway webpage (it is usually http://192.168.1.1).

Edit **line 225** of the code and replace `00:00:00:00:00:00` with your switch's MAC address. If you wish to rename your switch, you may change both the variables `Switch 1` also on **line 225**.

**If you edit the switch name, both the name variable on line 225 MUST be the same!**

If you want to add in a second switch, uncomment **line 226** and follow the same instructions as **line 225**. If you want to add in even more switches, duplicate **line 226**, paste it directly below itself and follow the same instructions as **line 225**.

### Editing username and password (not complusory)
The default username and password is `wiwo`. It is alright not perform this step if you do not wish to change your password.

Username and password variables are stored at **line 13 and 14** using bcrypt hash (_the only security feature implemented as I do not want to potentially expose my password :P_).
Replace the variables with the username and password you want in **bcrypt hash** format. You can convert plaintext to bcrypt hash using the provided bcrypt function 
in **line 20** (uncomment it, replace "ENTER PASSWORD HERE" with your plain text, load the website, and your text will be displayed in bcrypt on the top left of the web page) or using an online bcrypt hash generator.

**WARNING: Becareful when using online bcrypt hash generator. NEVER type your real password as you do not know if the online bcrypt hash generator logs your keystroke!**

### Adding more login accounts (not complusory)
To add in more login account, simply uncomment **line 16 and 17**. The default username and password for the second account is `wiwo2`. If you wish to change them, follow the previous step. Then, uncomment **line 30 to 33**.

To add even more login accounts, duplicate **line 16 and 17** directly below it. Then, duplicate **line 30 to 33** directly below it.

## Finish
You have done configuring the web application and can start using it! Please take note of the disclaimer written above - **use this web application at your own risk!** Enjoy! :)
