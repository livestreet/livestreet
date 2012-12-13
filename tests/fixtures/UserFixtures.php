<?php

require_once(realpath((dirname(__FILE__)) . "/../AbstractFixtures.php"));

class UserFixtures extends AbstractFixtures
{
    public static function getOrder()
    {
        return 0;
    }

    public function load()
    {
        $oUserFirst = $this->_createUser('user-golfer', 'qwerty','user_first@info.com', '2012-11-1 00:10:20');

        $oUserFirst->setProfileName('Golfer FullName');
        $oUserFirst->setProfileAbout('... Golfer profile description');
        $oUserFirst->setProfileSex('man');

        $this->oEngine->User_Update($oUserFirst);
        $this->addReference('user-golfer', $oUserFirst);

        $oUserFriend = $this->_createUser('user-friend', 'qwerty','user_friend@info.com', '2012-11-1 10:20:30');
        $oUserFriend->setProfileName('Friend FullName');
        $oUserFriend->setProfileAbout('... Friend profile description');
        $oUserFriend->setProfileSex('man');

        $this->oEngine->User_Update($oUserFriend);
        $this->addReference('user-friend', $oUserFriend);

        $friend = $this->oEngine->GetEntity('User_Friend');
        $friend->setUserFrom($oUserFirst->getId());
        $friend->setUserTo($oUserFriend->getId());
        $friend->setStatusFrom(1);
        $friend->setStatusTo(2);

        $this->oEngine->User_AddFriend($friend);

    }

    /**
     * Create user with default values
     *
     * @param string $sUserName
     * @param string $sPassword
     * @param string $sMail
     * @param string $sDate
     *
     * @return ModuleTopic_EntityUser
     */
    private function _createUser($sUserName, $sPassword,$sMail,$sDate)
    {
        $oUser = Engine::GetEntity('User');
        $oUser->setLogin($sUserName);
        $oUser->setPassword(md5($sPassword));
        $oUser->setMail($sMail);
        $oUser->setUserDateRegister($sDate);
        $oUser->setUserIpRegister('127.0.0.1');
        $oUser->setUserActivate('1');
        $oUser->setUserActivateKey('0');

        $this->oEngine->User_Add($oUser);

        return $oUser;
    }

}