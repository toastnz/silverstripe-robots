<?php

namespace Innoweb\Robots\Extensions;

use Innoweb\Robots\Controllers\RobotsController;
use SilverStripe\Control\Controller;
use SilverStripe\Control\Director;
use SilverStripe\Core\Extension;
use SilverStripe\Security\Security;

class PageExtension extends Extension
{
    public function MetaTags(&$tagsString)
    {
        if ($this->getOwner()->config()->robots_enable_metatag == true) {
            $robotsString = $this->getOwner()->getRobotsTagString();
            $robotsMeta = "<meta name=\"robots\" content=\"$robotsString\" />";
            if (preg_match('/<title>.*<\/title>/', $tagsString) == 1) {
                $tagsString = preg_replace('/(<title>.*<\/title>)/', "$1\n$robotsMeta", $tagsString);
            } else {
                $tagsString = $robotsMeta . "\n" . $tagsString;
            }
        }
    }

    public function getRobotsTagString()
    {
        $follow = "follow";
        $index = "index";

        if (RobotsController::create()->getActiveMode() == RobotsController::MODE_DISALLOW) {
            $follow = "nofollow";
            $index = "noindex";
        } else if (!Director::isLive()) {
            $follow = "nofollow";
            $index = "noindex";
        } else if (is_a(Controller::curr(), Security::class)) {
            $follow = "nofollow";
            $index = "noindex";
        } else if (
            $this->getOwner()->hasExtension('Wilr\GoogleSitemaps\Extensions\GoogleSitemapSiteTreeExtension')
            && ($priority = $this->getOwner()->Priority)
            && $priority == -1
        ) {
            $index = "noindex";
        }

        // get config settings
        if ($this->getOwner()->config()->robots_noindex == true) {
            $index = "noindex";
        }
        if ($this->getOwner()->config()->robots_nofollow == true) {
            $follow = "nofollow";
        }

        $robotsString = "$index, $follow";

        $this->getOwner()->invokeWithExtensions('updateRobotsTagString', $robotsString);

        Controller::curr()->invokeWithExtensions('updateRobotsTagString', $robotsString);

        return $robotsString;
    }
}