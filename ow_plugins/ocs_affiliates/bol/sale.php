<?php

/**
 * Copyright (c) 2013, Oxwall CandyStore
 * All rights reserved.

 * ATTENTION: This commercial software is intended for use with Oxwall Free Community Software http://www.oxwall.org/
 * and is licensed under Oxwall Store Commercial License.
 * Full text of this license can be found at http://www.oxwall.org/store/oscl
 */

/**
 * Data Transfer Object for `ocsaffiliates_sale` table.
 *
 * @author Oxwall CandyStore <plugins@oxcandystore.com>
 * @package ow.ow_plugins.ocs_affiliates.bol
 * @since 1.5.3
 */
class OCSAFFILIATES_BOL_Sale extends OW_Entity
{
    /**
     * @var int
     */
    public $affiliateId;
    /**
     * @var int
     */
    public $saleId;
    /**
     * @var float
     */
    public $saleAmount;
    /**
     * @var float
     */
    public $bonusAmount;
    /**
     * @var int
     */
    public $saleDate;
}