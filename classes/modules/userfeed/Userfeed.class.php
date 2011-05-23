<?php

class ModuleUserfeed extends Module
{
    const SUBSCRIBE_TYPE_BLOG = 1;
    const SUBSCRIBE_TYPE_USER = 2;

    protected $oMapper = null;

    public function Init()
    {
        $this->oMapper=Engine::GetMapper(__CLASS__);
    }

    public function subscribeUser($iUserId, $iSubscribeType, $iTargetId)
    {
        return $this->oMapper->subscribeUser($iUserId, $iSubscribeType, $iTargetId);
    }

    public function unsubscribeUser($iUserId, $iSubscribeType, $iTargetId)
    {
        return $this->unsubscribeUser($iUserId, $iSubscribeType, $iTargetId);
    }

    public function updateSubscribes($iUserId, $aUserSubscribes)
    {
        return $this->updateSubscribes($iUserId, $aUserSubscribes);
    }

    public function read($iUserId, $iCount = null, $iFromId = null)
    {
        if (!$iCount) $iCount = Config::Get('module.userfeed.count_default');
        $aUserSubscribes = $this->oMapper->getUserSubscribes($iUserId);
        $aTopicsIds = $this->oMapper->readFeed($aUserSubscribes, $iCount, $iFromId);
        return $this->Topic_getTopicsAdditionalData($aTopicsIds);
    }

    public function getUserSubscribes($iUserId)
    {
        $aUserSubscribes = $this->oMapper->getUserSubscribes($iUserId);
        $aResult = array('blogs' => array(), 'users' => array());
        if (count($aUserSubscribes['blogs'])) {
            $aResult['blogs'] = $this->Blog_getBlogsByArrayId($aUserSubscribes['blogs']);
//            foreach ($aUserSubscribes['blogs'] as $iBlogId) {
//                $oBlog = $this->Blog_getBlogById($iBlogId);
//                if ($oBlog) {
//                    $aResult['blogs'][] = $oBlog;
//                }
//            }
        }
        if (count($aUserSubscribes['users'])) {
            $aResult['users'] = $this->User_getUsersByArrayId($aUserSubscribes['users']);
        }

        return $aResult;
    }
}