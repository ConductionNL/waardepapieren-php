#Waardepapieren API client for PHP</h1>

## Requirements ##
To use the waardepapieren API client, the following things are required:

+ Get yourself a [waardepapieren acount](https://www.id-vault.com).
  And make sure you have an organization and application set up.
+ PHP >= 7.1.3


## Composer Installation ##

By far the easiest way to install the waardepapieren API client is to require it with [Composer](http://getcomposer.org/doc/00-intro.md).

    $ composer require conduction/waardepapieren-php:dev-main

    {
        "require": {
            "conduction/waardepapieren-php":dev-main
        }
    }



## Getting started ##

Initializing the waardepapieren API client.

```php
$idVault = new \Conduction\Waardepapieren\WaardepapierenApiClient();
``` 

## License ##
Copyright (c) 2018 Copyright. All Rights Reserved. Made with love by <strong>Conduction</strong>.

## Support ##
Contact: <a href="https://conduction.nl" target="_blank">www.conduction.nl </a> — info@conduction.nl
