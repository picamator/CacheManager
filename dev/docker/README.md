Docker
======
Directory contains Dockerfiles per containers:
* /app - application container

Installation
------------
To prepear developing environment please choose one of the installation way.

### Installation with building own Docker image
1. Install Docker, instraction to you operation system is [here](https://docs.docker.com/engine/installation/)
2. Run command from CacheManager root directory, substituting `mydockeraccount` by your own, `sudo docker build -t mydockeraccount/cachemanager -f dev/docker/app/Dockerfile .`

### Instalation with using prepeared Docker image
> in-progress
