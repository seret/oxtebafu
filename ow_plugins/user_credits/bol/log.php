<?php

/**
 * Copyright (c) 2009, Skalfa LLC
 * All rights reserved.

 * ATTENTION: This commercial software is intended for use with Oxwall Free Community Software http://www.oxwall.org/
 * and is licensed under Oxwall Store Commercial License.
 * Full text of this license can be found at http://www.oxwall.org/store/oscl
 */

/**
 * Data Transfer Object for `usercredits_log` table.
 *
 * @author Egor Bulgakov <egor.bulgakov@gmail.com>
 * @package ow.plugin.user_credits.bol
 * @since 1.0
 */
class USERCREDITS_BOL_Log extends OW_Entity
{
    /**
     * @var int
     */
    public $userId;
    /**
     * @var int
     */
    public $actionId;
    /**
     * @var float
     */
    public $amount;
    /**
     * @var int
     */
    public $logTimestamp;
}