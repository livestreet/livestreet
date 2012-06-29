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
 * Экшен обработки статистики юзеров, т.е. УРЛа вида /people/
 *
 * @package actions
 * @since 1.0
 */
class ActionPeople extends Action {
	/**
	 * Главное меню
	 *
	 * @var string
	 */
	protected $sMenuHeadItemSelect='people';
	/**
	 * Меню
	 *
	 * @var string
	 */
	protected $sMenuItemSelect='all';

	/**
	 * Инициализация
	 *
	 */
	public function Init() {
		/**
		 * Устанавливаем title страницы
		 */
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
	 * Поиск пользователей по логину
	 */
	protected function EventAjaxSearch() {
		/**
		 * Устанавливаем формат Ajax ответа
		 */
		$this->Viewer_SetResponseAjax('json');
		/**
		 * Получаем из реквеста первые быквы для поиска пользователей по логину
		 */
		$sTitle=getRequest('user_login');
		if (is_string($sTitle) and mb_strlen($sTitle,'utf-8')) {
			$sTitle=str_replace(array('_','%'),array('\_','\%'),$sTitle);
		} else {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'));
			return;
		}
		/**
		 * Как именно искать: совпадение в любой частилогина, или только начало или конец логина
		 */
		if (getRequest('isPrefix')) {
			$sTitle.='%';
		} elseif (getRequest('isPostfix')) {
			$sTitle='%'.$sTitle;
		} else {
			$sTitle='%'.$sTitle.'%';
		}
		/**
		 * Ищем пользователей
		 */
		$aResult=$this->User_GetUsersByFilter(array('activate' => 1,'login'=>$sTitle),array('user_rating'=>'desc'),1,50);
		/**
		 * Формируем ответ
		 */
		$oViewer=$this->Viewer_GetLocalViewer();
		$oViewer->Assign('aUsersList',$aResult['collection']);
		$oViewer->Assign('oUserCurrent',$this->User_GetUserCurrent());
		$oViewer->Assign('sUserListEmpty',$this->Lang_Get('user_search_empty'));
		$this->Viewer_AssignAjax('sText',$oViewer->Fetch("user_list.tpl"));
	}
	/**
	 * Показывает юзеров по стране
	 *
	 */
	protected function EventCountry() {
		$this->sMenuItemSelect='country';
		/**
		 * Страна существует?
		 */
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
		$aPaging=$this->Viewer_MakePaging($aResult['count'],$iPage,Config::Get('module.user.per_page'),Config::Get('pagination.pages.count'),Router::GetPath('people').$this->sCurrentEvent.'/'.$oCountry->getId());
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
		$this->sMenuItemSelect='city';
		/**
		 * Город существует?
		 */
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
		$aPaging=$this->Viewer_MakePaging($aResult['count'],$iPage,Config::Get('module.user.per_page'),Config::Get('pagination.pages.count'),Router::GetPath('people').$this->sCurrentEvent.'/'.$oCity->getId());
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
		$this->sMenuItemSelect='online';
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
		$this->sMenuItemSelect='new';
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
		$aPaging=$this->Viewer_MakePaging($aResult['count'],$iPage,Config::Get('module.user.per_page'),Config::Get('pagination.pages.count'),Router::GetPath('people').'index',array('order'=>$sOrder,'order_way'=>$sOrderWay));
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
		$this->Viewer_Assign('sMenuItemSelect',$this->sMenuItemSelect);
	}
}
?>