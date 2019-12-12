# [Get started with Docker](https://docs.docker.com/get-started/)

## Commands
* `docker ps`
* `docker ps -a`
* `docker images`
* `docker volume ls`

Command | Description
---|---|
`docker logs CONTAINER --since 10m` | Fetch logs for last 10 minutes from container: 
[`docker cp`](https://docs.docker.com/engine/reference/commandline/cp/) | Copy files to and from container

F.ex:
```
docker cp ./filename.txt /tmp/
docker logs -f CONTAINER
```

Go into the container
```
docker exec -it CONTAINER /bin/sh
```


## Docker images

### Open TCP port for Docker in VM
```
# /etc/systemd/system/docker.service.d/override.conf
[Service]
ExecStart=
ExecStart=/usr/bin/dockerd -H fd:// -H tcp://0.0.0.0:2376
```

### SSH setup for docker images
```
ssh myuser@mypage.fi -L 22376:localhost:2376
```
or add following to ssh config and use only `ssh mypage`

### .ssh/config
```
Host mypage
	Hostname mypage.fi
	User myuser
	LocalForward 22376 localhost:2376
```

### Deployment
```
# **** Set env variable to use Docker with SSH port forward
# $env:DOCKER_HOST="localhost:22376"
# **** add stack
# docker stack deploy -c .\mypage.yml mypage
# **** remove stack
# docker stack rm mypage
# *** update service image
# docker service update --force --detach=false --image=mypage-front:latest mypage-front
#
```

## Removing old containers that are not in use
```
docker image ls
docker image prune

docker container ls
docker container prune
```

### mypage.yml
```yml
version: '3'
services:
  nginx-front:
    image: mypage-front
    deploy:
      replicas: 2
      update_config:
        parallelism: 1
        delay: 10s
    ports:
      - 8080:80
```

## Docker volumes in Windows

To use Docker volumes in Windows local admin rights are needed. If normally log in is done with AzureAD account then separate local admin account is needed:
- Windows settings: Create DockerAdmin local admin account with Administrator priviledges
- Log in and logout to DockerAdmin
- Share the required folder from AzureAD account to DockerAdmin. Read/write access is needed
- Docker settings \ Shared Drives select drive C and login with DockerAdmin account


## Containers
* Create following files:
  * [Dockerfile](docker-getting_started/Dockerfile)
  * [requirements.txt](docker-getting_started/requirements.txt)
  * [app.py](docker-getting_started/app.py)
  * [docker-compose.yml](docker-getting_started/docker-compose.yml)
* And one folder to persist Redis data
	* ./data

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

* Visualizer runs in port 8080
* Redis port 6379


