<?php

/**
 * ownCloud - User Share Guest
 *
 * @author Victor Bordage-Gorry <victor.bordage-gorry@globalis-ms.com>
 * @copyright 2016 CNRS DSI / GLOBALIS media systems
 * @license This file is licensed under the Affero General Public License version 3 or later. See the COPYING file.
 */

namespace OCA\User_Share_Guest\Controller;

use \OCP\AppFramework\Controller;
use \OCA\User_Share_Guest\Db\GuestMapper;
use \OCA\User_Share_Guest\Db\Guest;
use OCP\AppFramework\Http\TemplateResponse;

class PageController extends Controller {

    protected $l;
    protected $guestMapper;
    protected $userId;
    protected $userManager;
    protected $urlGenerator;

    public function __construct($appName, $request, $l, GuestMapper $guestMapper, $userId, $userManager, $urlGenerator) {
        parent::__construct($appName, $request);
        $this->l = $l;
        $this->guestMapper = $guestMapper;
        $this->userId = $userId;
        $this->userManager = $userManager;
        $this->urlGenerator = $urlGenerator;

    }

    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     * @PublicPage
     *
     * @param string $uid
     * @param string $token
     *
     * @return \OCP\AppFramework\Http\TemplateResponse
     */
    public function confirm($uid, $token) {
        if (!$this->guestMapper->verifyGuestToken($uid, $token) || $this->guestMapper->hasGuestAccepted($uid)) {
            \OC_Util::redirectToDefaultPage();
            exit();
        }

        $templateName = 'public';
        $parameters = array('l' => $this->l, 'uid' => $uid);

        return new TemplateResponse($this->appName, $templateName, $parameters, 'guest');
    }

    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     * @PublicPage
     *
     * @param string $uid
     * @param string $password
     * @param string $passwordconfirm
     */
    public function accept($uid, $password, $passwordconfirm) {
        $error = '';

        if ($password !== $passwordconfirm) {
            $error = $this->l->t('Passwords are different, please check your entry');
        }
        /*elseif (!\OC_Password_Policy::testPassword($password)) {
            $error = $this->l->t('Your password is not enough secure');
        }*/

        if ($error === '') {
            $this->guestMapper->updateGuest($uid, array('accepted' => 1, 'is_active' => 1, 'last_connection' => 'NOW()'));
            \OC_User::setPassword($uid, $password);
            \OC_User::login($uid, $password);
            $url = $this->urlGenerator->linkToRoute('user_share_guest.page.share_list');
            $url = $this->urlGenerator->getAbsoluteURL($url);
            header('Location: ' . $url);
            exit();
        }

        $templateName = 'public';
        $parameters = array('l' => $this->l, 'uid' => $uid, 'error' => $error);
        return new TemplateResponse($this->appName, $templateName, $parameters, 'guest');
    }

    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     *
     */
    public function shareList() {
        $user = $this->userManager->get($this->userId);

        $templateName = 'list';
        $parameters = array(
            'l' => $this->l,
            'user_displayname' => $user->getDisplayname(),
            'user_uid' => $this->userId
        );
        return new TemplateResponse($this->appName, $templateName, $parameters, 'base');
    }
}
