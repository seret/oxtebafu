<?php

/**
 * This software is intended for use with Oxwall Free Community Software http://www.oxwall.org/ and is a proprietary licensed product. 
 * For more information see License.txt in the plugin folder.

 * ---
 * Copyright (c) 2012, Purusothaman Ramanujam
 * All rights reserved.

 * Redistribution and use in source and binary forms, with or without modification, are not permitted provided.

 * This plugin should be bought from the developer by paying money to PayPal account (purushoth.r@gmail.com).

 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES,
 * INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO,
 * PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED
 * AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */
class CREDITS_MCTRL_Action extends OW_MobileActionController {

    public function __construct() {
        parent::__construct();

        if (OW::getRequest()->isAjax()) {
            return;
        }
    }

    public function adminlogs() {
        if (!OW::getUser()->isAdmin() && !OW::getUser()->isAuthorized('credits')) {
            throw new AuthenticateException();
        }

        $language = OW::getLanguage();
        $config = OW::getConfig();

        $page = isset($_GET['page']) && (int) $_GET['page'] ? (int) $_GET['page'] : 1;

        $itemsPerPage = (int) $config->getValue('credits', 'logsPerPage');

        $totalLogsCount = CREDITS_BOL_Service::getInstance()->getCreditHistoryCountForAllUsers();

        $logs = CREDITS_BOL_Service::getInstance()->getCreditHistoryForAllUsers($page, $itemsPerPage);

        $pages = (int) ceil($totalLogsCount / $itemsPerPage);
        $paging = new BASE_CMP_Paging($page, $pages, 10);

        $this->assign('paging', $paging->render());
        $this->assign('logs', $logs);

        OW::getDocument()->addStyleSheet(OW::getPluginManager()->getPlugin('credits')->getStaticCssUrl() . 'style.css');
        OW::getDocument()->addScript(OW::getPluginManager()->getPlugin('credits')->getStaticJsUrl() . 'jquery.tablesorter.min.js');

        $this->setPageHeading($language->text('credits', 'admin_credit_logs_label'));
        $this->setPageTitle($language->text('credits', 'admin_credit_logs_label'));
        $this->setPageHeadingIconClass('ow_ic_gear_wheel');
    }

    public function logs(array $params) {
        if (!OW::getUser()->isAuthenticated()) {
            throw new AuthenticateException();
        }

        $language = OW::getLanguage();
        $config = OW::getConfig();

        $userId = OW::getUser()->getId();

        $type = isset($params['type']) ? $params['type'] : 'all';
        $page = isset($_GET['page']) && (int) $_GET['page'] ? (int) $_GET['page'] : 1;

        $itemsPerPage = (int) $config->getValue('credits', 'logsPerPage');

        $totalLogsCount = CREDITS_BOL_Service::getInstance()->getCreditHistoryCount($userId, $type);

        $logs = CREDITS_BOL_Service::getInstance()->getCreditHistory($userId, $type, $page, $itemsPerPage);

        $pages = (int) ceil($totalLogsCount / $itemsPerPage);
        $paging = new BASE_CMP_Paging($page, $pages, 10);

        $this->assign('paging', $paging->render());
        $this->assign('logs', $logs);
        $this->assign('logType', $type);
        $this->assign('allLogUrl', OW::getRouter()->urlForRoute('credits_logs', array('type' => 'all')));
        $this->assign('sentLogUrl', OW::getRouter()->urlForRoute('credits_logs', array('type' => 'sent')));
        $this->assign('receivedLogUrl', OW::getRouter()->urlForRoute('credits_logs', array('type' => 'received')));

        if (!OW::getUser()->isAdmin() && !OW::getUser()->isAuthorized('credits')) {
            $this->assign('adminLogUrl', 'NULL');
        } else {
            $this->assign('adminLogUrl', OW::getRouter()->urlForRoute('credits_admin_logs'));
        }

        if (!OW::getUser()->isAuthorized('credits', 'send')) {
            $this->assign('transferUrl', 'NULL');
        } else {
            $this->assign('transferUrl', OW::getRouter()->urlForRoute('credits_transfer'));
        }

        $this->assign('buyUrl', OW::getRouter()->urlFor('USERCREDITS_CTRL_BuyCredits', 'index'));

        OW::getDocument()->addStyleSheet(OW::getPluginManager()->getPlugin('credits')->getStaticCssUrl() . 'style.css');
        OW::getDocument()->addScript(OW::getPluginManager()->getPlugin('credits')->getStaticJsUrl() . 'jquery.tablesorter.min.js');

        $this->setPageHeading($language->text('credits', 'your_credits_label'));
        $this->setPageTitle($language->text('credits', 'your_credits_label'));
        $this->setPageHeadingIconClass('ow_ic_gear_wheel');
    }

