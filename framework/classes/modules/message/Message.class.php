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

/**
 * Модуль системных сообщений
 * Позволяет показывать пользователю сообщения двух видов - об ошибке и об успешном действии.
 * <pre>
 * $this->Message_AddErrorSingle($this->Lang_Get('not_access'),$this->Lang_Get('error'));
 * </pre>
 *
 * @package engine.modules
 * @since 1.0
 */
class ModuleMessage extends Module {
	/**
	 * Массив сообщений со статусом ОШИБКА
	 *
	 * @var array
	 */
	protected $aMsgError=array();
	/**
	 * Массив сообщений со статусом СООБЩЕНИЕ
	 *
	 * @var array
	 */
	protected $aMsgNotice=array();
	/**
	 * Массив сообщений, который будут показаны на СЛЕДУЮЩЕЙ страничке
	 *
	 * @var array
	 */
	protected $aMsgNoticeSession=array();
	/**
	 * Массив ошибок, который будут показаны на СЛЕДУЮЩЕЙ страничке
	 *
	 * @var array
	 */
	protected $aMsgErrorSession=array();

	/**
	 * Инициализация модуля
	 *
	 */
	public function Init() {
		/**
		 * Добавляем сообщения и ошибки, которые содержались в сессии
		 */
		$aNoticeSession = $this->Session_Get('message_notice_session');
		if(is_array($aNoticeSession) and count($aNoticeSession)) {
			$this->aMsgNotice = $aNoticeSession;
		}
		$aErrorSession = $this->Session_Get('message_error_session');
		if(is_array($aErrorSession) and count($aErrorSession)) {
			$this->aMsgError = $aErrorSession;
		}
	}
	/**
	 * При завершении работы модуля передаем списки сообщений в шаблоны Smarty
	 *
	 */
	public function Shutdown() {
		/**
		 * Добавляем в сессию те сообщения, которые были отмечены для сессионного использования
		 */
		$this->Session_Set('message_notice_session', $this->GetNoticeSession());
		$this->Session_Set('message_error_session', $this->GetErrorSession());

		$this->Viewer_Assign('aMsgError',$this->GetError());
		$this->Viewer_Assign('aMsgNotice',$this->GetNotice());
	}
	/**
	 * Добавляет новое сообщение об ошибке
	 *
	 * @param string $sMsg	Сообщение
	 * @param string $sTitle	Заголовок
	 * @param bool   $bUseSession	Показать сообщение при следующем обращении пользователя к сайту
	 */
	public function AddError($sMsg,$sTitle=null,$bUseSession=false) {
		if(!$bUseSession) {
			$this->aMsgError[]=array('msg'=>$sMsg,'title'=>$sTitle);
		} else {
			$this->aMsgErrorSession[]=array('msg'=>$sMsg,'title'=>$sTitle);
		}
	}
	/**
	 * Создаёт единственное сообщение об ошибке(т.е. очищает все предыдущие)
	 *
	 * @param string $sMsg	Сообщение
	 * @param string $sTitle	Заголовок
	 * @param bool   $bUseSession	Показать сообщение при следующем обращении пользователя к сайту
	 */
	public function AddErrorSingle($sMsg,$sTitle=null,$bUseSession=false) {
		$this->ClearError();
		$this->AddError($sMsg,$sTitle,$bUseSession);
	}
	/**
	 * Добавляет новое сообщение
	 *
	 * @param string $sMsg	Сообщение
	 * @param string $sTitle	Заголовок
	 * @param bool   $bUseSession	Показать сообщение при следующем обращении пользователя к сайту
	 */
	public function AddNotice($sMsg,$sTitle=null,$bUseSession=false) {
		if(!$bUseSession) {
			$this->aMsgNotice[]=array('msg'=>$sMsg,'title'=>$sTitle);
		} else {
			$this->aMsgNoticeSession[]=array('msg'=>$sMsg,'title'=>$sTitle);
		}
	}
	/**
	 * Создаёт единственное сообщение, удаляя предыдущие
	 *
	 * @param string $sMsg	Сообщение
	 * @param string $sTitle	Заголовок
	 * @param bool   $bUseSession	Показать сообщение при следующем обращении пользователя к сайту
	 */
	public function AddNoticeSingle($sMsg,$sTitle=null,$bUseSession=false) {
		$this->ClearNotice();
		$this->AddNotice($sMsg,$sTitle,$bUseSession);
	}
	/**
	 * Очищает стек сообщений
	 *
	 */
	public function ClearNotice() {
		$this->aMsgNotice=array();
		$this->aMsgNoticeSession=array();
	}
	/**
	 * Очищает стек ошибок
	 *
	 */
	public function ClearError() {
		$this->aMsgError=array();
		$this->aMsgErrorSession=array();
	}
	/**
	 * Получает список сообщений об ошибке
	 *
	 * @return array
	 */
	public function GetError() {
		return $this->aMsgError;
	}
	/**
	 * Получает список сообщений
	 *
	 * @return array
	 */
	public function GetNotice() {
		return $this->aMsgNotice;
	}
	/**
	 * Возвращает список сообщений,
	 * которые необходимо поместить в сессию
	 *
	 * @return array
	 */
	public function GetNoticeSession() {
		return $this->aMsgNoticeSession;
	}
	/**
	 * Возвращает список ошибок,
	 * которые необходимо поместить в сессию
	 *
	 * @return array
	 */
	public function GetErrorSession() {
		return $this->aMsgErrorSession;
	}
}
?>