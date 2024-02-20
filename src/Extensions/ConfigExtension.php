<?php

namespace Innoweb\Robots\Extensions;

use Innoweb\Robots\Controllers\RobotsController;
use SilverStripe\Core\Environment;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\HiddenField;
use SilverStripe\Forms\OptionsetField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\ORM\DataExtension;
use SilverStripe\SiteConfig\SiteConfig;
use UncleCheese\DisplayLogic\Forms\Wrapper;

class ConfigExtension extends DataExtension
{
    private static $force_robots_mode;
    private static $enable_custom_robots = true;
    private static $robots_tab_path = 'Root.Robots';

    private static $robots_mode_labels = [
        RobotsController::MODE_ALLOW => 'Allow all',
        RobotsController::MODE_DISALLOW => 'Disallow all',
        RobotsController::MODE_CUSTOM => 'Set custom robots.txt content'
    ];

    private static $db = [
        'RobotsMode' => 'Varchar(20)',
        'RobotsContent' => 'Text'
    ];

    private static $casting = [
        'RenderedContentAllow' => 'Text',
        'RenderedContentDisallow' => 'Text',
    ];

    public function updateSiteCMSFields(FieldList $fields)
    {
        $fields = $this->getOwner()->applyRobotsCMSFields($fields);
    }

    public function updateCMSFields(FieldList $fields)
    {
        $fields = $this->getOwner()->applyRobotsCMSFields($fields);
    }

    public function applyRobotsCMSFields(FieldList $fields)
    {
        $fields->removeByName([
            'RobotsMode',
            'RobotsContent',
            'SiteAdvancedHeader',
            'RobotsTxt',
        ]);

        $tabPath = $this->getOwner()->getRobotsTabPath();
        if (!$tabPath) {
            return;
        }

        $isCustomAllowed = $this->getOwner()->getIsCustomRobotsModeAllowed();
        $customField = TextareaField::create('RobotsContent', 'Custom Content');

        $forcedMode = $this->getOwner()->getForcedRobotsMode();
        if ($forcedMode) {
            if ($isCustomAllowed && $forcedMode === RobotsController::MODE_CUSTOM) {
                $fields->addFieldToTab($tabPath, $customField);
            }
            return;
        }

        $options = $this->getOwner()->getRobotsModeOptions();
        if (!$options) {
            return;
        }

        $optionsCount = count($options);
        if ($optionsCount === 1) {
            if ($isCustomAllowed) {
                $fields->addFieldToTab($tabPath, $customField);
            } else {
                $tabPath = 'Root.Main';
            }

            $hiddenModeField = HiddenField::create('RobotsMode', null);
            $fields->addFieldToTab($tabPath, $hiddenModeField);

            reset($options);
            $this->getOwner()->RobotsMode = key($options);
        } else {
            $modeField = OptionsetField::create('RobotsMode', 'Robots.txt', $options);
            $fields->addFieldToTab($tabPath, $modeField);

            if ($isCustomAllowed) {
                $fields->addFieldToTab($tabPath, $customField);
                $customField->displayIf('RobotsMode')->isEqualTo(RobotsController::MODE_CUSTOM);
            }
        }

        if (isset($options[RobotsController::MODE_ALLOW])) {
            $allowedOutputField = Wrapper::create(
                TextareaField::create(
                    'AllowedContent',
                    'Robots.txt output',
                    $this->owner->getRenderedContentAllow()
                )->setReadonly(true)
            );
            $fields->addFieldToTab($tabPath, $allowedOutputField);
            $allowedOutputField->displayIf('RobotsMode')->isEqualTo(RobotsController::MODE_ALLOW);
        }

        if (isset($options[RobotsController::MODE_DISALLOW])) {
            $disallowedOutputField = Wrapper::create(
                TextareaField::create(
                    'ContentDisallow',
                    'Robots.txt output',
                    $this->owner->getRenderedContentDisallow()
                )->setReadonly(true)
            );
            $fields->addFieldToTab($tabPath, $disallowedOutputField);
            $disallowedOutputField->displayIf('RobotsMode')->isEqualTo(RobotsController::MODE_DISALLOW);
        }

        return $fields;
    }

    public function populateDefaults()
    {
        $this->getOwner()->RobotsMode = $this->getOwner()->getDefaultRobotsMode();
    }

    public function requireDefaultRecords()
    {
        // get correct config class
        if (class_exists('Symbiote\Multisites\Multisites')) {
			$configs = \Symbiote\Multisites\Model\Site::get();
		} elseif (class_exists('Fromholdio\ConfiguredMultisites\Multisites')) {
			$configs = \Fromholdio\ConfiguredMultisites\Model\Site::get();
        } else {
            $configs = SiteConfig::get();
        }
        // update configs if required
        if ($configs && $configs->exists()) {
            foreach ($configs as $config) {
                if (!$config->RobotsMode) {
                    if ($config->RobotsContent) {
                        $config->RobotsMode = RobotsController::MODE_CUSTOM;
                    } else {
                        $config->RobotsMode = $config->getDefaultRobotsMode();
                    }
                    $config->write();
                }
            }
        }
    }

    public function getDefaultRobotsMode()
    {
        $mode = RobotsController::MODE_DISALLOW;
        $this->getOwner()->invokeWithExtensions('updateDefaultRobotsMode');
        return $mode;
    }

    public function getIsCustomRobotsModeAllowed()
    {
        $isAllowed = false;
        $options = $this->getOwner()->getRobotsModeOptions();
        if ($options && isset($options[RobotsController::MODE_CUSTOM])) {
            $isAllowed = true;
        }
        $this->getOwner()->invokeWithExtensions('updateIsCustomRobotsModeAllowed', $isAllowed);
        return $isAllowed;
    }

    public function getForcedRobotsMode()
    {
        $mode = null;
        $options = $this->getOwner()->getRobotsModeOptions();
        $envMode = Environment::getEnv('FORCE_ROBOTS_MODE');
        if ($envMode && isset($options[$envMode])) {
            $mode = $envMode;
        }
        if (!$mode) {
            $configMode = $this->getOwner()->config()->get('force_robots_mode');
            if ($configMode && isset($options[$configMode])) {
                $mode = $configMode;
            }
        }
        $this->getOwner()->invokeWithExtensions('updateForcedRobotsMode', $mode);
        return $mode;
    }

    public function getRobotsModeOptions()
    {
        $options = $this->getOwner()->config()->get('robots_mode_labels');
        foreach ($options as $key => $value) {
            if (!$value) {
                unset($options[$key]);
            }
        }
        $this->getOwner()->invokeWithExtensions('updateRobotsModeOptions', $options);
        if (!$options || !is_array($options) || count($options) < 1) {
            $options = null;
        }
        return $options;
    }

    public function getRobotsTabPath()
    {
        $path = $this->getOwner()->config()->get('robots_tab_path');
        $this->getOwner()->invokeWithExtensions('updateRobotsTabPath', $path);
        return $path;
    }

    public function getRenderedContentAllow()
    {
        $controller = RobotsController::create();
        return $controller->allow();
    }

    public function getRenderedContentDisallow()
    {
        $controller = RobotsController::create();
        return $controller->disallow();
    }
}