    public function transfer() {
        if (!OW::getUser()->isAuthenticated()) {
            throw new AuthenticateException();
        }

        if (!OW::getUser()->isAuthorized('credits', 'send')) {
            $this->setTemplate(OW::getPluginManager()->getPlugin('base')->getCtrlViewDir() . 'authorization_failed.html');
            return;
        }

        $language = OW::getLanguage();
        $config = OW::getConfig();

        $userId = OW::getUser()->getId();

        $userCredits = USERCREDITS_BOL_CreditsService::getInstance()->getCreditsBalance($userId);
        $this->assign('userCredits', $userCredits);

        $form = new Form('creditForm');

        $element = new Selectbox('receiveUser');
        $element->setRequired(true);
        $element->setLabel($language->text('credits', 'credit_receive_user'));
        $usersList = CREDITS_BOL_Service::getInstance()->getUserFriends($userId);
        $element->addOptions($usersList);
        $form->addElement($element);

        $element = new TextField('creditPoint');
        $element->setRequired(true);
        $element->setLabel($language->text('credits', 'credits_to_send'));
        $element->addAttribute("style", "width: 100px;");
        $validator = new IntValidator(1, $userCredits);
        $validator->setErrorMessage($language->text('credits', 'credit_value_error'));
        $element->addValidator($validator);
        $form->addElement($element);

        $element = new Submit('sendCredit');
        $element->setValue($language->text('credits', 'send_credits'));
        $form->addElement($element);

        if (OW::getRequest()->isPost()) {
            if ($form->isValid($_POST)) {
                $values = $form->getValues();
                $receiveUser = $values['receiveUser'];
                $creditValue = (int) $values['creditPoint'];

                if (CREDITS_BOL_Service::getInstance()->transferCredits($userId, $receiveUser, $creditValue)) {
                    OW::getFeedback()->info($language->text('credits', 'credit_transfer_ok'));
                    $this->redirect(OW::getRouter()->urlForRoute('credits_transfer'));
                } else {
                    OW::getFeedback()->error($language->text('credits', 'credit_transfer_fail'));
                }
            }
        }

        $this->addForm($form);

        $this->setPageHeading($language->text('credits', 'transfer_credits_label'));
        $this->setPageTitle($language->text('credits', 'transfer_credits_label'));
        $this->setPageHeadingIconClass('ow_ic_gear_wheel');
    }

    public function send(array $params = null) {
        if (!OW::getUser()->isAuthenticated()) {
            throw new AuthenticateException();
        }

        $receiveUser = $params['id'];

        if (!OW::getUser()->isAuthorized('credits', 'send') || !OW::getAuthorization()->isUserAuthorized($receiveUser, 'credits', 'receive') || !isset($params['id'])) {
            $this->setTemplate(OW::getPluginManager()->getPlugin('base')->getCtrlViewDir() . 'authorization_failed.html');
            return;
        }

        $language = OW::getLanguage();
        $config = OW::getConfig();

        $userId = OW::getUser()->getId();

        $userCredits = USERCREDITS_BOL_CreditsService::getInstance()->getCreditsBalance($userId);
        $this->assign('userCredits', $userCredits);
        $this->assign('receiveUserName', BOL_UserService::getInstance()->getDisplayName($receiveUser));

        $form = new Form('creditForm');

        $element = new TextField('creditPoint');
        $element->setRequired(true);
        $element->setLabel($language->text('credits', 'credits_to_send'));
        $element->addAttribute("style", "width: 100px;");
        $validator = new IntValidator(1, $userCredits);
        $validator->setErrorMessage($language->text('credits', 'credit_value_error'));
        $element->addValidator($validator);
        $form->addElement($element);

        $element = new Submit('sendCredit');
        $element->setValue($language->text('credits', 'send_credits'));
        $form->addElement($element);

        if (OW::getRequest()->isPost()) {
            if ($form->isValid($_POST)) {
                $values = $form->getValues();

                $creditValue = (int) $values['creditPoint'];

                if (CREDITS_BOL_Service::getInstance()->transferCredits($userId, $receiveUser, $creditValue)) {
                    OW::getFeedback()->info($language->text('credits', 'credit_transfer_ok'));
                    $this->redirect(OW::getRouter()->urlForRoute('credits_transfer'));
                } else {
                    OW::getFeedback()->error($language->text('credits', 'credit_transfer_fail'));
                }
            }
        }

        $this->addForm($form);

        $this->setPageHeading($language->text('credits', 'transfer_credits_label'));
        $this->setPageTitle($language->text('credits', 'transfer_credits_label'));
        $this->setPageHeadingIconClass('ow_ic_gear_wheel');
    }

}
