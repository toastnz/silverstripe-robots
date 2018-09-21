<?php

namespace Innoweb\Robots\Controller;

use SilverStripe\Control\Controller;
use SilverStripe\SiteConfig\SiteConfig;

class RobotsController extends Controller
{
    
    private static $allowed_actions = array(
        'robots.txt' => 'index'
    );
    
    public function index()
    {
        
        $config = SiteConfig::current_site_config();
        
        if ($config->RobotsAllowCrawl == 1) {
            
            /*
             * Trim the RobotsTxt field because it may be an empty string.
             * and since SilverStripe doesn't ship with a default robots.txt
             * file, we'll want to return a 404 if there isn't any text for
             * the site's robots.txt file.
             */
            $contents = trim($config->RobotsContent);
            
        } else {
            
            $contents = "User-agent: * \n";
            $contents .= "Disallow: / \n";
            
        }
        
        if(empty($contents)) {
            return $this->httpError(404);
        }
        
        $this->getResponse()->addHeader('Content-Type', 'text/plain; charset="utf-8"');
        return $contents;
        
    }
    
}
