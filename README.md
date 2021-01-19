#Waardepapieren API client for PHP</h1>

## Requirements ##
To use the waardepapieren API client, the following things are required:

+ PHP >= 7.4.0


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
$waardepapieren = new \Conduction\Waardepapieren\WaardepapierenApiClient();
``` 

## Validating JWS Tokens ##
For validating JWS tokens we provide two functions.

For validating the signature of the token we use the verifyJWSToken function.
This function needs the public JWK key and the JWS token.
```php
$valid = $waardepapieren->verifyJWSToken($key, $token);
```

It wil then return a boolean based on the fact if the signature is valid.

The second function named checkTokenData looks for differences is the data that's present in the JWS token compared to an array.
The provided array is the data of the claim.

This function needs the JWS token and a array with data.
```php
$valid = $waardepapieren->checkTokenData($token, $data);
```

It will then return a boolean based on the fact if there is a difference between the two datasets.
True means there are no differences between the datasets and false if the two datasets differ from each other.

## License ##
Copyright (c) 2018 Copyright. All Rights Reserved. Made with love by <strong>Conduction</strong>.

## Support ##
Contact: <a href="https://conduction.nl" target="_blank">www.conduction.nl </a> — info@conduction.nl
