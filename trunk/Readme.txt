Installation example:
#
httpdocs: upload all *.php files to your httpdocs directory;
httpdocs/archive: "root directory of all archives", chmod 777;
httpdocs/archive/2012-09-08: directory of all pictures of this day;
httpdocs/archive/2012-09-08/320x240: thumbnail directory;
cgi-bin: upload all *.pl and *.pm files here. chmod 755 copy2archive.pl;


if cam.php is not working, writing authorisations of your webserver are limited.
copy($uploadfile, $archivefile) causes an error;

a) give Apache writing access:
setfacl -m u:apache:rwx path_to_httpdocs
setfacl -m d:u:apache:rwx path_to_httpdocs
(yes, path_to_archive is not sufficient, but try it out first)

b) vhost.conf: set "php_admin_value open_basedir"
or
c) in php.ini: open_basedir "/var/www/vhosts/megsm.de/subdomains/webcam;/tmp"

example for vhost.conf:
<Directory /var/www/vhosts/megsm.de/subdomains/webcam/>
<IfModule sapi_apache2.c>
	php_admin_flag engine on
	# General settings
	php_admin_flag safe_mode off
	php_admin_value open_basedir "/var/www/vhosts/megsm.de/subdomains/webcam/:/tmp/"
	# Performance settings
	# Additional directives
</IfModule>

<IfModule mod_php5.c>
	php_admin_flag engine on
	# General settings
	php_admin_flag safe_mode off
	php_admin_value open_basedir "/var/www/vhosts/megsm.de/subdomains/webcam/:/tmp/"
	# Performance settings
	# Additional directives
</IfModule>

<IfModule mod_python.c>
    <Files ~ (\.py$)>
        SetHandler python-program
        PythonHandler mod_python.cgihandler
    </Files>
</IfModule>
<IfModule mod_fcgid.c>
    <Files ~ (\.fcgi)>
        SetHandler fcgid-script
        Options +FollowSymLinks +ExecCGI
    </Files>
</IfModule>
SSLRequireSSL
Options +Includes +ExecCGI
</Directory>