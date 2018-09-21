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
        'RobotsAllowCrawl' => 'Boolean',
        'RobotsContent' => 'Text',
    ];
    
    public function updateCMSFields(FieldList $fields)
    {
        
        $fields->addFieldsToTab(
            'Root.Robots', 
            [
                FieldGroup::create(
                    'RobotsSettings',
                    [
                        CheckboxField::create('RobotsAllowCrawl', 'Allow crawling')
                            
                    ]
                )
                    ->setTitle('Global settings')
                    ->setDescription('If this is disabled the content below will be ignored and all robots disallowed.'),
                TextareaField::create('RobotsContent','Content of robots.txt'),
            ]
        );
        
    }
    
}
