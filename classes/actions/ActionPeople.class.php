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
		
	/**
	 * Инициализация
	 *
	 */
	public function Init() {
		$this->Viewer_AddHtmlTitle($this->Lang_Get('people'));
	}
	/**
	 * Регистрируем евенты
	 *
	 */
	protected function RegisterEvent() {		
		$this->AddEvent('online','EventOnline');
		$this->AddEvent('new','EventNew');
		$this->AddEventPreg('/^(index)?$/i','/^(page(\d+))?$/i','/^$/i','EventIndex');
		$this->AddEventPreg('/^ajax-search$/i','EventAjaxSearch');

		$this->AddEventPreg('/^country$/i','/^\d+$/i','/^(page(\d+))?$/i','EventCountry');
		$this->AddEventPreg('/^city$/i','/^\d+$/i','/^(page(\d+))?$/i','EventCity');
	}
		
	
	/**********************************************************************************
	 ************************ РЕАЛИЗАЦИЯ ЭКШЕНА ***************************************
	 **********************************************************************************
	 */

	/**
	 * Поиск пользователей
	 */
	protected function EventAjaxSearch() {
		$this->Viewer_SetResponseAjax('json');

		if ($sTitle=getRequest('user_login') and is_string($sTitle)) {
			$sTitle=str_replace('%','',$sTitle);
		}
		if (!$sTitle) {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'));
			return;
		}
		if (getRequest('isPrefix')) {
			$sTitle.='%';
		} elseif (getRequest('isPostfix')) {
			$sTitle='%'.$sTitle;
		} else {
			$sTitle='%'.$sTitle.'%';
		}

		$aResult=$this->User_GetUsersByFilter(array('activate' => 1,'login'=>$sTitle),array('user_rating'=>'desc'),1,50);

		$oViewer=$this->Viewer_GetLocalViewer();
		$oViewer->Assign('aUsersList',$aResult['collection']);
		$oViewer->Assign('sUserListEmpty',$this->Lang_Get('user_search_empty'));
		$this->Viewer_AssignAjax('sText',$oViewer->Fetch("user_list.tpl"));
	}
	/**
	 * Показывает юзеров по стране
	 *
	 */
	protected function EventCountry() {		
		if (!($oCountry=$this->Geo_GetCountryById($this->getParam(0)))) {
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
		 * Получаем список вязей пользователей со страной
		 */					
		$aResult=$this->Geo_GetTargets(array('country_id'=>$oCountry->getId(),'target_type'=>'user'),$iPage,Config::Get('module.user.per_page'));
		$aUsersId=array();
		foreach($aResult['collection'] as $oTarget) {
			$aUsersId[]=$oTarget->getTargetId();
		}
		$aUsersCountry=$this->User_GetUsersAdditionalData($aUsersId);
		/**
		 * Формируем постраничность
		 */			
		$aPaging=$this->Viewer_MakePaging($aResult['count'],$iPage,Config::Get('module.user.per_page'),4,Router::GetPath('people').$this->sCurrentEvent.'/'.$oCountry->getId());
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
		if (!($oCity=$this->Geo_GetCityById($this->getParam(0)))) {
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
		$aResult=$this->Geo_GetTargets(array('city_id'=>$oCity->getId(),'target_type'=>'user'),$iPage,Config::Get('module.user.per_page'));
		$aUsersId=array();
		foreach($aResult['collection'] as $oTarget) {
			$aUsersId[]=$oTarget->getTargetId();
		}
		$aUsersCity=$this->User_GetUsersAdditionalData($aUsersId);
		/**
		 * Формируем постраничность
		 */			
		$aPaging=$this->Viewer_MakePaging($aResult['count'],$iPage,Config::Get('module.user.per_page'),4,Router::GetPath('people').$this->sCurrentEvent.'/'.$oCity->getId());
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
	 * Показываем юзеров
	 *
	 */
	protected function EventIndex() {
		/**
		 * Получаем статистику
		 */
		$this->GetStats();
		/**
		 * По какому полю сортировать
		 */
		$sOrder='user_rating';
		if (getRequest('order')) {
			$sOrder=getRequest('order');
		}
		/**
		 * В каком направлении сортировать
		 */
		$sOrderWay='desc';
		if (getRequest('order_way')) {
			$sOrderWay=getRequest('order_way');
		}
		$aFilter=array(
			'activate' => 1
		);
		/**
		 * Передан ли номер страницы
		 */
		$iPage=$this->GetParamEventMatch(0,2) ? $this->GetParamEventMatch(0,2) : 1;
		/**
		 * Получаем список юзеров
		 */
		$aResult=$this->User_GetUsersByFilter($aFilter,array($sOrder=>$sOrderWay),$iPage,Config::Get('module.user.per_page'));
		$aUsers=$aResult['collection'];
		/**
		 * Формируем постраничность
		 */
		$aPaging=$this->Viewer_MakePaging($aResult['count'],$iPage,Config::Get('module.user.per_page'),4,Router::GetPath('people').'index',array('order'=>$sOrder,'order_way'=>$sOrderWay));
		/**
		 * Получаем алфавитный указатель на список пользователей
		 */
		$aPrefixUser=$this->User_GetGroupPrefixUser(1);
		/**
		 * Загружаем переменные в шаблон
		 */
		$this->Viewer_Assign('aPaging',$aPaging);
		$this->Viewer_Assign('aUsersRating',$aUsers);
		$this->Viewer_Assign('aPrefixUser',$aPrefixUser);
		$this->Viewer_Assign("sUsersOrder",htmlspecialchars($sOrder));
		$this->Viewer_Assign("sUsersOrderWay",htmlspecialchars($sOrderWay));
		$this->Viewer_Assign("sUsersOrderWayNext",htmlspecialchars($sOrderWay=='desc' ? 'asc' : 'desc'));
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
	 * Выполняется при завершении работы экшена
	 *
	 */
	public function EventShutdown() {
		/**
		 * Загружаем в шаблон необходимые переменные
		 */
		$this->Viewer_Assign('sMenuHeadItemSelect',$this->sMenuHeadItemSelect);
	}
}
?>