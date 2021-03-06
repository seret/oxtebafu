<?php

/**
 * Copyright (c) 2009, Skalfa LLC
 * All rights reserved.

 * ATTENTION: This commercial software is intended for use with Oxwall Free Community Software http://www.oxwall.org/
 * and is licensed under Oxwall Store Commercial License.
 * Full text of this license can be found at http://www.oxwall.org/store/oscl
 */

/**
 * Membership plan product adapter class.
 *
 * @author Egor Bulgakov <egor.bulgakov@gmail.com>
 * @package ow.ow_plugins.membership.classes
 * @since 1.0
 */
class MEMBERSHIP_CLASS_MembershipPlanProductAdapter implements OW_BillingProductAdapter
{
    const PRODUCT_KEY = 'membership_plan';

    const RETURN_ROUTE = 'membership_subscribe';

    public function getProductKey()
    {
        return self::PRODUCT_KEY;
    }

    public function getProductOrderUrl()
    {
        return OW::getRouter()->urlForRoute(self::RETURN_ROUTE);
    }

    public function deliverSale( BOL_BillingSale $sale )
    {
        $planId = $sale->entityId;

        $membershipService = MEMBERSHIP_BOL_MembershipService::getInstance();

        $plan = $membershipService->findPlanById($planId);
        $type = $membershipService->findTypeByPlanId($planId);

        if ( $plan && $type )
        {
            $userMembership = new MEMBERSHIP_BOL_MembershipUser();
    
            $userMembership->userId = $sale->userId;
            $userMembership->typeId = $type->id;
            $userMembership->expirationStamp = time() + (int) $plan->period * 60 * 60 * 24;
            $userMembership->recurring = $sale->recurring;
    
            $membershipService->setUserMembership($userMembership);
    
            return true;
        }
        
        return false;
    }
}