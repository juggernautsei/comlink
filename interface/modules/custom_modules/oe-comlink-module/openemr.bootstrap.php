<?php

/**
 *
 * link    http://www.open-emr.org
 * author  Sherwin Gaddis <sherwingaddis@gmail.com>
 * Copyright (c) 2020. Sherwin Gaddis <sherwingaddis@gmail.com>
 *
 */

use OpenEMR\Events\Globals\GlobalsInitializedEvent;
use OpenEMR\Menu\MenuEvent;
use OpenEMR\Services\Globals\GlobalSetting;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;


function comlink_add_menu_item(MenuEvent $event)
{
    $menu = $event->getMenu();

    $menuItem = new stdClass();
    $menuItem->requirement = 0;
    $menuItem->target = 'fin';
    $menuItem->menu_id = 'fin0';
    $menuItem->label = xlt("Patient Monitoring");
    $menuItem->url = "/interface/modules/custom_modules/oe-comlink-module/comlinkUI.php";
    $menuItem->children = [];
    $menuItem->acl_req = ["patients", "docs"];
    $menuItem->global_req = [];

    foreach ($menu as $item) {
        if ($item->menu_id == 'patimg') {
            $item->children[] = $menuItem;
            break;
        }
    }

    $event->setMenu($menu);

    return $event;
}

/**
 * @var EventDispatcherInterface $eventDispatcher
 * @var array                    $module
 * @global                       $eventDispatcher @see ModulesApplication::loadCustomModule
 * @global                       $module          @see ModulesApplication::loadCustomModule
 */
$eventDispatcher->addListener(MenuEvent::MENU_UPDATE, 'comlink_add_menu_item');

function createFaxModuleGlobals(GlobalsInitializedEvent $event)
{
    $instruct = xl('Enable communication with Comlink FHIR server.');
    $user_array = array(xl('Comlink Username'), 'text', '', xl('Username for Comlink Account'));
    $pass_array = array(xl('Comlink password'), 'encrypted', '', xl('Password for Comlink Account'));

    $event->getGlobalsService()->createSection("Comlink Device Module", "Report");
    $setting = new GlobalSetting(xl('Comlink Account'), $user_array, '', $instruct);
    $event->getGlobalsService()->appendToSection("Comlink Device Module", "comlink_enable", $setting);
}

$eventDispatcher->addListener(GlobalsInitializedEvent::EVENT_HANDLE, 'createFaxModuleGlobals');

