<?php

namespace Innoweb\Robots\Controller;

use SilverStripe\Control\Controller;
use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\Core\Environment;

class RobotsController extends Controller
{
    
    private static $disallow_robots = false;
    
    private static $allowed_actions = array(
        'robots.txt' => 'index'
    );
    
    public function index()
    {
        // get disallow config
        $disallow = $this->config()->disallow_robots;
        if (Environment::getEnv('ROBOTS_DISALLOW') == 'true') {
            $disallow = true;
        } else if (Environment::getEnv('ROBOTS_DISALLOW') == 'false') {
            $disallow = false;
        }
        
        // load robots content
        $config = SiteConfig::current_site_config();
        /*
         * Trim the RobotsTxt field because it may be an empty string.
         * and since SilverStripe doesn't ship with a default robots.txt
         * file, we'll want to return a 404 if there isn't any text for
         * the site's robots.txt file.
         */
        $contents = trim($config->RobotsContent);
        
        // add disallow code if disallowed or no content set
        if ($disallow || empty($contents)) {
            $contents = "User-agent: * \n";
            $contents .= "Disallow: / \n";
        }
        
        // return content
        $this->getResponse()->addHeader('Content-Type', 'text/plain; charset="utf-8"');
        return $contents;
        
    }
    
}
