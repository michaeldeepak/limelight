Limelight smartpurge library for PHP
=================================================

[![Apache license](https://img.shields.io/:license-apache-blue.svg?style=flat-square)](https://github.com/michaeldeepak/limelight/blob/master/LICENSE)

**Limelight Smartpurge** is a  PHP client using curl to purge limelight cdn (https://www.limelight.com/).

- Simple php interface to purge contents against limelight
- Requires PHP >= 5.5 compiled with cURL extension and cURL 7.16.2+ compiled with a TLS backend (e.g. NSS or OpenSSL).

## Installing Limelight Smartpurge


```bash
# cone from githup
git clone https://github.com/michaeldeepak/limelight.git
```

Change credentials php by adding your account related credentials.php

Change the email constants in constants.php

## Quick Examples

### Make purge request

```php
<?php

$pattern[] = array(
    'pattern'   => "http://*.example.com/images/*",
    'evict'     => false,
    'exact'     => false,
    'incqs'     => false
);

```


See [example](https://github.com/michaeldeepak/limelight/blob/master/refresh.php) for a running example of how to use this library.



### Limelight credentials [credentials.php]

| Credential key            | Type    | Required  | Description   |
| -------------             | ------  | --------  | ------------  |
|  LIMELIGHT_SHARED_KEY     | String  | Yes       | Limelight control account username |
|  LIMELIGHT_USERNAME       | String  | Yes       | Limelight control account shared key |
|  LIMELIGHT_SHORT_NAME     | String  | Yes       | Limelight cdn account name |



### Limelight constatns [constants.php]

| Config key                        | Type    | Required  | Description   |
| -------------                     | ------- | --------  | -----------   |
| LIMELIGHT_API_URL                 | String  | Yes       | Limelight api [url](https://purge.llnw.com/purge/v1)|
| EMAIL_SUBJECT                     | String  | No        | Subject of sent mail |
| EMAIL_TO                          | String  | Yes       | Email recipient address. A comma is used to separate multiple recipients |



Documentation
-----------
- [Generating documentation](https://github.com/michaeldeepak/limelight/blob/master/README.md)

License
-------

Apache License

See [LICENSE](https://github.com/michaeldeepak/limelight/blob/master/LICENSE) for details.
