# Silverstripe Robots.txt

[![Version](http://img.shields.io/packagist/v/innoweb/silverstripe-robots.svg?style=flat-square)](https://packagist.org/packages/innoweb/silverstripe-robots)
[![License](http://img.shields.io/packagist/l/innoweb/silverstripe-robots.svg?style=flat-square)](license.md)

## Overview

Adds a Robots.txt file that is configurable from /admin/settings/.

This module supports single site as well as [multisites](https://github.com/symbiote/silverstripe-multisites) setups.

## Requirements

* Silverstripe CMS 4.x

## Installation

Install the module using composer:
```
composer require innoweb/silverstripe-robots dev-master
```
Then run dev/build.

## Configuration

On the SiteConfig (or Site is Multisites is installed) there is a setting in the CMS that lets you set the robots mode. The three options are:
* Allow all
* Disallow all
* Custom content

The output of all three states is managed through templates and can be overwritten for an app or theme.

### Allow all

When switched to 'allow all' the module uses the template `Innoweb/Robots/RobotsController_allow.ss` with the following default content:

```
<% if $GoogleSitemapURL %>Sitemap: {$GoogleSitemapURL}<% end_if %>
User-agent: *
Disallow: /dev/
Disallow: /admin/
Disallow: /Security/
```

The module checks whether the [Google Sitemaps module](https://github.com/wilr/silverstripe-googlesitemaps) is installed and injects the sitemap URL automatically.

It allows access to all pages and disallows access to development and security URLs by default.

### Disallow all

When switched to 'disallow all' the module uses the template `Innoweb/Robots/RobotsController_disallow.ss` with the following default content:

```
UserAgent: *
Disallow: /
```

This disallows all robots from accessing any page on the site.

### Custom content

This setting reveals a text field in the CMS where custom code can be entered. 

The template contains the following code and doesn't add anything to the custom code entered:

```
$RobotsContent.RAW
```

A good standard robots.txt configuration for Silverstripe looks as follows. This is used as default when the module is switched to 'allow all':

```
Sitemap: https://www.example.com/sitemap.xml
User-agent: *
Disallow: /dev/
Disallow: /admin/
Disallow: /Security/
```

## License

BSD 3-Clause License, see [License](license.md)
