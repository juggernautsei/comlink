<?php

/*
 *
 * @package      OpenEMR
 * @link               https://www.open-emr.org
 *
 * @author    Sherwin Gaddis <sherwingaddis@gmail.com>
 * @copyright Copyright (c) 2021 Sherwin Gaddis <sherwingaddis@gmail.com>
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 *
 */

namespace OpenEMR\Modules\Comlink;


use OpenEMR\Menu\MenuEvent;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

function oe_module_comlink_add_menu_item(MenuEvent $event)
{
    $menu = $event->getMenu();

    $menuItem = new stdClass();
    $menuItem->requirement = 0;
    $menuItem->target = 'mod';
    $menuItem->menu_id = 'mod0';
    $menuItem->label = xlt('Complink Module');
    $menuItem->url = "/interface/modules/custom_modules/oe-comlink-module/messageUI.php";
    $menuItem->children = [];
    $menuItem->acl_req = ["patients"];
    $menuItem->global_req = [''];

    foreach ($menu as $item) {
        if ($item->menu_id == 'modimg') {
            $item->children[] = $menuItem;
            break;
        }
    }

    $event->setMenu();
    return $event;
}

/**
 * @var EventDispatcherInterface $eventDispatcher
 * @var array                    $module
 * @global                       $eventDispatcher @see ModulesApplication::loadCustomModule
 * @global                       $module          @see ModulesApplication::loadCustomModule
 */
$eventDispatcher->addListener(MenuEvent::MENU_UPDATE, 'oe_module_comlink_add_menu_item');


/**
 * @global EventDispatcher $eventDispatcher Injected by the OpenEMR module loader
 */
$bootstrap = new Bootstrap($eventDispatcher);
$bootstrap->subscribeToEvents();
