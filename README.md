Webareal api handler
====================
Api for getting and sending data to webareal type eshops

Full documentation [here](https://webareal.docs.apiary.io/#)

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist matejch/webareal-api-handler "*"
```

or add

```
"matejch/webareal-api-handler": "*"
```

to the require section of your `composer.json` file.


Usage
-----


You need _username_, _password_ and _token (API key)_ from your webareal eshop
API key is located in tab **API**

Token bearer for requests is retrieved in login request


``
All classes extend from \matejch\webarealApiHandler\WebarealHandler
``

```php
/** create handler, constructor requires username, password, adn token */
/** all can be acquired from your webareal admin interface */
$handler = new \matejch\webarealApiHandler\WebarealHandler($username,$password,$apiToken);

/** login before other requests */
$handler->login();

/** this is function for testing successful login, returns message whether access was granted */
$handler->test();

/** 
 * You can check info about how many request you have remaining, 
 * what's your limit and whether you are blocked 
 */
$handler->apiInfo()

/** API subdomain */
/** If you don't use subdomain set in class use this method*/
$handler->setBaseUrl($apiSubDomainUrl);

```

### Customers
Info about customers endpoint [here](https://webareal.docs.apiary.io/#reference/0/working-with-registered-customers/get-all-registered-customers)
```php
/** Only one api endpoint exists, and that is for getting list of customers */
$customers = new \matejch\webarealApiHandler\WCustomers($username,$password,$apiToken);
$customers->login();

$customers->asArray = true; // optional

/** also query string for searching specific customers can be set*/
$customers->searchBy(['limit' => 20,
                      'offset' => 0,
                      'sortBy' => 'id',
                      'sortDirection' => 'desc',
                      'findBy' => 'id',
                      'searchedString' => 'search string here']);

/** data can be returned as json string or array of customers */
$customers->get();

```

### Products
Info about product endpoints [here](https://webareal.docs.apiary.io/#reference/0/create-product/get-product-list)

```php

```

### Orders

Info about order endpoints [here](https://webareal.docs.apiary.io/#reference/0/order-list)

```php

```
TODO
-----

- [x] Login request
- [x] Test request
- [x] Api info
- [x] Get customers
- [x] Get products
- [ ] Create product
- [x] Get product info
- [x] Remove product
- [ ] Update product
- [ ] Create multiple products
- [ ] Update multiple products
- [ ] Create properties
- [ ] Update properties
- [ ] Get property
- [ ] Update property
- [ ] Remove property
- [ ] Get property list
- [ ] Get product variants list
- [ ] Get product variant
- [ ] Create product variants
- [ ] Update product variants
- [ ] Get product variant list
- [ ] Update product variant list
- [ ] Remove product variant
- [ ] Create property list
- [ ] Get property values list
- [ ] Create property values
- [ ] Create property value
- [ ] Get property value info
- [ ] Remove specific property value
- [ ] Update specific property value
- [ ] Get order list
- [ ] Update orders
- [ ] Get order info
- [ ] Update order
- [ ] Remove order
- [ ] Get all order states
- [ ] Update order products
- [ ] Update order product
- [ ] Remove order product