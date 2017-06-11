# PGSProject

> 	LINUX
1. To run our project first you need to install LAMP (Linux, Apache, MySQL and PHP). To accoplish this process just follow  steps under this link https://www.atlantic.net/community/howto/install-linux-apache-mysql-php-lamp-stack-on-ubuntu-16-04/.

2. After that add the code below to the 00-default.conf (/etc/apache2/sites-availble/). It is necessary to allow .htaccess files.
	<br>
  <*/VirtualHost>
	<br>
  <*Directory "/var/www/html">
	<br>
    AllowOverride All
	<br>
  <*/Directory>

3. Put zend directory to /var/www/html/. Then type this -> (localhost/zend/public) to the address bar. Enjoy!
  
  
> 	WINDOWS	
1. To run our project first you need to install XAMPP. You can download it from this site: https://www.apachefriends.org/download.html.

2. After successfull installation open XAMPP Control Panel and click "Start" for MySQL and Apache.

3. Put "zend" directory to  xampp/htdocs.

4. Now you can open it by using the Built-in PHP web Server or Apache Web Server
	a)Using the Built-in PHP web Server
		
		-open terminal in xampp/htdocs/zend you can do it by clicking SHIFT+RMB and choosing "Open Command Window here"
		-next you must write this command "php -S 0.0.0.0:8080 -t public public/index.php" and press ENTER
		-now you can open project by typing http://localhost:8080 to the address bar.
		-you must do it every time if you close terminal or stop commmand
	
	b)Using Apache Web Server
	
	
		-in XAMPP Control Panel click Config for Apache and choose Apache(httpd.conf)
		-paste this code at the end, change [XAMPP DIRECTORY] to your xampp directory
		

		<VirtualHost *:80>
		     ServerName zend.localhost
		     DocumentRoot "[XAMPP DIRECTORY]/htdocs/zend/public"
		     SetEnv APPLICATION_ENV "development"
		     <Directory [XAMPP DIRECTORY]/htdocs/zend/public>
			 DirectoryIndex index.php
			 AllowOverride All
			 Order allow,deny
			 Allow from all
			  </Directory>
		</VirtualHost></code>
 
 		-open c:\windows\system32\drivers\etc\hosts file as administrator in some text editor and paste this :
		127.0.0.1 zf-tutorial.localhost localhost
		-Restart Apahche by clicking Stop in XAMPP Control Panel for apache and Start
		-now you can open project by typing http://zend.localhost/users in address bar in your web browser 
