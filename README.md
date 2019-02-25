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