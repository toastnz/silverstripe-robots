<?php

namespace Innoweb\Robots\Controllers;

use SilverStripe\Control\Controller;
use SilverStripe\Control\Director;
use SilverStripe\Core\Manifest\ModuleLoader;
use SilverStripe\SiteConfig\SiteConfig;
use Symbiote\Multisites\Multisites;
use Wilr\GoogleSitemaps\GoogleSitemap;

class RobotsController extends Controller
{
    const MODE_ALLOW = 'allow';
    const MODE_DISALLOW = 'disallow';
    const MODE_CUSTOM = 'custom';

    public function index()
    {
        $mode = $this->getActiveMode();

        $this->getResponse()->addHeader(
            'Content-Type',
            'text/plain; charset="utf-8"'
        );

        if ($mode === self::MODE_ALLOW) {
            return $this->allow();
        }
        else if ($mode === self::MODE_DISALLOW) {
            return $this->disallow();
        }
        else if ($mode === self::MODE_CUSTOM) {
            return $this->custom();
        }

        return $this->httpError(404);
    }

    public function allow()
    {
        return $this->renderWith(self::class . '_allow');
    }

    public function disallow()
    {
        return $this->renderWith(self::class . '_disallow');
    }

    public function custom()
    {
        $site = $this->getRobotsSite();
        $custom = trim($site->RobotsContent);

        if (!$custom || empty($custom)) {
            return $this->disallow();
        }

        return $this->renderWith(
            [self::class . '_custom'],
            ['RobotsContent' => $custom]
        );
    }

    public function getActiveMode()
    {
        $site = $this->getRobotsSite();

        $forcedMode = $site->getForcedRobotsMode();
        if ($forcedMode) {
            return $forcedMode;
        }

        $siteMode = $site->RobotsMode;
        if ($siteMode) {
            return $siteMode;
        }

        return $site->getDefaultRobotsMode();
    }

    public function getRobotsSite()
    {
        $isMultisitesEnabled = ModuleLoader::inst()
            ->getManifest()
            ->moduleExists('symbiote/silverstripe-multisites');

        if ($isMultisitesEnabled) {
            $site = Multisites::inst()->getCurrentSite();
        }
        else {
            $site = SiteConfig::current_site_config();
        }

        $this->extend('updateRobotsSite', $site);
        return $site;
    }

    public function getGoogleSitemapURL()
    {
        $url = '/sitemap.xml';

        $isGoogleSitemapsEnabled = ModuleLoader::inst()
            ->getManifest()
            ->moduleExists('wilr/silverstripe-googlesitemaps');

        if ($isGoogleSitemapsEnabled) {
            $isGoogleSitemapsEnabled = GoogleSitemap::enabled();
        }

        if ($isGoogleSitemapsEnabled) {

            $isMultisitesEnabled = ModuleLoader::inst()
                ->getManifest()
                ->moduleExists('symbiote/silverstripe-multisites');

            if ($isMultisitesEnabled) {
                $site = Multisites::inst()->getCurrentSite();
                $url = $site->getURL() . $url;
            }
            else {
                $url = Director::absoluteURL($url);
            }
        }
        else {
            $url = null;
        }

        $this->extend('updateGoogleSitemapURL', $url);
        return $url;
    }
}
