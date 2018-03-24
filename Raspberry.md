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


