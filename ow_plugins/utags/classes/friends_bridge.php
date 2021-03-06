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
 * @package utags.classes
 */
class UTAGS_CLASS_FriendsBridge extends UTAGS_CLASS_AbstractUserBridge
{

    /**
     * Class instance
     *
     * @var UTAGS_CLASS_FriendsBridge
     */
    private static $classInstance;

    /**
     * Returns class instance
     *
     * @return UTAGS_CLASS_FriendsBridge
     */
    public static function getInstance()
    {
        if ( !isset(self::$classInstance) )
        {
            self::$classInstance = new self();
        }

        return self::$classInstance;
    }

    public function __construct()
    {
        
    }
    
    public function isActive()
    {
        return OW::getPluginManager()->isPluginActive("friends");
    }
    
    public function findUsers( $kw, $userId, $limit = null )
    {
        if ( !OW::getPluginManager()->isPluginActive('friends') ) {
            return array();
        }

        $friendsTable = FRIENDS_BOL_FriendshipDao::getInstance()->getTableName();

        $questionName = OW::getConfig()->getValue('base', 'display_name_question');
        $questionDataTable = BOL_QuestionDataDao::getInstance()->getTableName();

        $limitStr = $limit === null ? '' : 'LIMIT 0, ' . intval($limit);

        $query = "SELECT `fr`.`userId` FROM `" . $friendsTable . "` AS `fr`
            INNER JOIN " . $questionDataTable . " qd ON fr.userId = qd.userId
            LEFT JOIN `" . BOL_UserSuspendDao::getInstance()->getTableName() . "` AS `us` ON ( `fr`.`friendId` = `us`.`userId` )
            WHERE `fr`.`status` = :status AND `us`.`userId` IS NULL AND `fr`.`friendId` = :userId
                AND qd.questionName=:name AND qd.textValue LIKE :kw
            UNION
            SELECT `fr`.`friendId` AS `userId` FROM `" . $friendsTable . "` AS `fr`
            INNER JOIN " . $questionDataTable . " qd ON fr.friendId = qd.userId
            LEFT JOIN `" . BOL_UserSuspendDao::getInstance()->getTableName() . "` AS `us` ON ( `fr`.`friendId` = `us`.`userId` )
            WHERE `fr`.`status` = :status AND `us`.`userId` IS NULL AND `fr`.`userId` = :userId
                AND qd.questionName=:name AND qd.textValue LIKE :kw
            $limitStr
            ";

        return OW::getDbo()->queryForColumnList($query,
            array(
                'userId' => $userId,
                'status' => FRIENDS_BOL_FriendshipDao::VAL_STATUS_ACTIVE,
                'kw' => '%' . $kw . '%',
                'name' => $questionName
            )
        );
    }
    
    public function onSearch( BASE_CLASS_EventCollector $event )
    {
        $params = $event->getParams();
        
        $kw = $params["kw"];
        $userId = $params["userId"];
        
        if ( $kw === null )
        {
            $userIds = OW::getEventManager()->call('plugin.friends.get_friend_list', array(
                'userId' => $userId,
                'first' => 0,
                'count' => 200
            ));

            $userIds = empty($userIds) ? array() : $userIds;
        }
        else
        {
            $userIds = $this->findUsers($kw, $userId);
        }
        
        $data = $this->buildData($userIds, OW::getLanguage()->text('utags', 'selector_group_friends'), array($userId));
        
        foreach ( $data as $item )
        {
            $event->add($item);
        }
    }
    
    public function onInputInit( OW_Event $event )
    {
        $params = $event->getParams();
        
        $userId = $params["userId"];
        /* @var $input UTAGS_CMP_Tags */
        $input = $params["input"];
        
        $group = OW::getLanguage()->text('utags', 'selector_group_friends');
        //OW::getLanguage()->addKeyForJs('utags', 'selector_no_friends');
        
        $input->setupGroup($group, array(
            'priority' => 10,
            'alwaysVisible' => true,
            'noMatchMessage' => false
        ));
    }

    public function genericInit()
    {
        
    }


    public function init()
    {
        if ( !$this->isActive() ) return;
        
        parent::init();
        
        $this->genericInit();
        
        OW::getEventManager()->bind(UTAGS_BOL_Service::EVENT_ON_SEARCH, array($this, "onSearch"));
        OW::getEventManager()->bind(UTAGS_BOL_Service::EVENT_ON_INPUT_INIT, array($this, "onInputInit"));
    }
}