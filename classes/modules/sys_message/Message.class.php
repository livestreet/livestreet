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
		$this->Viewer_Assign('aMsgError',$this->GetError());
		$this->Viewer_Assign('aMsgNotice',$this->GetNotice());		
	}
	/**
	 * Добавляет новое сообщение об ошибке
	 *
	 * @param string $sMsg
	 * @param string $sTitle
	 */
	public function AddError($sMsg,$sTitle=null) {
		$this->aMsgError[]=array('msg'=>$sMsg,'title'=>$sTitle);
	}
	
	/**
	 * Создаёт идинственное сообщение об ошибке(т.е. очищает все предыдущие)
	 *
	 * @param string $sMsg
	 * @param string $sTitle
	 */
	public function AddErrorSingle($sMsg,$sTitle=null) {
		$this->aMsgError=array();
		$this->aMsgError[]=array('msg'=>$sMsg,'title'=>$sTitle);
	}
	/**
	 * Добавляет новое сообщение
	 *
	 * @param string $sMsg
	 * @param string $sTitle
	 */
	public function AddNotice($sMsg,$sTitle=null) {
		$this->aMsgNotice[]=array('msg'=>$sMsg,'title'=>$sTitle);
	}
	
	/**
	 * Создаёт идинственное сообщение, удаляя предыдущие
	 *
	 * @param string $sMsg
	 * @param string $sTitle
	 */
	public function AddNoticeSingle($sMsg,$sTitle=null) {
		$this->aMsgNotice=array();
		$this->aMsgNotice[]=array('msg'=>$sMsg,'title'=>$sTitle);
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
}
?>