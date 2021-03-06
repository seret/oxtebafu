<?php

/**
 * Copyright (c) 2012, Sergey Kambalin
 * All rights reserved.

 * ATTENTION: This commercial software is intended for use with Oxwall Free Community Software http://www.oxwall.org/
 * and is licensed under Oxwall Store Commercial License.
 * Full text of this license can be found at http://www.oxwall.org/store/oscl
 */

require_once 'plugin.php';
require_once dirname(__FILE__) . DS . 'classes' . DS . 'credits.php';

$plugin = GHEADER_Plugin::getInstance();

$credits = new GHEADER_CLASS_Credits();
$credits->triggerCreditActionsAdd();

if ( $plugin->isAvaliable() )
{
    $plugin->fullActivate();
}
else
{
   $plugin->shortActivate();
}