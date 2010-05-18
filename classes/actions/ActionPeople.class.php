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
 * Обработка статистики юзеров, т.е. УРЛа вида /people/
 *
 */
class ActionPeople extends Action {
	/**
	 * Главное меню
	 *
	 * @var unknown_type
	 */
	protected $sMenuHeadItemSelect='people';
	
	protected $sMenuItemSelect='people';
		
	/**
	 * Инициализация
	 *
	 */
	public function Init() {
		$this->SetDefaultEvent('good');	
		$this->Viewer_AddHtmlTitle($this->Lang_Get('people'));
	}
	/**
	 * Регистрируем евенты
	 *
	 */
	protected function RegisterEvent() {		
		$this->AddEvent('good','EventGood');		
		$this->AddEvent('bad','EventBad');	
		$this->AddEvent('online','EventOnline');	
		$this->AddEvent('new','EventNew');
			
		$this->AddEventPreg('/^country$/i','/^.+$/i','/^(page(\d+))?$/i','EventCountry');
		$this->AddEventPreg('/^city$/i','/^.+$/i','/^(page(\d+))?$/i','EventCity');
	}
		
	
	/**********************************************************************************
	 ************************ РЕАЛИЗАЦИЯ ЭКШЕНА ***************************************
	 **********************************************************************************
	 */
	
	/**
	 * Показывает юзеров по стране
	 *
	 */
	protected function EventCountry() {		
		if (!($oCountry=$this->User_GetCountryByName(urldecode($this->getParam(0))))) {
			return parent::EventNotFound();
		}
		/**
		 * Получаем статистику
		 */
		$this->GetStats();	
		/**
		 * Передан ли номер страницы
		 */
		$iPage=$this->GetParamEventMatch(1,2) ? $this->GetParamEventMatch(1,2) : 1;				
		/**
		 * Получаем список юзеров
		 */					
		$aResult=$this->User_GetUsersByCountry($oCountry->getName(),$iPage,Config::Get('module.user.per_page'));	
		$aUsersCountry=$aResult['collection'];
		/**
		 * Формируем постраничность
		 */			
		$aPaging=$this->Viewer_MakePaging($aResult['count'],$iPage,Config::Get('module.user.per_page'),4,Router::GetPath('people').$this->sCurrentEvent.'/'.$oCountry->getName());
		/**
		 * Загружаем переменные в шаблон
		 */
		if ($aUsersCountry) {
			$this->Viewer_Assign('aPaging',$aPaging);			
		}	
		$this->Viewer_Assign('oCountry',$oCountry);
		$this->Viewer_Assign('aUsersCountry',$aUsersCountry);				
	}
	/**
	 * Показывает юзеров по городу
	 *
	 */
	protected function EventCity() {		
		if (!($oCity=$this->User_GetCityByName(urldecode($this->getParam(0))))) {
			return parent::EventNotFound();
		}
		/**
		 * Получаем статистику
		 */
		$this->GetStats();	
		/**
		 * Передан ли номер страницы
		 */
		$iPage=$this->GetParamEventMatch(1,2) ? $this->GetParamEventMatch(1,2) : 1;		
		/**
		 * Получаем список юзеров
		 */					
		$aResult=$this->User_GetUsersByCity($oCity->getName(),$iPage,Config::Get('module.user.per_page'));	
		$aUsersCity=$aResult['collection'];
		/**
		 * Формируем постраничность
		 */			
		$aPaging=$this->Viewer_MakePaging($aResult['count'],$iPage,Config::Get('module.user.per_page'),4,Router::GetPath('people').$this->sCurrentEvent.'/'.$oCity->getName());
		/**
		 * Загружаем переменные в шаблон
		 */
		if ($aUsersCity) {
			$this->Viewer_Assign('aPaging',$aPaging);			
		}	
		$this->Viewer_Assign('oCity',$oCity);
		$this->Viewer_Assign('aUsersCity',$aUsersCity);				
	}
	/**
	 * Показываем последних на сайте
	 *
	 */
	protected function EventOnline() {
		/**
		 * Последние по визиту на сайт
		 */
		$aUsersLast=$this->User_GetUsersByDateLast(15);
		$this->Viewer_Assign('aUsersLast',$aUsersLast);
		/**
		 * Получаем статистику
		 */
		$this->GetStats();		
	}
	/**
	 * Показываем новых на сайте
	 *
	 */
	protected function EventNew() {
		/**
		 * Последние по регистрации
		 */
		$aUsersRegister=$this->User_GetUsersByDateRegister(15);
		$this->Viewer_Assign('aUsersRegister',$aUsersRegister);
		/**
		 * Получаем статистику
		 */
		$this->GetStats();		
	}
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
		 * Статистика кто, где и т.п.
		 */
		$aStat=$this->User_GetStatUsers();		
		/**
		 * Загружаем переменные в шаблон
		 */
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
		$iPage=preg_match("/^page(\d+)$/i",$this->getParam(0),$aMatch) ? $aMatch[1] : 1;				
		/**
		 * Получаем список юзеров
		 */		
		$aResult=$this->User_GetUsersRating($sType,$iPage,Config::Get('module.user.per_page'));	
		$aUsersRating=$aResult['collection'];	
		/**
		 * Формируем постраничность
		 */			
		$aPaging=$this->Viewer_MakePaging($aResult['count'],$iPage,Config::Get('module.user.per_page'),4,Router::GetPath('people').$this->sCurrentEvent);
		/**
		 * Загружаем переменные в шаблон
		 */
		if ($aUsersRating) {
			$this->Viewer_Assign('aPaging',$aPaging);			
		}	
		$this->Viewer_Assign('aUsersRating',$aUsersRating);
	}
	
	/**
	 * Выполняется при завершении работы экшена
	 *
	 */
	public function EventShutdown() {		
		/**
		 * Загружаем в шаблон необходимые переменные
		 */
		$this->Viewer_Assign('sMenuHeadItemSelect',$this->sMenuHeadItemSelect);
		$this->Viewer_Assign('sMenuItemSelect',$this->sMenuItemSelect);
	}
}
?>