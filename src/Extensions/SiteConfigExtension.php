<?php

namespace Innoweb\Robots\Extensions;

use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\FieldGroup;

class SiteConfigExtension extends DataExtension
{
    
    private static $db = [
        'RobotsContent' => 'Text',
    ];
    
    public function updateCMSFields(FieldList $fields)
    {
        
        $fields->addFieldToTab(
            'Root.Robots', 
            TextareaField::create('RobotsContent','Content of robots.txt')
                ->setDescription('Will be redered in the robots.txt of the site. If no content is entered, '
                    .'robots will be disallowed from accessing the site')
        );
        
    }
    
}
