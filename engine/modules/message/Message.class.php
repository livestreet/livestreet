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
 *
 */
class LsMessage extends Module {
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
	 * @var array
	 */
	protected $aMsgNoticeSession=array();
	/**
	 * Массив ошибок, который будут показаны на СЛЕДУЮЩЕЙ страничке
	 * @var array
	 */
	protected $aMsgErrorSession=array();
	
	/**
	 * Инициализация модуля
	 *
	 */
	public function Init() {			
	}
	
	/**
	 * При завершении работы модуля передаем списки сообщений в шаблоны Smarty
	 *
	 */
	public function Shutdown() {	
	    // Логика здесь такая - получаем сообщения, которые содержаться в сессии
	    // и добавляем их к выводимым. А те сообщения, которые были добавлены
	    // текущими экшенами (в этом сеансе), вкладываем в сессию.          
	    $sNoticeSession = $this->Session_Get('message_notice_session');
	    $aNotice=(!$sNoticeSession) 
	        ? $this->GetNotice()
	        : array_merge($this->GetNotice(), (array)unserialize($sNoticeSession));
	    $this->Session_Set('message_notice_session', serialize($this->GetNoticeSession()));

	    $sErrorSession = $this->Session_Get('message_error_session');
	    $aError=(!$sErrorSession) 
	        ? $this->GetError()
	        : array_merge($this->GetError(), (array)unserialize($sErrorSession));
	    $this->Session_Set('message_error_session', serialize($this->GetErrorSession()));
	    
		$this->Viewer_Assign('aMsgError',$aError);		
		$this->Viewer_Assign('aMsgNotice',$aNotice);		
	}
	/**
	 * Добавляет новое сообщение об ошибке
	 *
	 * @param string $sMsg
	 * @param string $sTitle
	 * @param bool   $bUseSession
	 */
	public function AddError($sMsg,$sTitle=null,$bUseSession=false) {
		if(!$bUseSession) {			
			$this->aMsgError[]=array('msg'=>$sMsg,'title'=>$sTitle);
		} else {
			$this->aMsgErrorSession[]=array('msg'=>$sMsg,'title'=>$sTitle);
		}
	}
	
	/**
	 * Создаёт идинственное сообщение об ошибке(т.е. очищает все предыдущие)
	 *
	 * @param string $sMsg
	 * @param string $sTitle
	 * @param bool   $bUseSession
	 */
	public function AddErrorSingle($sMsg,$sTitle=null,$bUseSession=false) {
		if(!$bUseSession) {				
			$this->aMsgError=array();
			$this->aMsgError[]=array('msg'=>$sMsg,'title'=>$sTitle);
		} else {
			$this->aMsgErrorSession=array();
			$this->aMsgErrorSession[]=array('msg'=>$sMsg,'title'=>$sTitle);			
		}
	}
	/**
	 * Добавляет новое сообщение
	 *
	 * @param string $sMsg
	 * @param string $sTitle
	 * @param bool   $bUseSession
	 */
	public function AddNotice($sMsg,$sTitle=null,$bUseSession=false) {
		if(!$bUseSession) {		
			$this->aMsgNotice[]=array('msg'=>$sMsg,'title'=>$sTitle);
		} else {
			$this->aMsgNoticeSession[]=array('msg'=>$sMsg,'title'=>$sTitle);			
		}
	}
	
	/**
	 * Создаёт идинственное сообщение, удаляя предыдущие
	 *
	 * @param string $sMsg
	 * @param string $sTitle
	 * @param bool   $bUseSession
	 */
	public function AddNoticeSingle($sMsg,$sTitle=null,$bUseSession=false) {
		if(!$bUseSession) {
			$this->aMsgNotice=array();
			$this->aMsgNotice[]=array('msg'=>$sMsg,'title'=>$sTitle);			
		} else {
			$this->aMsgNoticeSession=array();
			$this->aMsgNoticeSession[]=array('msg'=>$sMsg,'title'=>$sTitle);					
		}
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