# [Get started with Docker](https://docs.docker.com/get-started/)

## Commands
* `docker ps`
* `docker ps -a`
* `docker images`
* `docker volume ls`

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


