<h1 align="center">id-Vault API client for PHP</h1>

## Requirements ##
To use the id-Vault API client, the following things are required:

+ Get yourself a [id-Vault Account](https://www.id-vault.com).
  And make sure you have an organization and application set up.
+ PHP >= 7.1.3


## Composer Installation ##

By far the easiest way to install the id-Vault API client is to require it with [Composer](http://getcomposer.org/doc/00-intro.md).

    $ composer require conduction/id-vault-api-php:dev-main

    {
        "require": {
            "conduction/id-vault-api-php":dev-main
        }
    }



## Getting started ##

Initializing the id-Vault API client.

```php
$idVault = new \Conduction\IdVaultApi\IdVaultApiClient();
``` 

## Sending emails ##
This function allows us to send mails through id-vault.

```php
$body = '<p> test mail </p>';
$mail = $idVault->sendMail('62817d5c-0ba5-4aaa-81f2-ad0e5a763cd4', $body, 'test mail', 'gino@conduction.nl', 'no-reply@id-vault.com');
```
For this to work you have to make sure your mailgun key is available in your id-vault application.
The function will then use the Mailgun key to send the email to the provided receiver.

Make sure you provide a valid html body for the email.


## Authenticating users ##
We can use the `$idVault->authenticateUser();` to retrieve user information from id-Vault. 
We do this by providing the function with:
+ code provided by the `https://id-vault.com/oath` endpoint.
+ the application id.
+ the application secret.
+ (optional) state.

```php
$user = $idVault->authenticateUser('fecbf5ca-a28b-41a1-8e6a-77e8f8939710', '05dd458d-9f33-4f16-9ec3-8d7f0a3b0791', '024a820d-860c-4ad5-b4c7-e507334e949f');
```

This then returns the following json object:

```json
{
    "tokenType": "bearer",
    "expiresIn": "3600",
    "scope": "schema.person.email+schema.person.given_name+schema.person.family_name",
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiI0NTVmNzgyZS00YjJkLTRjYmItYWU4My02ZTI5N2UyNWZlZjkiLCJuYW1lIjoiTWFybGVlbiBSb21pam4iLCJlbWFpbCI6Im1hcmxlZW5yb21pam43M0BnbWFpbC5jb20iLCJnaXZlbl9uYW1lIjoiTWFybGVlbiIsImZhbWlseV9uYW1lIjoiUm9taWpuIiwiY2xhaW1zIjp7InNjaGVtYS5wZXJzb24uZW1haWwiOlt7ImVtYWlsIjoibWFybGVlbnJvbWlqbjczQGdtYWlsLmNvbSJ9XSwic2NoZW1hLnBlcnNvbi5naXZlbl9uYW1lIjpbeyJnaXZlbl9uYW1lIjoiTWFybGVlbiJ9XSwic2NoZW1hLnBlcnNvbi5mYW1pbHlfbmFtZSI6W3siZmFtaWx5X25hbWUiOiJSb21pam4ifV19LCJpc3MiOiI0NTVmNzgyZS00YjJkLTRjYmItYWU4My02ZTI5N2UyNWZlZjkiLCJhdWQiOiJodHRwczpcL1wvZGV2LmNoZWNraW5nLm51XC91c2Vyc1wvYXV0aFwvaWR2YXVsdCIsImV4cCI6IjM2MDAiLCJqdGkiOiIxMDMzYzFmMy1hNzhkLTQ0Y2ItYWQxMy0zZjg4OGUwNzA0MTEiLCJhbGciOiJIUzI1NiIsImlhdCI6MTYwNzUxOTczMn0.Psq2XnCrgIgNQ-R4PHvctmCgC-VUhjBBNUFasHB0lAE",
    "state": "XXXX-XXXX-XXXX-XXXX"
}
```

In this JSON object access_token is an JWT token which holds the requested user information.

which looks like this:

```json
{
  "sub": "455f782e-4b2d-4cbb-ae83-6e297e25fef9",
  "name": "test person",
  "email": "testperson@gmail.com",
  "given_name": "test",
  "family_name": "person",
  "claims": {
    "schema.person.email": [
      {
        "email": "testperson@gmail.com"
      }
    ],
    "schema.person.given_name": [
      {
        "given_name": "Test"
      }
    ],
    "schema.person.family_name": [
      {
        "family_name": "Person"
      }
    ]
  },
  "iss": "455f782e-4b2d-4cbb-ae83-6e297e25fef9",
  "aud": "https://checking.nu/users/auth/idvault",
  "exp": "3600",
  "jti": "1033c1f3-a78d-44cb-ad13-3f888e070411",
  "alg": "HS256",
  "iat": 1607519732
}
```

## Creating dossiers ##

With this function we are able to create a dossier for an id-vault user.

We do this by providing the function with:
+ array of scopes this dossier requires (the scopes must be authorized by the id-vault user).
+ access_token provided by id-vault.
+ name of the dossier.
+ goal of the dossier.
+ expire date of the dossier.
+ url where the user can find the dossier.
+ (optional) description of the dossier.
+ whether the dossier is on legal basis.

```php
$scopes = ['schema.person.family_name', 'schema.person.given_name'];
$accesToken = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiI0NTVmNzgyZS00YjJkLTRjYmItYWU4My02ZTI5N2UyNWZlZjkiLCJuYW1lIjoiTWFybGVlbiBSb21pam4iLCJlbWFpbCI6Im1hcmxlZW5yb21pam43M0BnbWFpbC5jb20iLCJnaXZlbl9uYW1lIjoiTWFybGVlbiIsImZhbWlseV9uYW1lIjoiUm9taWpuIiwiY2xhaW1zIjp7InNjaGVtYS5wZXJzb24uZW1haWwiOlt7ImVtYWlsIjoibWFybGVlbnJvbWlqbjczQGdtYWlsLmNvbSJ9XSwic2NoZW1hLnBlcnNvbi5naXZlbl9uYW1lIjpbeyJnaXZlbl9uYW1lIjoiTWFybGVlbiJ9XSwic2NoZW1hLnBlcnNvbi5mYW1pbHlfbmFtZSI6W3siZmFtaWx5X25hbWUiOiJSb21pam4ifV19LCJpc3MiOiI0NTVmNzgyZS00YjJkLTRjYmItYWU4My02ZTI5N2UyNWZlZjkiLCJhdWQiOiJodHRwczpcL1wvZGV2LmNoZWNraW5nLm51XC91c2Vyc1wvYXV0aFwvaWR2YXVsdCIsImV4cCI6IjM2MDAiLCJqdGkiOiIxMDMzYzFmMy1hNzhkLTQ0Y2ItYWQxMy0zZjg4OGUwNzA0MTEiLCJhbGciOiJIUzI1NiIsImlhdCI6MTYwNzUxOTczMn0.Psq2XnCrgIgNQ-R4PHvctmCgC-VUhjBBNUFasHB0lAE";

$user = $idVault->createDossier($scopes, $accesToken, 'employee file', 'document employee', '05-12-2021 12:00', 'https://test.com/dossiers', 'this is your employee file', false);
```

The function returns the response from id-Vault as an array.

The id-vault user will then receive a notification that a dossier got added to his account and is able to view the dossier in the dashboard.

## Get scopes ##

With this function we are able to request additional scopes from a user we currently have a valid authorization with.

We do this by providing the function with:
+ array of scopes we want to request from the user.
+ access_token provided by id-vault.


```php
$scopes = ['schema.person.family_name', 'schema.person.given_name'];
$accesToken = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiI0NTVmNzgyZS00YjJkLTRjYmItYWU4My02ZTI5N2UyNWZlZjkiLCJuYW1lIjoiTWFybGVlbiBSb21pam4iLCJlbWFpbCI6Im1hcmxlZW5yb21pam43M0BnbWFpbC5jb20iLCJnaXZlbl9uYW1lIjoiTWFybGVlbiIsImZhbWlseV9uYW1lIjoiUm9taWpuIiwiY2xhaW1zIjp7InNjaGVtYS5wZXJzb24uZW1haWwiOlt7ImVtYWlsIjoibWFybGVlbnJvbWlqbjczQGdtYWlsLmNvbSJ9XSwic2NoZW1hLnBlcnNvbi5naXZlbl9uYW1lIjpbeyJnaXZlbl9uYW1lIjoiTWFybGVlbiJ9XSwic2NoZW1hLnBlcnNvbi5mYW1pbHlfbmFtZSI6W3siZmFtaWx5X25hbWUiOiJSb21pam4ifV19LCJpc3MiOiI0NTVmNzgyZS00YjJkLTRjYmItYWU4My02ZTI5N2UyNWZlZjkiLCJhdWQiOiJodHRwczpcL1wvZGV2LmNoZWNraW5nLm51XC91c2Vyc1wvYXV0aFwvaWR2YXVsdCIsImV4cCI6IjM2MDAiLCJqdGkiOiIxMDMzYzFmMy1hNzhkLTQ0Y2ItYWQxMy0zZjg4OGUwNzA0MTEiLCJhbGciOiJIUzI1NiIsImlhdCI6MTYwNzUxOTczMn0.Psq2XnCrgIgNQ-R4PHvctmCgC-VUhjBBNUFasHB0lAE";

$user = $idVault->getScopes($scopes, $accesToken);
```

The function returns the response from id-Vault as an array.

The id-vault user will then receive a notification that your application is requesting additional scopes.
The user will then either approve the new scopes or decline them.


## API documentation ##
If you wish to learn more about our API, please visit the <a href="https://id-vault.com/docs/" target="_blank">id-Vault documentation</a>. API Documentation is available in English.

## License ##
Copyright (c) 2018 Copyright. All Rights Reserved. Made with love by <strong>Conduction</strong>.

## Support ##
Contact: <a href="https://conduction.nl" target="_blank">www.conduction.nl </a> — info@conduction.nl
