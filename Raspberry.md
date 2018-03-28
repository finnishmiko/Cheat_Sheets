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

## [NodeJS app with Nginx](https://stackoverflow.com/questions/5009324/node-js-nginx-what-now)

Create the file `yourdomain.com` at `/etc/nginx/sites-available/` with content:

```sh
upstream app_yourdomain {
    server 127.0.0.1:3000;
    keepalive 8;
}

# the nginx server instance
server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com www.yourdomain.com;
    access_log /var/log/nginx/yourdomain.com.log;

    # pass the request to the node.js server with the correct headers
    # and much more can be added, see nginx config options
    location / {
      proxy_http_version 1.1;
      proxy_set_header Upgrade $http_upgrade;
      proxy_set_header Connection "upgrade";
      proxy_set_header X-Real-IP $remote_addr;
      proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
      proxy_set_header Host $http_host;
      proxy_set_header X-NginX-Proxy true;

      proxy_pass http://app_yourdomain/;
      proxy_redirect off;
    }
 }
```

Then enable the file:

```sh
cd /etc/nginx/sites-enabled/ 
ln -s /etc/nginx/sites-available/yourdomain.com yourdomain.com
````

Test for syntax mistakes:
```sh
nginx -t
```

Restart Nginx:
```sh
sudo /etc/init.d/nginx restart
```

Lastly start the node server.



## Copy files to and from server

```sh
scp username@source:/location/to/file username@destination:/where/to/put
```

F.ex. copy a file from Raspberry to a local machine.

`scp username@192.168.1.2:/home/pi/project/file.txt /c/project`