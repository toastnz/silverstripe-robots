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

You can disallow robots from accessing your site using the config.

You can add the following code to your .env file to disallow robots for an environment. 
```
ROBOTS_DISALLOW="true"
``` 

You can also disallow access by using the yml config:
```
RobotsController:
  disallow_robots: true
```

The default value for this configuration is `false`, allowing access to the site (except if the romots.txt field content is empty, see below).

Both these methods will add a default robots.txt content in place, that will disallow robots from accessing your site. 

## robots.txt content

To add a romots.txt to you site, paste in your robots.txt configuration into the textarea inside the robots tab in admin/settings/.

### Example content

Here a good standard robots.txt configuration:

```
Sitemap: https://www.example.com/sitemap.xml
User-agent: *
Disallow: /dev/
Disallow: /admin/
Disallow: /Security/
```

If you leave the field empty, a default content will be used that blocks robots from accessing the site.

## License

BSD 3-Clause License, see [License](license.md)
