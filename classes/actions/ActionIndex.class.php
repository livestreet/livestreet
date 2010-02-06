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
 * Обработка главной страницы, т.е. УРЛа вида /index/
 *
 */
class ActionIndex extends Action {
	/**
	 * Главное меню
	 *
	 * @var unknown_type
	 */
	protected $sMenuHeadItemSelect='blog';
	/**
	 * Меню
	 *
	 * @var unknown_type
	 */
	protected $sMenuItemSelect='index';
	/**
	 * Субменю
	 *
	 * @var unknown_type
	 */
	protected $sMenuSubItemSelect='good';
	/**
	 * Число новых топиков
	 *
	 * @var unknown_type
	 */
	protected $iCountTopicsNew=0;
	/**
	 * Число новых топиков в коллективных блогах
	 *
	 * @var unknown_type
	 */
	protected $iCountTopicsCollectiveNew=0;
	/**
	 * Число новых топиков в персональных блогах
	 *
	 * @var unknown_type
	 */
	protected $iCountTopicsPersonalNew=0;
	
	/**
	 * Инициализация
	 *
	 */
	public function Init() {
		/**
		 * Подсчитываем новые топики
		 */
		$this->iCountTopicsCollectiveNew=$this->Topic_GetCountTopicsCollectiveNew();
		$this->iCountTopicsPersonalNew=$this->Topic_GetCountTopicsPersonalNew();
		$this->iCountTopicsNew=$this->iCountTopicsCollectiveNew+$this->iCountTopicsPersonalNew;
	}
	/**
	 * Регистрация евентов
	 *
	 */
	protected function RegisterEvent() {		
		$this->AddEventPreg('/^(page(\d+))?$/i','EventIndex');				
	}
		
	
	/**********************************************************************************
	 ************************ РЕАЛИЗАЦИЯ ЭКШЕНА ***************************************
	 **********************************************************************************
	 */
	
	/**
	 * Реализация евента
	 *
	 */
	protected function EventIndex() {
		$this->Viewer_SetHtmlRssAlternate(Router::GetPath('rss').'index/',Config::Get('view.name'));	
		/**
		 * Меню
		 */
		$this->sMenuSubItemSelect='good';
		/**
		 * Передан ли номер страницы
		 */
		$iPage=$this->GetEventMatch(2) ? $this->GetEventMatch(2) : 1;
		/**
		 * Получаем список топиков
		 */					
		$aResult=$this->Topic_GetTopicsGood($iPage,Config::Get('module.topic.per_page'));			
		$aTopics=$aResult['collection'];	
		/**
		 * Формируем постраничность
		 */
		$aPaging=$this->Viewer_MakePaging($aResult['count'],$iPage,Config::Get('module.topic.per_page'),4,Router::GetPath('index'));
		/**
		 * Загружаем переменные в шаблон
		 */
		$this->Viewer_Assign('aTopics',$aTopics);
		$this->Viewer_Assign('aPaging',$aPaging);		
		/**
		 * Устанавливаем шаблон вывода
		 */
		$this->SetTemplateAction('index');
	}	
	/**
	 * При завершении экшена загружаем переменные в шаблон
	 *
	 */
	public function EventShutdown() {
		$this->Viewer_Assign('sMenuHeadItemSelect',$this->sMenuHeadItemSelect);
		$this->Viewer_Assign('sMenuItemSelect',$this->sMenuItemSelect);
		$this->Viewer_Assign('sMenuSubItemSelect',$this->sMenuSubItemSelect);
		$this->Viewer_Assign('iCountTopicsNew',$this->iCountTopicsNew);
		$this->Viewer_Assign('iCountTopicsCollectiveNew',$this->iCountTopicsCollectiveNew);
		$this->Viewer_Assign('iCountTopicsPersonalNew',$this->iCountTopicsPersonalNew);	
	}
}
?>