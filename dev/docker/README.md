Docker
======
Development environment has:

* /app - application container

Installation
------------
To prepare developing environment please choose one of the installation way replacing placeholder:

* `{my-docker-account}` Docker Hub account
* `{project-path}` project path

### Pre installation
Before start please be sure that was installed:

1. [Docker](https://docs.docker.com/engine/installation/)
2. Optional, create account in [Docker Hub](https://hub.docker.com/)

### Installation with building own Docker image
1. Build image by running command from CacheManager root directory, `sudo docker build -t {my-docker-account}/cachemanager -f dev/docker/app/Dockerfile .`
2. Check images `sudo docker images`
3. Run container `sudo docker run -d -p 2222:22 -v ~/{project-path}/CacheManager:/CacheManager -t {my-docker-account}/cachemanager`
4. Check container by executing command `sudo docker ps`

### Installation using prepared Docker image
1. Run command `sudo docker login`
2. Run command `sudo docker pull picamator/cachemanager`
3. Check images `sudo docker images`
4. Run container `sudo docker run -d -p 2222:22 -v ~/{project-path/CacheManager:/CacheManager -t picamator/cachemanager`
5. Check container by executing command `sudo docker ps`

SSH
---
Please use credentials bellow to connect to container via ssh:

1. user: `root`
2. password: `screencast`
3. ip: 0.0.0.0
4. port: 2222

To make connection via console simple run `ssh root@0.0.0.0 -p 2222`.

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

### IDE style configuration
Please install [EditorConfig](https://www.jetbrains.com/help/webstorm/2016.2/configuring-code-style.html) plugin to import editor's configuration.
