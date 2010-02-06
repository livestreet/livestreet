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
 * Обработка персональных блогов, т.е. УРла вида /log/
 *
 */
class ActionPersonalBlog extends Action {
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
	protected $sMenuItemSelect='log';
	/**
	 * Субменю
	 *
	 * @var unknown_type
	 */
	protected $sMenuSubItemSelect='good';
	
	/**
	 * Инициализация
	 *
	 */
	public function Init() {		
		$this->SetDefaultEvent('good');
	}
	
	/**
	 * Регистрируем необходимые евенты
	 *
	 */
	protected function RegisterEvent() {
		$this->AddEventPreg('/^good$/i','/^(page(\d+))?$/i','EventTopics');
		$this->AddEvent('good','EventTopics');
		$this->AddEventPreg('/^bad$/i','/^(page(\d+))?$/i','EventTopics');
		$this->AddEventPreg('/^new$/i','/^(page(\d+))?$/i','EventTopics');
	}
		
	
	/**********************************************************************************
	 ************************ РЕАЛИЗАЦИЯ ЭКШЕНА ***************************************
	 **********************************************************************************
	 */
	/**
	 * Показ топиков
	 *
	 */
	protected function EventTopics() {
		$sShowType=$this->sCurrentEvent;
		/**
		 * Меню
		 */
		$this->sMenuSubItemSelect=$sShowType;
		/**
		 * Передан ли номер страницы
		 */
		$iPage=$this->GetParamEventMatch(0,2) ? $this->GetParamEventMatch(0,2) : 1;			
		/**
		 * Получаем список топиков
		 */					
		$aResult=$this->Topic_GetTopicsPersonal($iPage,Config::Get('module.topic.per_page'),$sShowType);
		$aTopics=$aResult['collection'];	
		/**
		 * Формируем постраничность
		 */
		$aPaging=$this->Viewer_MakePaging($aResult['count'],$iPage,Config::Get('module.topic.per_page'),4,Router::GetPath('personal_blog').$sShowType);
		/**
		 * Вызов хуков
		 */
		$this->Hook_Run('personal_show',array('sShowType'=>$sShowType));
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
	 * При завершении экшена загружаем в шаблон необходимые переменные
	 *
	 */
	public function EventShutdown() {
		/**
		 * Подсчитываем новые топики
		 */
		$iCountTopicsCollectiveNew=$this->Topic_GetCountTopicsCollectiveNew();
		$iCountTopicsPersonalNew=$this->Topic_GetCountTopicsPersonalNew();
		$iCountTopicsNew=$iCountTopicsCollectiveNew+$iCountTopicsPersonalNew;
		/**
		 * Загружаем переменные в шаблон
		 */
		$this->Viewer_Assign('sMenuHeadItemSelect',$this->sMenuHeadItemSelect);	
		$this->Viewer_Assign('sMenuItemSelect',$this->sMenuItemSelect);
		$this->Viewer_Assign('sMenuSubItemSelect',$this->sMenuSubItemSelect);
		$this->Viewer_Assign('iCountTopicsCollectiveNew',$iCountTopicsCollectiveNew);
		$this->Viewer_Assign('iCountTopicsPersonalNew',$iCountTopicsPersonalNew);
		$this->Viewer_Assign('iCountTopicsNew',$iCountTopicsNew);
	}
}
?>