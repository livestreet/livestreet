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

        $oUserGolfer = Engine::GetEntity('User');
        $oUserGolfer->setLogin('golfer');
        $oUserGolfer->setPassword(md5('qwerty'));
        $oUserGolfer->setMail('golfer@gmail.com');

        $oUserGolfer->setUserDateRegister(date("Y-m-d H:i:s")); // @todo freeze
        $oUserGolfer->setUserIpRegister('127.0.0.1');
        $oUserGolfer->setUserActivate('1');
        $oUserGolfer->setUserActivateKey('0');

        $this->oEngine->User_Add($oUserGolfer);

        $oUserGolfer->setProfileName('Sergey Doryba');
        $oUserGolfer->setProfileAbout('...  Sergey Doryba profile description');
        $oUserGolfer->setProfileSex('man');

        $this->oEngine->User_Update($oUserGolfer);
        $this->addReference('user-golfer', $oUserGolfer);
    }

}

