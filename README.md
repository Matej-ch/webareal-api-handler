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
```php

```

TODO
-----

- [x] Login request
- [x] Test request
- [x] Api info
- [x] Get customers
- [ ] Get products
- [ ] Create product
- [ ] Get product info
- [ ] Remove product
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