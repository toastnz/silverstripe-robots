# Changelog

All notable changes to this project will be documented in this file.

This project adheres to [Semantic Versioning](http://semver.org/).

## [4.0.1]

* exclude Multisites Sites from DisallowedPages

## [4.0.0]

* rename .env `ROBOTS_MODE` to `FORCE_ROBOTS_MODE` and `ConfigExtension::robots_mode` to `ConfigExtension::force_robots_mode` for clarification
* enable robots tag output in dev and test environments
* add `DisallowedPages` to allowed robots.txt output, based on Google Sitemap settings

## [3.1.1]

* fix default values when upgrading from version 1.x

## [3.1.0]

* fix use statements
* add extension hook for robots string for current controller

## [3.0.0]

* add robots meta tag for all pages based on env type, wilr/silverstripe-googlesitemaps module and page specific config values

## [2.1.0]

* fix content for disallowed content
* add output preview in CMS for allow and disallow

## [2.0.1]

* remove default robots.txt content field from multisites site CMSFields

## [2.0.0]

* module rewrite

## [1.1.0]

* switch disallow config from CMS to .env/yml

## [1.0.0]

* initial release
