# Silverstripe Robots.txt

[![Version](http://img.shields.io/packagist/v/innoweb/silverstripe-robots.svg?style=flat-square)](https://packagist.org/packages/innoweb/silverstripe-robots)
[![License](http://img.shields.io/packagist/l/innoweb/silverstripe-robots.svg?style=flat-square)](license.md)

## Overview

Adds a Robots.txt file that is configurable from /admin/settings/ and injects robots meta tag into all pages.

This module supports single site as well as [multisites](https://github.com/symbiote/silverstripe-multisites) and [configured-multisites](https://github.com/fromholdio/silverstripe-configured-multisites) setups.

## Requirements

* Silverstripe CMS 5.x

Note: this version is compatible with SilverStripe 5. For SilverStripe 4, please see the [4 release line](https://github.com/xini/silverstripe-robots/tree/4).

## Installation

Install the module using composer:
```
composer require innoweb/silverstripe-robots dev-master
```
Then run dev/build.

## Configuration

### Robots.txt

On the SiteConfig (or Site if Multisites is installed) there is a setting in the CMS that lets you set the robots mode. The three options are:
* Allow all
* Disallow all
* Custom content

The output of all three states is managed through templates and can be overwritten for an app or theme.

You can force the state using the following `.env` variable (e.g. for dev or test environment):

```dotenv
FORCE_ROBOTS_MODE="allow|disallow|custom"
```

#### Allow all

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

#### Disallow all

When switched to 'disallow all' the module uses the template `Innoweb/Robots/RobotsController_disallow.ss` with the following default content:

```
User-agent: *
Disallow: /
```

This disallows all robots from accessing any page on the site.

#### Custom content

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

### Robots meta tag

The module injects a robots meta tag into every page. The injection of the meta tag can be disabled using the following config, e.g. if the robots meta tag is managed manually in the template:

```yaml
Page:
  robots_enable_metatag: false
```

By default, all pages are set to `index, follow` with the following exceptions:

* The Robots.txt setting on the site if set to 'Disallow all'
* The environment is set to `test` or `dev`
* The current page is displayed by the Security controller 
* The Priority setting for the page is `-1` (see [Google Sitemaps module](https://github.com/wilr/silverstripe-googlesitemaps))

Additionally, for each page type a config value can be set to control the meta tag. By default, the following values are set:

```yaml
Page:
  robots_noindex: false
  robots_nofollow: false

SilverStripe\CMS\Model\VirtualPage:
  robots_noindex: true
  robots_nofollow: true

SilverStripe\ErrorPage\ErrorPage:
  robots_noindex: true
  robots_nofollow: true
```

This can be customised for any custom page types as needed.

## License

BSD 3-Clause License, see [License](license.md)
