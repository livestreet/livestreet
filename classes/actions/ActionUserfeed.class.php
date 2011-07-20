<?php

class ActionUserfeed extends Action
{
    protected $oUserCurrent;

    public function Init()
    {
        $this->oUserCurrent = $this->User_getUserCurrent();
        if (!$this->oUserCurrent) {
            parent::EventNotFound();
        }
        $this->SetDefaultEvent('index');
        
        $this->Viewer_Assign('sMenuItemSelect', 'feed');
    }

    protected function RegisterEvent()
    {
        $this->AddEvent('index', 'EventIndex');
        $this->AddEvent('update', 'EventUpdateSubscribes');
        $this->AddEvent('subscribe', 'EventSubscribe');
        $this->AddEvent('subscribeByLogin', 'EventSubscribeByLogin');
        $this->AddEvent('unsubscribe', 'EventUnSubscribe');
        $this->AddEvent('get_more', 'EventGetMore');
    }

    protected function EventIndex()
    {
        $aTopics = $this->Userfeed_read($this->oUserCurrent->getId());
        $this->Viewer_Assign('aTopics', $aTopics);
        if (count($aTopics)) {
            $this->Viewer_Assign('iUserfeedLastId', end($aTopics)->getId());
        }
        if (count($aTopics) < Config::Get('module.userfeed.count_default')) {
            $this->Viewer_Assign('bDisableGetMoreButton', true);
        } else {
            $this->Viewer_Assign('bDisableGetMoreButton', false);
        }
        $this->SetTemplateAction('list');
    }

    protected function EventGetMore()
    {
        $this->Viewer_SetResponseAjax('json');
        $iFromId = getRequest('last_id');
        if (!$iFromId)  {
            $this->Message_AddError($this->Lang_Get('system_error'),$this->Lang_Get('error'));
            return;
        }
        $aTopics = $this->Userfeed_read($this->oUserCurrent->getId(), null, $iFromId);

        $oViewer=$this->Viewer_GetLocalViewer();
		$oViewer->Assign('aTopics',  $aTopics);
		$sFeed = $oViewer->Fetch('topic_list.tpl');
        $this->Viewer_AssignAjax('result', $sFeed);
        $this->Viewer_AssignAjax('topics_count', count($aTopics));

        if (count($aTopics)) {
            $this->Viewer_AssignAjax('iUserfeedLastId', end($aTopics)->getId());
        }
    }

    protected function EventSubscribe()
    {
        $this->Viewer_SetResponseAjax('json');
        if (!getRequest('id')) {
            $this->Message_AddError($this->Lang_Get('system_error'),$this->Lang_Get('error'));
        }
        $sType = getRequest('type');
        $iType = null;
        switch($sType) {
            case 'blogs':
                $iType = ModuleUserfeed::SUBSCRIBE_TYPE_BLOG;
                break;
            case 'users':
                $iType = ModuleUserfeed::SUBSCRIBE_TYPE_USER;
                if ($this->oUserCurrent->getId() == getRequest('id')) {
                    $this->Message_AddError($this->Lang_Get('userfeed_error_subscribe_to_yourself'),$this->Lang_Get('error'));
                    return;
                }
                break;
            default:
                $this->Message_AddError($this->Lang_Get('system_error'),$this->Lang_Get('error'));
                return;
        }
        $this->Userfeed_subscribeUser($this->oUserCurrent->getId(), $iType, getRequest('id'));
        $this->Message_AddNotice($this->Lang_Get('userfeed_subscribes_updated'), $this->Lang_Get('attention'));
    }

    protected function EventSubscribeByLogin()
    {
        $this->Viewer_SetResponseAjax('json');
        if (!getRequest('login')) {
            $this->Message_AddError($this->Lang_Get('system_error'),$this->Lang_Get('error'));
            return;
        }
        $oUser = $this->User_getUserByLogin(getRequest('login'));
        if (!$oUser) {
            $this->Message_AddError($this->Lang_Get('system_error'),$this->Lang_Get('error'));
            return;
        }
        if ($this->oUserCurrent->getId() == $oUser->getId()) {
            $this->Message_AddError($this->Lang_Get('userfeed_error_subscribe_to_yourself'),$this->Lang_Get('error'));
            return;
        }
        $this->Userfeed_subscribeUser($this->oUserCurrent->getId(), ModuleUserfeed::SUBSCRIBE_TYPE_USER, $oUser->getId());
        $this->Viewer_AssignAjax('uid', $oUser->getId());
        $this->Viewer_AssignAjax('user_login', $oUser->getLogin());
        $this->Viewer_AssignAjax('user_web_path', $oUser->getuserWebPath());
        $this->Viewer_AssignAjax('lang_error_msg', $this->Lang_Get('userfeed_subscribes_already_subscribed'));
        $this->Viewer_AssignAjax('lang_error_title', $this->Lang_Get('error'));
        $this->Message_AddNotice($this->Lang_Get('userfeed_subscribes_updated'), $this->Lang_Get('attention'));
    }

    protected function EventUnsubscribe()
    {
        $this->Viewer_SetResponseAjax('json');
        if (!getRequest('id')) {
            $this->Message_AddError($this->Lang_Get('system_error'),$this->Lang_Get('error'));
            return;
        }
        $sType = getRequest('type');
        $iType = null;
        switch($sType) {
            case 'blogs':
                $iType = ModuleUserfeed::SUBSCRIBE_TYPE_BLOG;
                break;
            case 'users':
                $iType = ModuleUserfeed::SUBSCRIBE_TYPE_USER;
                break;
            default:
                $this->Message_AddError($this->Lang_Get('system_error'),$this->Lang_Get('error'));
                return;
        }
        $this->Userfeed_unsubscribeUser($this->oUserCurrent->getId(), $iType, getRequest('id'));
        $this->Message_AddNotice($this->Lang_Get('userfeed_subscribes_updated'), $this->Lang_Get('attention'));
    }

    protected function EventUpdateSubscribes()
    {
        $this->Viewer_SetResponseAjax('json');
        $sType = getRequest('type');
        $iType = null;
        switch($sType) {
            case 'blogs':
                $iType = ModuleUserfeed::SUBSCRIBE_TYPE_BLOG;
                break;
            case 'users':
                $iType = ModuleUserfeed::SUBSCRIBE_TYPE_USER;
                break;
            default:
                $this->Message_AddError($this->Lang_Get('system_error'),$this->Lang_Get('error'));
                return;
        }
        $aIds = explode(',', getRequest('ids'));
        $aUserSubscribes = array('users' => array(), 'blogs' => array());
        $aUserSubscribes[$sType] = $aIds;
        $this->Userfeed_updateSubscribes($this->oUserCurrent->getId(), $aUserSubscribes, $iType);
        $this->Message_AddNotice($this->Lang_Get('userfeed_subscribes_updated'), $this->Lang_Get('attention'));
    }
}