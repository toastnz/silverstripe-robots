# SilverStripe Robots.txt

[![Version](http://img.shields.io/packagist/v/innoweb/silverstripe-robots.svg?style=flat-square)](https://packagist.org/packages/innoweb/silverstripe-robots)
[![License](http://img.shields.io/packagist/l/innoweb/silverstripe-robots.svg?style=flat-square)](license.md)

## Overview

Adds a Robots.txt file that is configurable from /admin/settings/.

This module has been inspired by MichaelJJames' [silverstripe-robots](https://github.com/MichaelJJames/silverstripe-robots) and Symbiote's [Multisites](https://github.com/symbiote/silverstripe-multisites).

## Requirements

* SilverStripe CMS 4.x

## Installation

Install the module using composer:
```
composer require innoweb/silverstripe-robots dev-master
```
Then run dev/build.

## Configuration

The module is configurable from within /admin/settings. 

To add a romots.txt to you site, paste in your robots.txt configuration into the textarea inside the robots tab in admin/settings/.

### Example config

Here a good standard robots.txt configuration:

```
Sitemap: https://www.example.com/sitemap.xml
User-agent: *
Disallow: /dev/
Disallow: /admin/
Disallow: /Security/
```

## License

BSD 3-Clause License, see [License](license.md)
