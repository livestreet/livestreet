<?php

class ActionStream extends Action
{
	protected $oUserCurrent;

	public function Init()
	{
		$this->oUserCurrent = $this->User_getUserCurrent();
		if (!$this->oUserCurrent) {
			parent::EventNotFound();
		}
		$this->SetDefaultEvent('index');
		$this->Viewer_Assign('aStreamEventTypes', $this->Stream_getEventTypes());

		$this->Viewer_Assign('sMenuItemSelect', 'stream');
	}

	public function Shutdown()
	{

	}

	protected function RegisterEvent()
	{
		$this->AddEvent('index', 'EventIndex');
		$this->AddEvent('subscribe', 'EventSubscribe');
		$this->AddEvent('subscribeByLogin', 'EventSubscribeByLogin');
		$this->AddEvent('unsubscribe', 'EventUnSubscribe');
		$this->AddEvent('switchEventType', 'EventSwitchEventType');
		$this->AddEvent('get_more', 'EventGetMore');
	}

	protected function EventIndex()
	{
		$aEvents = $this->Stream_read();
		$this->Viewer_Assign('aStreamEvents', $aEvents['events']);
		if (isset($aEvents['events']) && count($aEvents['events'])) {
			$aLastEvent = end($aEvents['events']);
			$this->Viewer_Assign('iStreamLastId', $aLastEvent['id']);
			$this->Viewer_Assign('aStreamTopics', $aEvents['topics']);
			$this->Viewer_Assign('aStreamBlogs', $aEvents['blogs']);
			$this->Viewer_Assign('aStreamUsers', $aEvents['users']);
			$this->Viewer_Assign('aStreamComments', $aEvents['comments']);
			if (count($aEvents['events']) < Config::Get('module.stream.count_default')) {
				$this->Viewer_Assign('bDisableGetMoreButton', true);
			} else {
				$this->Viewer_Assign('bDisableGetMoreButton', false);
			}
		}
		$this->SetTemplateAction('list');
	}

	protected function EventSwitchEventType()
	{
		$this->Viewer_SetResponseAjax('json');
		if (!getRequest('type')) {
			$this->Message_AddError($this->Lang_Get('system_error'),$this->Lang_Get('error'));
		}
		$this->Stream_switchUserEventType($this->oUserCurrent->getId(), getRequest('type'));
		$this->Message_AddNotice($this->Lang_Get('stream_subscribes_updated'), $this->Lang_Get('attention'));
	}

	protected function EventGetMore()
	{
		$this->Viewer_SetResponseAjax('json');
		$iFromId = getRequest('last_id');
		if (!$iFromId)  {
			$this->Message_AddError($this->Lang_Get('system_error'),$this->Lang_Get('error'));
			return;
		}
		$aEvents = $this->Stream_read(null, $iFromId);

		$oViewer=$this->Viewer_GetLocalViewer();
		$oViewer->Assign('aStreamEvents', $aEvents['events']);
		if (isset($aEvents['events']) && count($aEvents['events'])) {
			$aLastEvent = end($aEvents['events']);
			$oViewer->Assign('iStreamLastId', $aLastEvent['id']);
			$this->Viewer_AssignAjax('iStreamLastId', $aLastEvent['id']);
			$oViewer->Assign('aStreamTopics', $aEvents['topics']);
			$oViewer->Assign('aStreamBlogs', $aEvents['blogs']);
			$oViewer->Assign('aStreamUsers', $aEvents['users']);
			$oViewer->Assign('aStreamComments', $aEvents['comments']);
		}
		$sFeed = $oViewer->Fetch('stream_list.tpl');
		$this->Viewer_AssignAjax('result', $sFeed);
		$this->Viewer_AssignAjax('events_count', count($aEvents['events']));
	}

	protected function EventSubscribe()
	{
		$this->Viewer_SetResponseAjax('json');
		if (!getRequest('id')) {
			$this->Message_AddError($this->Lang_Get('system_error'),$this->Lang_Get('error'));
		}
		if ($this->oUserCurrent->getId() == getRequest('id')) {
			$this->Message_AddError($this->Lang_Get('stream_error_subscribe_to_yourself'),$this->Lang_Get('error'));
			return;
		}
		$this->Stream_subscribeUser($this->oUserCurrent->getId(), getRequest('id'));
		$this->Message_AddNotice($this->Lang_Get('stream_subscribes_updated'), $this->Lang_Get('attention'));
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
			$this->Message_AddError($this->Lang_Get('stream_error_subscribe_to_yourself'),$this->Lang_Get('error'));
			return;
		}
		$this->Stream_subscribeUser($this->oUserCurrent->getId(),  $oUser->getId());
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
		}
		$this->Stream_unsubscribeUser($this->oUserCurrent->getId(), getRequest('id'));
		$this->Message_AddNotice($this->Lang_Get('stream_subscribes_updated'), $this->Lang_Get('attention'));
	}
}