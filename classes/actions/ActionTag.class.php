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
 * Обрабатывает поиск по тегам
 *
 */
class ActionTag extends Action {	
	/**
	 * Главное меню
	 *
	 * @var unknown_type
	 */
	protected $sMenuHeadItemSelect='blog';
	/**
	 * Инициализация
	 *
	 */
	public function Init() {		
		/**
		 * Определяем какие блоки выводить
		 */
		$this->Viewer_AddBlocks('right',array('tags','stream'));	
	}
	
	protected function RegisterEvent() {	
		$this->AddEventPreg('/^.+$/i','/^(page(\d+))?$/i','EventTags');					
	}
		
	
	/**********************************************************************************
	 ************************ РЕАЛИЗАЦИЯ ЭКШЕНА ***************************************
	 **********************************************************************************
	 */
	
	/**
	 * Отображение топиков
	 *
	 */
	protected function EventTags() {
		/**
		 * Получаем тег из УРЛа
		 */
		$sTag=urldecode($this->sCurrentEvent);
		/**
		 * Передан ли номер страницы
		 */
		$iPage=$this->GetParamEventMatch(0,2) ? $this->GetParamEventMatch(0,2) : 1;		
		/**
		 * Получаем список топиков
		 */
		$iCount=0;			
		$aResult=$this->Topic_GetTopicsByTag($sTag,$iCount,$iPage,BLOG_TOPIC_PER_PAGE);		
		$aTopics=$aResult['collection'];	
		/**
		 * Формируем постраничность
		 */		
		$aPaging=$this->Viewer_MakePaging($aResult['count'],$iPage,BLOG_TOPIC_PER_PAGE,4,DIR_WEB_ROOT.'/tag/'.htmlspecialchars($sTag));
		/**
		 * Загружаем переменные в шаблон
		 */				
		$this->Viewer_Assign('aPaging',$aPaging);
		$this->Viewer_Assign('aTopics',$aTopics);
		$this->Viewer_Assign('sTag',$sTag);
		$this->Viewer_AddHtmlTitle($this->Lang_Get('tag_title'));
		$this->Viewer_AddHtmlTitle($sTag);
		$this->Viewer_SetHtmlRssAlternate(DIR_WEB_ROOT.'/'.ROUTE_PAGE_RSS.'/tag/'.$sTag.'/',$sTag);
		/**
		 * Устанавливаем шаблон вывода
		 */
		$this->SetTemplateAction('index');		
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