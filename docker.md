# [Get started with Docker](https://docs.docker.com/get-started/)

## Commands

- `docker ps` aka. `docker container ls`
- `docker ps -a`
- `docker images`
- `docker volume ls`
- `docker service ls`

| Command                                                                 | Description                                    |
| ----------------------------------------------------------------------- | ---------------------------------------------- |
| `docker logs CONTAINER --since 10m`                                     | Fetch logs for last 10 minutes from container: |
| [`docker cp`](https://docs.docker.com/engine/reference/commandline/cp/) | Copy files to and from container               |

F.ex:

```sh
docker cp ./filename.txt ImageName:/tmp/

docker logs -f CONTAINER

# Save log to file
docker logs -f CONTAINER &> logname.log &
# -f is same as --follow and it writes all existing logs and continues logging afterwards.
# &> redirects both the standard output and standard error.
# last & runs the method in the background.

docker service logs SERVICE
```

Go into the container

```
docker exec -it CONTAINER /bin/sh
```

Add source tag to the image:

```sh
docker tag SOURCE_IMAGE[:TAG] TARGET_IMAGE:new_tag_name_here
```

## Removing old containers that are not in use

```
docker image ls
docker image prune

docker container ls
docker container prune
```

# VS Code devcontainer

Error while opening devcontainer from DevOps repository: "Cannot determine the organization name for this 'dev.azure.com' remote url". Following setting fixed it:

```PowerShell
git config --global credential.useHttpPath true
```

# Use local Docker to control Docker in VM:

### Open TCP port for Docker in VM

```
# Create a file with this content:
# /etc/systemd/system/docker.service.d/override.conf
[Service]
ExecStart=
ExecStart=/usr/bin/dockerd -H fd:// -H tcp://0.0.0.0:2376
```

and run

```sh
systemctl daemon-reload
service docker restart
```

### SSH setup for docker images

```
ssh myuser@mypage.fi -L 22376:localhost:2376
```

or add following to ssh config and use only `ssh mypage` to open tunnel to VM.

### .ssh/config

```
Host mypage
	Hostname mypage.fi
	User myuser
	LocalForward 22376 localhost:2376
```

# Stack deployment

```sh
# **** Set env variable to use Docker with SSH port forward
$env:DOCKER_HOST="localhost:22376"

# **** initialise swarm (needs to be run only once)
docker swarm init

# **** add stack
docker stack deploy -c .\mypage.yml mypage

# **** or add stack without first setting env variable:
docker -H localhost:22376 stack deploy -c mypage.yml mypage

# **** remove stack
docker stack rm mypage

# *** update service image
docker service update --force --detach=false --image=mypage-front:latest mypage-front
# also this command can be run without env variable with parameter
# -H localhost:22376

```

## Example Dockerfile to serve React app

Use Nginx image to serve React's build folder's static files. First build React app with requider env variables. Then build Docker image with `Dockerfile`:

```sh
# *** Build image in local machine for testing:
# docker build -t mypage-front .
# docker run -i -p 8000:80 --rm mypage-front
# *** then go to http://localhost:8000
#
# *** Or build the image in VM for deployment:
# docker -H localhost:22376 build -t mypage-front .
FROM nginx:alpine
COPY build /usr/share/nginx/html
```

This image can be part of the stack f.ex. `mypage.yml`

```yml
version: "3.1"
services:
  front:
    image: mypage-front
    deploy:
      replicas: 2
      update_config:
        parallelism: 1
        delay: 10s
    ports:
      - 8080:80
```

If there are backend and frontend images in VM in ports 8088 and 8080 then Nginx can be configured with following settings:

```sh
        location / {
                proxy_pass       http://localhost:8080;
                proxy_set_header Host      $host;
                proxy_set_header X-Real-IP $remote_addr;
                proxy_http_version 1.1;
        }
        location /api/ {
                proxy_redirect off;
                proxy_pass       http://localhost:8088;
                proxy_set_header Host      $host;
                proxy_set_header X-Real-IP $remote_addr;
        }
```

# Docker volumes in Windows

To use Docker volumes in Windows local admin rights are needed. If normally login is done with AzureAD account then separate local admin account is needed:

- Windows settings: Create DockerAdmin local admin account with Administrator priviledges
- Login and logout to DockerAdmin
- Share the required folder from AzureAD account to DockerAdmin. Read/write access is needed
- Docker settings \ Shared Drives select drive C and login with DockerAdmin account

# Containers

- Create following files:
  - [Dockerfile](docker-getting_started/Dockerfile)
  - [requirements.txt](docker-getting_started/requirements.txt)
  - [app.py](docker-getting_started/app.py)
  - [docker-compose.yml](docker-getting_started/docker-compose.yml)
- And one folder to persist Redis data \* ./data

Then build and run the app:

```sh
docker build -t friendlyhello .
```

```sh
docker run -p 4000:80 friendlyhello
```

Either visit the page
[http://localhost:4000](http://localhost:4000)
or use curl:

```sh
curl http://localhost:4000
```

CTRL+C to exit.

With -d the app can be run in the background, in detached mode:

```sh
docker run -d -p 4000:80 friendlyhello
```

## Services

```sh
docker-compose.yml

# This is needed before docker stack deploy can be used
docker swarm init

docker stack deploy -c docker-compose.yml getstartedlab

# See a list of containers
docker stack ps getstartedlab

# Take down the app and the swarm
docker stack rm getstartedlab

# One node swarm is still running
docker node ls

# Take down the swarm with
docker swarm leave --force

```

## Stacks

- Visualizer runs in port 8080
- Redis port 6379
