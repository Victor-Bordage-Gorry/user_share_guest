<?php

/**
 * ownCloud - User Share Guest
 *
 * @author Victor Bordage-Gorry <victor.bordage-gorry@globalis-ms.com>
 * @copyright 2016 CNRS DSI / GLOBALIS media systems
 * @license This file is licensed under the Affero General Public License version 3 or later. See the COPYING file.
 */

namespace OCA\User_Share_Guest\Service;

use \OCP\IL10N;
use \OCP\IConfig;

/**
 * Send mail on hook trigger
 */
Class MailService
{
    protected $appName;
    protected $l;
    protected $config;
    protected $userManager;
    protected $userId;
    protected $urlGenerator;

    public function __construct($appName, $userId, IL10N $l, IConfig $config, $userManager, $urlGenerator)
    {
        $this->appName = $appName;
        $this->userId = $userId;
        $this->l = $l;
        $this->config = $config;
        $this->userManager = $userManager;
        $this->urlGenerator = $urlGenerator;
    }

    public function sendMailGuest($uid, $token) {

        // Mail part
        $theme = new \OC_Defaults;
        $subject = (string)$this->l->t('A user of MyCore wish you invite and share files with you');
        $parameter = array('token' => $token, 'uid' => $uid);

        $url = $_SERVER['HTTP_HOST'] . $this->urlGenerator->linkToRoute('user_share_guest.page.confirm', $parameter);

        // generate the content
        $html = new \OCP\Template($this->appName, "mail_usershareguest_html", "");
        $html->assign('overwriteL10N', $this->l);
        $html->assign('sharerUid', $this->userId);
        $html->assign('accountUrl', $url);
        $htmlMail = $html->fetchPage();

        $alttext = new \OCP\Template($this->appName, "mail_usershareguest_text", "");
        $alttext->assign('overwriteL10N', $this->l);
        $alttext->assign('sharerUid', $this->userId);
        $alttext->assign('accountUrl', $url);
        $altMail = $alttext->fetchPage();

        $fromAddress = $fromName = \OCP\Util::getDefaultEmailAddress('owncloud');
        $toAddress = $uid;
        $toName = $uid;

        //sending
        try {
            \OCP\Util::sendMail($toAddress, $toName, $subject, $htmlMail, $fromAddress, $fromName, 1, $altMail);
        } catch (\Exception $e) {
            \OCP\Util::writeLog($this->appName, "Can't send mail for guest's invitation : " . $e->getMessage(), \OCP\Util::ERROR);
        }
    }

}
