# Cheat Sheets
* [ATtiny13A](ATtiny13A.md)
* [Azure](Azure.md)
* [Docker](docker.md)
* [ESP8266](ESP8266.md)
* [Git](git.md)
* [Java](Java.md)
* [MongoDB](MongoDB.md)
* [Nodejs](Nodejs.md)
* [PHP](PHP.md)
* [PhoneGap](PhoneGap.md)
* [Raspberry](Raspberry.md)
* [React](React.md)
* [Wordpress](Wordpress.md)


# Env variables

```sh
# Set env variable
$env:APP_BACKEND_URL="http://localhost:3001/api/0.01/"

# Set env variable in Linux
export APP_BACKEND_URL="http://localhost:3001/api/0.01/"

# Set env variables to Apache server: 
# Add following to .htaccess file and restart server
SetEnv APP_BACKEND_URL http://localhost:3001/api/0.01/

# Delete env variable
Remove-Item Env:\APP_BACKEND_URL

# See current env variables in windows:
Get-ChildItem env:

# See current env variables in Linux:
printenv
```

# SSH keys

In home `folder/.ssh` run:

```sh
ssh-keygen -t rsa
# Filename f.ex. id_rsa

# Check created public key:
more id_rsa.pub
```

Add public key to Virtual Machine's `.ssh` folder `authorized_keys` file.

```
cat id_rsa.pub > ~\.ssh\authorized_keys
```

In local machine `.ssh` folder add file `config` with content:
```sh
Host loginnametoserver
	User loginnametoserver
	Hostname test.server.com
```

```sh
# Now you can login to VM with 
ssh loginnametoserver

# instead of 
ssh loginnametoserver@test.server.com
```

# SSH2 key conversion

```sh
ssh-keygen -i -f Identity.pub > sshpub

# Word count should be 1
wc sshpub
```

# DNS

### Custom domain (`example.com`) to Azure web app

```sh
# Add example.com to Azure web app custom domain list.

# DNS settings for example.com:

# For root domain: example.com
A @ IP address from Azure web app
TXT @ exampleapp.azurewebsites.net

# Subdomain www.example.com
# CNAME record
www
exampleapp.azurewebsites.net
```

### Other hosting companies

Note that f.ex. One.com doesn't support custom domains that are not in One.com.


# WAMP server

Default setting is such that `C:\wamp64\www` folder is `http://localhost` and `C:\wamp64\www\project1` folder is `http://localhost/project1`.

To change project address to `http://www.project1.test` following settings are needed.

1. Edit hosts file in `C:\Windows\System32\drivers\etc\hosts` and add following line:

```sh
127.0.0.1 www.project.test

# Existing setting in hosts file is:
127.0.0.1 localhost
```

2. Then edit Apache's `httpd-vhosts.conf` file (can be accessed from Apache icon) and add following settings:

```sh
<VirtualHost *:80>
  ServerName project1
  ServerAlias www.project1.test
  DocumentRoot "${INSTALL_DIR}/www/project1"
  <Directory "${INSTALL_DIR}/www/project1/">
    Options +Indexes +Includes +FollowSymLinks +MultiViews
    AllowOverride All
    Require local
  </Directory>
</VirtualHost>
```
