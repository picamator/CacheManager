Docker
======
Directory contains Dockerfiles per containers:
* /app - application container

Installation
------------
To prepare developing environment please choose one of the installation way.

Commands inside inside installation instructions contains placeholders for replacement:
* `my-docker-account` by your own
* `project-path` absolute path to CacheManager

Moreover it's assumed that:
* Docker was installed, please follow [installation steps](https://docs.docker.com/engine/installation/)
* The [Docker Hub](https://hub.docker.com/) account was created

### Installation with building own Docker image
1. Build image by running command from CacheManager root directory, `sudo docker build -t my-docker-account/cachemanager -f dev/docker/app/Dockerfile .`
2. Check images `sudo docker images`
3. Run container `sudo docker run -d -p 2222:22 -v ~/project-path/CacheManager:/CacheManager -t my-docker-account/cachemanager`
4. Check container by executing command `sudo docker ps`

### Installation using prepared Docker image
1. Run command `sudo docker login`
2. Run command `sudo docker pull picamator/cachemanager`
3. Check images `sudo docker images`
4. Run container `sudo docker run -d -p 2222:22 -v ~/project-path/CacheManager:/CacheManager -t picamator/cachemanager`
5. Check container by executing command `sudo docker ps`

SSH
---
Please use credentials bellow to connect to container via ssh:

1. user: `root`
2. password: `screencast`

Configuration IDE (PhpStorm)
---------------------------- 
### Remote interpreter
1. Use ssh connection to set php interpreter
2. Set "Path mappings": <progect root>->/CacheManager

More information is [here](https://confluence.jetbrains.com/display/PhpStorm/Working+with+Remote+PHP+Interpreters+in+PhpStorm).

### UnitTests
1. Configure UnitTest using remote interpreter. 
2. Choose "Use Composer autoload"
3. Set "Path to script": /CacheManager/vendor/autoload.php
4. Set "Default configuration file": /CacheManager/dev/tests/unit/phpunit.xml.dist

More information is [here](https://confluence.jetbrains.com/display/PhpStorm/Running+PHPUnit+tests+over+SSH+on+a+remote+server+with+PhpStorm).
