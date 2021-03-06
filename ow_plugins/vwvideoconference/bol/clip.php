<?php

/**
 * This software is intended for use with Oxwall Free Community Software http://www.oxwall.org/ and is
 * licensed under The BSD license.

 * ---
 * Copyright (c) 2011, Oxwall Foundation
 * All rights reserved.

 * Redistribution and use in source and binary forms, with or without modification, are permitted provided that the
 * following conditions are met:
 *
 *  - Redistributions of source code must retain the above copyright notice, this list of conditions and
 *  the following disclaimer.
 *
 *  - Redistributions in binary form must reproduce the above copyright notice, this list of conditions and
 *  the following disclaimer in the documentation and/or other materials provided with the distribution.
 *
 *  - Neither the name of the Oxwall Foundation nor the names of its contributors may be used to endorse or promote products
 *  derived from this software without specific prior written permission.

 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES,
 * INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO,
 * PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED
 * AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */

/**
 * Data Transfer Object for `vwvc_clip` table.  
 * 
 * @author Egor Bulgakov <egor.bulgakov@gmail.com>
 * @package ow.plugin.vwvc.bol
 * @since 1.0
 * 
 */
class VWVC_BOL_Clip extends OW_Entity
{
    /**
     * Clip owner
     *
     * @var int
     */
    public $userId;
    /**
     * Clip title
     *
     * @var string
     */
    public $title;
    /**
     * Clip description
     *
     * @var string
     */
    public $description;
    /**
     * Date and time clip was modified
     *
     * @var int
     */
    public $modifDatetime;
    /**
     * welcome
     *
     * @var string
     */
    public $welcome;
    /**
     * Camera width
     *
     * @var int
     */
    public $camWidth;
    /**
     * Camera Height
     *
     * @var int
     */
    public $camHeight;
    /**
     * Camera FPS
     *
     * @var int
     */
    public $camFPS;
    /**
     * Microphone rate
     *
     * @var int
     */
    public $micRate;
    /**
     * Camera Bandwidth
     *
     * @var int
     */
    public $camBandwidth;
    /**
     * Background url
     *
     * @var string
     */
    public $background_url;
    /**
     * Layout code
     *
     * @var string
     */
    public $layoutCode;
    /**
     * Filter regex
     *
     * @var string
     */
    public $filterRegex;
    /**
     * Filter replace
     *
     * @var string
     */
    public $filterReplace;
    /**
     * Permission
     * 'permission' consists of tutorial:file_upload:file_delete:panelFiles:panelRooms:panelUsers:advancedCamSettings:
     * showCamSettings:configureSource:disableVideo:disableSound:disableBandwidthDetection:limitByBandwidth:
     * autoViewCams:fillWindow:writeText:regularWatch:newWatch:privateTextchat:verboseLevel (21)
     *
     * @var string
     */
    public $permission;
    /**
     * Date and time clip was added
     *
     * @var int
     */
    public $addDatetime;
    /**
     * Clip approval status ('approved' | 'pending')
     *
     * @var string
     */
    public $status;
    /**
     * @var string
     */
    public $user_list;
    /**
     * @var string
     */
    public $moderator_list;
    /**
     * @var string
     */
    public $privacy;
    /**
     * @var string
     * yes or no
     */
    public $online;
    /**
     * @var int
     */
    public $onlineCount;
    /**
     * @var string
     */
    public $onlineUser;
    /**
     * @var string
     */
    public $onlineUsers;
    /**
     * Returns user id
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }
}
