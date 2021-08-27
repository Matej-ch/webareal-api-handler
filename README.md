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

$products = new \matejch\webarealApiHandler\WProduct($username,$password,$apiToken);
$products->login();

$products->asArray = true; // optional

/** get list of products */
/** filter can be set with $products->searchBy(array of searchable options) */
$products->get();

/** 
 * id of product necessary for update, delete, view can be obtained with get() method, 
 * or through csv from your admin interface
 */

/** create new product */
$products->setFields(['name' => 'Name', ....]);
$products->create();

/** update existing product, you must know id beforehand */
$products->setFields(['secondName' => 'Second', ....]);
$products->update($id);

/** delete existing product, you must know id beforehand */
$products->delete($id);

/** detail info about one product, you must know id beforehand */
$products->view($id);

```

### Orders

Info about order endpoints [here](https://webareal.docs.apiary.io/#reference/0/order-list)

```php

```

### Product properties
Info product properties endpoints [here](https://webareal.docs.apiary.io/#reference/0/multiple-manipulation-with-products/update-property)

```php
$property = new \matejch\webarealApiHandler\WProductProperty($username,$password,$apiToken);
$property->login();

$property->get();

$property->view($id);

/** before calling update, setFields must set fields you want to update on property */
$property->setFields(['name' => 'Color']);
$property->update($id);

/** delete property */
$property->delete($id);

```

### Product variants
Info product variants endpoints [here](https://webareal.docs.apiary.io/#reference/0/get-product-variants/create-product-variant)

```php
$variants = new \matejch\webarealApiHandler\WProductVariants($username,$password,$apiToken);
$variants->login();

/** get list of variants, can be search by using searchBy method */
$variants->get();

$variants->view($id);

/** before calling create, setFields must set fields you want to update on property */
$variants->setFields(['idProduct' => 9,'name' => 'Jacket']);
$variants->create($id);

/** before calling update, setFields must set fields you want to update on property */
$variants->setFields(['idProduct' => 9,'name' => 'Jacket']);
$variants->update($id);

/** delete property */
$variants->delete($id);
```

TODO
-----

- [x] Login request
- [x] Test request
- [x] Api info
- [x] Get customers
- [x] Get products
- [x] Create product
- [x] Get product info
- [x] Remove product
- [x] Update product
- [ ] Create multiple products
- [ ] Update multiple products
- [ ] Create properties
- [ ] Update properties
- [x] Get property
- [x] Update property
- [x] Remove property
- [x] Get property list
- [x] Create product variant
- [x] Get product variants list
- [x] Get product variant
- [ ] Create product variants
- [ ] Update product variants
- [x] Get product variant list
- [x] Update product variant list
- [x] Remove product variant
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