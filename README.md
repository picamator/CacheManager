CacheManager
============

[![Latest Stable Version](https://poser.pugx.org/picamator/cachemanager/v/stable.svg)](https://packagist.org/packages/picamator/cachemanager)
[![License](https://poser.pugx.org/picamator/cachemanager/license.svg)](https://packagist.org/packages/picamator/cachemanager)
[![Build Status](https://travis-ci.org/picamator/CacheManager.svg?branch=master)](https://travis-ci.org/picamator/CacheManager)
[![Coverage Status](https://img.shields.io/coveralls/picamator/CacheManager.svg)](https://coveralls.io/r/picamator/CacheManager?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/8b533637-392d-4be8-8204-77ff22f460ca/mini.png)](https://insight.sensiolabs.com/projects/8b533637-392d-4be8-8204-77ff22f460ca)

CacheManager is an application providing wrapper over 3-rd party cache libraries optimizing for saving RESTful API's or SQL search results.

The general approach to save search response is based on building cache key as a hash of search query.
But that approach is not working well for two slightly different queries. Moreover it's failed to combine data from cache and server. CacheManager solves those problems for special cases like searching entities by it's ids.

Cache libraries
---------------
CacheManager does not implement any default cache library neither own one. Instead CacheManager asks objects to implement [PSR-6](http://www.php-fig.org/psr/psr-6/). 
Having that with [Symfony DI](https://github.com/symfony/dependency-injection) and 
[PSR-6 Adapters](https://github.com/php-cache?utf8=%E2%9C%93&query=adapter) it makes possible to use any cache library without limitation. 

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
### RESTful API
Assume application works with RESTful API, where:
* `cutomer` - entity name
* `query`   - parameter to save search criteria
* `IN`      - function similar to MySQL IN
* `fields`  - parameter with comma separated entity's fields

Each of the samples below shows pair of API requests that runs one after another or during one application request circle. 

#### Sample 1
1. `GET: customer\?query="id IN(1,2,3)&fields='name,address'"`
2. `GET: customer\?query="id IN(1,2)&fields='name'"`

The second request SHOULD use cache because it had information about `customer` with id 1 and 2.

#### Sample 2
1. `GET: customer\?query="id IN(1,2,3)&fields='name'"`
2. `GET: customer\?query="id IN(1,2)&fields='name,address'"`

The second request SHOULD NOT use cache because it asks more information about `customer` that was saved in the cache.
Therefore after obtained data the server response SHOULD be saved in cache overriding the previously saved data.

#### Sample 3
1. `GET: customer\?query="id IN(1,2,3)&fields='name,address'"`
2. `GET: customer\?query="id IN(3,4)&fields='name'"`

The second query SHOULD use cache for `customer` with id 3 and application SHOULD get only information about id 4.

### SQL
Let's application use MySQL with:
* `customer` - table
* `id`, `name`, and `address` - columns in `customer` table

Each of SQL queries in samples bellow runs one after another or during one application request circle.
SQL samples SHOULD behavior similar to corresponding RESTful API samples.

#### Sample 1
1. `SELECT name, address FROM customer WHERE id IN(1,2,3)`
2. `SELECT name FROM customer WHERE id IN(1,2)`

#### Sample 2
1. `SELECT name FROM customer WHERE id IN(1,2,3)`
2. `SELECT name, address FROM customer WHERE id IN(1,2)`

#### Sample 3
1. `SELECT name, address FROM customer WHERE id IN(1,2,3)`
2. `SELECT name FROM customer WHERE id IN(3,4)`

Usage
-----
### Memcached
[MemcachedManager](https://github.com/picamator/MemcachedManager) is an example to use CacheManager with [Memcached](https://memcached.org/).

### Custom implementation
To start using CacheManager it's need to implement:
* `Psr\Cache\CacheItemPoolInterface `

and optionally SPI:
* `Spi\ObserverInterface`

There is illustrative code example bellow. Please use DI library to build dependencies in your application.

```php
<?php
declare(strict_types = 1);

use \Picamator\CacheManager\Operation\Save;
use \Picamator\CacheManager\Operation\Search;
use \Picamator\CacheManager\Operation\Delete;

use \Picamator\CacheManager\ObjectManager;
use \Picamator\CacheManager\Cache\CacheItemFactory;
use \Picamator\CacheManager\Data\SearchResultFactory;

use \Picamator\CacheManager\Cache\KeyGenerator;
use \Picamator\CacheManager\CacheManager;
use \Picamator\CacheManager\CacheManagerSubject;

use \Picamator\CacheManager\Data\SearchCriteriaBuilder;

/** Classes for implementation */
// Required: use interface \Psr\Cache\CacheItemPoolInterface over your cache library to fit PSR-6
$cacheItemPoolMock = new CacheItemPoolMock();

// Optional: use interface \Picamator\CacheManager\Spi\ObserverInterface
$afterSearchMock = new AfterSearchMock();

/** Existing Classes */
// Object creator & factories
$objectManager          = new ObjectManager();
$cacheItemFactory       = new CacheItemFactory($objectManager);
$searchResultFactory    = new SearchResultFactory($objectManager);

// Building keys for saving data to cache
$cacheKeyGenerator      = new KeyGenerator();

// In real live please use Proxies or Lazy Loading
$operationSave          = new Save($cacheKeyGenerator, $cacheItemPoolMock, $cacheItemFactory);
$operationSearch        = new Search($cacheKeyGenerator, $cacheItemPoolMock, $searchResultFactory);
$operationDelete        = new Delete($cacheKeyGenerator, $cacheItemPoolMock);

// Instantiate main cache manager object
$cacheManager           = new CacheManager($operationSave, $operationSearch, $operationDelete);

// Wrap Cache managed over Observer, it's possible to omit wrapper if application does not need such kind extensibility
$cacheManagerSubject    = new CacheManagerSubject($cacheManager);

// attach observer to execute after search
$cacheManagerSubject->attach('afterSearch', $afterSearchMock);

// prepare criteria for search
$searchCriteriaBuilder  = new SearchCriteriaBuilder($objectManager);
$searchCriteria = $searchCriteriaBuilder
                    ->setContextName('cloud')
                    ->setEntityName('customer')
                    ->setIdList([1, 2, 3])
                    ->setFieldList(['id', 'name'])
                    ->setIdName('id')
                    ->build();

$searchResult = $cacheManagerSubject->search($searchCriteria);

// result api details
$searchResult->count();         // number of returned data from cache e.g. 2
$searchResult->getData();       // array of cache items
$searchResult->getMissedData(); // array of missed in cache id's e.g. [1]
$searchResult->hasData();       // boolean to show does something fit $searchCriteria in cache

```

API&SPI
-------
### API
API includes:
* interfaces inside [Api](src/Api) directory
* all Exceptions

### SPI
SPI includes:
* interfaces inside [Spi](src/Spi) directory
* events: beforeSave, afterSave, beforeSearch, afterSearch, beforeDelete, afterDelete

Documentation
-------------
* UML diagrams can be found in [doc/uml](doc/uml) folder

Developing
----------
To configure developing environment please:

1. Follow [install and run Docker container](dev/docker/README.md)
2. Run inside project root in the Docker container `composer install` 

### Backward compatibility
Please follow steps bellow to keep Backward compatibility:
* keep stable API & SPI SHOULD
* keep stable constructor injection signature
* keep stable throwing Exceptions type

### Backward compatibility validation
To check backward compatibility please run integration tests in [MemcachedManager](https://github.com/picamator/MemcachedManager).

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
