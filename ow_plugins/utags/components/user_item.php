<?php

/**
 * Copyright (c) 2013, Sergey Kambalin
 * All rights reserved.

 * ATTENTION: This commercial software is intended for use with Oxwall Free Community Software http://www.oxwall.org/
 * and is licensed under Oxwall Store Commercial License.
 * Full text of this license can be found at http://www.oxwall.org/store/oscl
 */

/**
 * @author Sergey Kambalin <greyexpert@gmail.com>
 * @package utags.components
 */
class UTAGS_CMP_UserItem  extends OW_Component
{
    public function __construct( $data )
    {
        parent::__construct();
        
        $this->assign("data", $data);
    }
}
