CacheManager
============

[![Latest Stable Version](https://poser.pugx.org/picamator/cachemanager/v/stable.svg)](https://packagist.org/packages/picamator/cachemanager)
[![License](https://poser.pugx.org/picamator/cachemanager/license.svg)](https://packagist.org/packages/picamator/cachemanager)
[![Build Status](https://travis-ci.org/picamator/CacheManager.svg?branch=master)](https://travis-ci.org/picamator/CacheManager)
[![Coverage Status](https://img.shields.io/coveralls/picamator/CacheManager.svg)](https://coveralls.io/r/picamator/CacheManager?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/8b533637-392d-4be8-8204-77ff22f460ca/mini.png)](https://insight.sensiolabs.com/projects/8b533637-392d-4be8-8204-77ff22f460ca)

CacheManager is an application providing wrapper over 3-rd party cache libraries optimizing for saving API's search results.

The general approach to save search response building cache key as a hash of search query.
But that approach is not working for two slightly different queries. Moreover it's failed to combine data from cache and server. Therefore CacheManager solves those problems for special cases such as searching by id or ids.

Cache libraries
---------------
CacheManager does not have any default cache library neither own one. Instead CacheManager asks objects that are implemented [PSR-6](http://www.php-fig.org/psr/psr-6/). Having that with [Symfony DI](https://github.com/symfony/dependency-injection) and 
[PSR-6 Adapters](https://github.com/php-cache?utf8=%E2%9C%93&query=adapter) it's possible to use any cache library without limitation. 

Requirements
------------
* PHP 7.0.x

Installation
------------
* Update to your `composer.json`

```json
{
    "require": {
        "picamator/cachemanager": "~1.0"
    }
}
```

* Run `composer install --no-dev`

Specification
--------------
Assume application works with API, where:
* `myEntity` - entity name
* `query`- parameter to save search criteria
* `IN` - function similar to MySQL IN
* `fields` - parameter with comma separated entities fields

Each of the samples below shows pair of API requests that runs one after another. 

### Sample 1
1. `myEntity\?query="id IN(1,2,3)&fields='name,address'"`
2. `myEntity\?query="id IN(1,2)&fields='name'"`

The second request SHOULD use cache because it already has information about `myEntity` with ids 1 and 2.

### Sample 2
1. `myEntity\?query="id IN(1,2,3)&fields='name'"`
2. `myEntity\?query="id IN(1,2)&fields='name,address'"`

The second request SHOULD NOT use cache because it asks more information about `myEntity` that is in the cache.
Therefore after obtaining data from server response SHOULD be saved in cache overriding the previously saved data.

### Sample 3
1. `myEntity\?query="id IN(1,2,3)&fields='name,address'"`
2. `myEntity\?query="id IN(3,4)&fields='name'"`

The second query SHOULD use cache for `myEntity` with id 3 and application SHOULD get only information about id 4.

Usage
-----
### Memcache
The repository [MemcachedManager](https://github.com/picamator/MemcachedManager) is an example to use CacheManager with [Memcached](https://memcached.org/).

### Custom implementation
To start using CacheManager it's need to implement several API factory interfaces:
1. `Api\Cache\CacheItemFactoryInterface`
2. `Api\Data\SearchResultFactoryInterface`

and optionally SPI interface:
1. `Spi\ObserverInterface`

There is code sample bellow, it's illustrate example in live please use DI library to build dependencies.

```php
<?php
declare(strict_types = 1);

use \Picamator\CacheManager\Operation\Save;
use \Picamator\CacheManager\Operation\Search;
use \Picamator\CacheManager\Operation\Invalidate;


use \Picamator\CacheManager\Cache\KeyGenerator;
use \Picamator\CacheManager\CacheManager;
use \Picamator\CacheManager\CacheManagerSubject;

use \Picamator\CacheManager\Data\SearchCriteria;

/** Classes for implementation */
// Required: use interface \Psr\Cache\CacheItemPoolInterface over your cache library to fit PSR-6
$cacheItemPoolMock = new CacheItemPoolMock();

// Required: use interface \Api\Data\SearchResultFactoryInterface
$cacheResultFactoryMock = new CacheResultFactoryMock();

// Required: use interface \Api\Cache\CacheItemFactoryInterface
$cacheItemFactoryMock = new CacheItemFactoryMock();

// Required: use interface \Picamator\CacheManager\Api\Data\SearchResultFactoryInterface
$searchResultFactoryMock = new SearchResultFactoryMock();

// Optional: use interface \Picamator\CacheManager\Spi\ObserverInterface
$afterSearchMock = new AfterSearchMock();

/** Existing Classes */
// Building keys for saving data to cache
$cacheKeyGenerator = new KeyGenerator();

// In real live please use Proxies or Lazy Loading
$operationSave          = new Save($cacheKeyGenerator, $cacheItemPoolMock, $cacheItemFactoryMock);
$operationSearch        = new Search($cacheKeyGenerator, $cacheItemPoolMock, $searchResultFactoryMock);
$operationInvalidate    = new Invalidate($cacheKeyGenerator, $cacheItemPoolMock);

// Instantiate main cache manager object
$cacheManager = new CacheManager($operationSave, $operationSearch, $operationInvalidate);

// Wrap Cache managed over Observer, it's possible to omit wrapper if application does not need such kind extensibility
$cacheManagerSubject = new CacheManagerSubject($cacheManager);

// attach observer to execute after search
$cacheManagerSubject->attach('afterSearch', $afterSearchMock);

// prepare criteria for search
$searchCriteria = new SearchCriteria(
    'customer',     // $entityName
    [1, 2, 3],      // $idList
    ['id', 'name'], // $fieldList
    'id',           // $idName
    'cloud'         // $contextName
);

$searchResult = $cacheManagerSubject->search($searchCriteria);

// result api details
$searchResult->count(); // number of returned data from cache e.g. 2
$searchResult->getData(); // array of cache items
$searchResult->getMissedData(); // array of missed in cache id's e.g. [1]
$searchResult->hasData(); // boolean to show does something fit $searchCriteria in cache

```

Pitfalls
--------
> @todo in-progress, it's about
> invalidation, pagination over mixed sources API and cache
> how to deal with different API sources and identical entities
> how apply CacheManager for Databases

Documentation
-------------
* UML diagrams can be found in [doc/uml](doc/uml) folder

> @todo in-progress, it's about
> link to wiki with API & SPI details
> link to phpclasses

Developing
----------
To configure developing environment please:

1. Follow [install and run Docker container](dev/docker/README.md)
2. Run inside project root in the Docker container `composer install` 

Contribution
------------
If you find this project worth to use please add a star. Follow changes to see all activities.
And if you see room for improvement, proposals please feel free to create an issue or send pull request.
Here is a great [guide to start contributing](https://guides.github.com/activities/contributing-to-open-source/).

Please note that this project is released with a [Contributor Code of Conduct](http://contributor-covenant.org/version/1/4/).
By participating in this project and its community you agree to abide by those terms.

License
-------
CacheManager is licensed under the MIT License. Please see the [LICENSE](LICENSE.txt) file for details
