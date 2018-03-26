# Raspberry pi

## Run node program at startup
```sh
sudo nano /ect/rc.local
```
Add following before `exit 0`
```sh
sleep 60
cd /home/pi/<program folder>
nodemon
```

Check the running Node tasks
```sh
ps -e|grep node
```

## Copy files to and from server

```sh
scp username@source:/location/to/file username@destination:/where/to/put
```

F.ex. copy a file from Raspberry to a local machine.

`scp username@192.168.1.2:/home/pi/project/file.txt /c/project`