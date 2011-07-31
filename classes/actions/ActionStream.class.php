<?php
/*-------------------------------------------------------
*
*   LiveStreet Engine Social Networking
*   Copyright © 2008 Mzhelskiy Maxim
*
*--------------------------------------------------------
*
*   Official site: www.livestreet.ru
*   Contact e-mail: rus.engine@gmail.com
*
*   GNU General Public License, version 2:
*   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*
---------------------------------------------------------
*/

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
		/**
		 * Загружаем в шаблон JS текстовки
		 */
		$this->Lang_AddLangJs(array(
			'stream_subscribes_already_subscribed','error'
		));
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
		$aEvents = $this->Stream_Read();
		$this->Viewer_Assign('bDisableGetMoreButton', count($aEvents) < Config::Get('module.stream.count_default'));
		$this->Viewer_Assign('aStreamEvents', $aEvents);
		if (count($aEvents)) {
			$oEvenLast=end($aEvents);
			$this->Viewer_Assign('iStreamLastId', $oEvenLast->getId());
		}
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
		$aEvents = $this->Stream_Read(null, $iFromId);

		$oViewer=$this->Viewer_GetLocalViewer();
		$oViewer->Assign('aStreamEvents', $aEvents);
		if (count($aEvents)) {
			$oEvenLast=end($aEvents);
			$this->Viewer_AssignAjax('iStreamLastId', $oEvenLast->getId());
		}
				
		$this->Viewer_AssignAjax('result', $oViewer->Fetch('actions/ActionStream/events.tpl'));
		$this->Viewer_AssignAjax('events_count', count($aEvents));
	}

	protected function EventSubscribe()
	{
		$this->Viewer_SetResponseAjax('json');
		if (!$this->User_getUserById(getRequest('id'))) {
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
			$this->Message_AddError($this->Lang_Get('user_not_found',array('login'=>getRequest('login'))),$this->Lang_Get('error'));
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
		$this->Message_AddNotice($this->Lang_Get('userfeed_subscribes_updated'), $this->Lang_Get('attention'));
	}

	protected function EventUnsubscribe()
	{
		$this->Viewer_SetResponseAjax('json');
		if (!$this->User_getUserById(getRequest('id'))) {
			$this->Message_AddError($this->Lang_Get('system_error'),$this->Lang_Get('error'));
		}
		$this->Stream_unsubscribeUser($this->oUserCurrent->getId(), getRequest('id'));
		$this->Message_AddNotice($this->Lang_Get('stream_subscribes_updated'), $this->Lang_Get('attention'));
	}
}