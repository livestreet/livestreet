<?
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
 * Обработка статистики юзеров, т.е. УРЛа вида /people/
 *
 */
class ActionPeople extends Action {
		
	/**
	 * Инициализация
	 *
	 */
	public function Init() {		
		$this->SetDefaultEvent('good');	
		$this->Viewer_AddHtmlTitle('Люди');	
		
		$this->Viewer_AddBlocks('right',array('actions/ActionPeople/sidebar.tpl'));
	}
	/**
	 * Регистрируем евенты
	 *
	 */
	protected function RegisterEvent() {		
		$this->AddEvent('good','EventGood');		
		$this->AddEvent('bad','EventBad');	
	}
		
	
	/**********************************************************************************
	 ************************ РЕАЛИЗАЦИЯ ЭКШЕНА ***************************************
	 **********************************************************************************
	 */
	
	/**
	 * Показываем хороших юзеров
	 *
	 */
	protected function EventGood() {
		/**
		 * Получаем статистику
		 */
		$this->GetStats();		
		/**
		 * Получаем хороших юзеров
		 */
		$this->GetUserRating('good');	
		/**
		 * Устанавливаем шаблон вывода
		 */		
		$this->SetTemplateAction('index');	
	}		
	/**
	 * Показываем плохих юзеров
	 *
	 */
	protected function EventBad() {	
		/**
		 * Получаем статистику
		 */
		$this->GetStats();
		/**
		 * Получаем хороших юзеров
		 */
		$this->GetUserRating('bad');
		/**
		 * Устанавливаем шаблон вывода
		 */		
		$this->SetTemplateAction('index');
	}
	/**
	 * Получение статистики
	 *
	 */
	protected function GetStats() {
		/**
		 * Последние по визиту на сайт
		 */
		$aUsersLast=$this->User_GetUsersByDateLast(15);		
		/**
		 * Последние по регистрации
		 */
		$aUsersRegister=$this->User_GetUsersByDateRegister(15);		
		/**
		 * Статистика кто, где и т.п.
		 */
		$aStat=$this->User_GetStatUsers();		
		/**
		 * Загружаем переменные в шаблон
		 */
		$this->Viewer_Assign('aUsersLast',$aUsersLast);
		$this->Viewer_Assign('aUsersRegister',$aUsersRegister);
		$this->Viewer_Assign('aStat',$aStat);
	}	
	/**
	 * Получаем список юзеров 
	 *
	 * @param unknown_type $sType
	 */
	protected function GetUserRating($sType) {
		/**
		 * Передан ли номер страницы
		 */
		if (preg_match("/^page(\d+)$/i",$this->getParam(0),$aMatch)) {			
			$iPage=$aMatch[1];
		} else {
			$iPage=1;
		}		
		/**
		 * Получаем список юзеров
		 */
		$iCount=0;			
		$aResult=$this->User_GetUsersRating($sType,$iCount,$iPage,USER_PER_PAGE);	
		$aUsersRating=$aResult['collection'];	
		/**
		 * Формируем постраничность
		 */			
		$aPaging=$this->Viewer_MakePaging($aResult['count'],$iPage,USER_PER_PAGE,4,DIR_WEB_ROOT.'/people/'.$this->sCurrentEvent);
		/**
		 * Загружаем переменные в шаблон
		 */
		if ($aUsersRating) {
			$this->Viewer_Assign('aPaging',$aPaging);			
		}	
		$this->Viewer_Assign('aUsersRating',$aUsersRating);
	}
}
?>