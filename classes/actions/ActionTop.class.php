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
 * Обрабатывает ТОПы
 *
 */
class ActionTop extends Action {
	/**
	 * Меню
	 *
	 * @var unknown_type
	 */
	protected $sMenuItemSelect='top';
	/**
	 * Субменю
	 *
	 * @var unknown_type
	 */
	protected $sMenuSubItemSelect='blog';
	
	/**
	 * Инициализация
	 *
	 */
	public function Init() {
		$this->SetDefaultEvent('topic');
		$this->Viewer_AddHtmlTitle($this->Lang_Get('top'));
	}
	/**
	 * Регистрация евентов
	 *
	 */
	protected function RegisterEvent() {		
		$this->AddEvent('blog','EventBlog');	
		$this->AddEvent('topic','EventTopic');	
		$this->AddEvent('comment','EventComment');
	}
		
	
	/**********************************************************************************
	 ************************ РЕАЛИЗАЦИЯ ЭКШЕНА ***************************************
	 **********************************************************************************
	 */
	
	/**
	 * Выводит ТОП блогов
	 *
	 */
	protected function EventBlog() {
		/**
		 * Меню
		 */
		$this->sMenuSubItemSelect='blog';	
		/**
		 * Получаем список блогов
		 */
		$aResult=$this->Blog_GetBlogsRating(1,20);
		$aBlogs=$aResult['collection'];	
		/**
		 * Загружаем переменные в шаблон
		 */		
		$this->Viewer_Assign('aBlogs',$aBlogs);	
		$this->Viewer_AddHtmlTitle($this->Lang_Get('top_blogs'));
	}	

	protected function EventTopic() {
		/**
		 * Меню
		 */
		$this->sMenuSubItemSelect='topic';	
		/**
		 * Определяем период ТОПа
		 */
		$iTimeDelta=$this->GetTimeDelta();				
		$sDate=date("Y-m-d H:00:00",time()-$iTimeDelta);	
		/**
		 * Получаем список топиков
		 */			
		$aTopics=$this->Topic_GetTopicsRatingByDate($sDate,20);
		/**
		 * Загружаем переменные в шаблон
		 */		
		$this->Viewer_Assign('aTopics',$aTopics);
		$this->Viewer_AddHtmlTitle($this->Lang_Get('top_topics'));					
	}
	
	protected function EventComment() {	
		/**
		 * Меню
		 */
		$this->sMenuSubItemSelect='comment';	
		/**
		 * Определяем период ТОПа
		 */
		$iTimeDelta=$this->GetTimeDelta();		
		$sDate=date("Y-m-d H:00:00",time()-$iTimeDelta);	
		/**
		 * Получаем список комментов
		 */
		$aComments=$this->Comment_GetCommentsRatingByDate($sDate,'topic',20);
		/**
		 * Загружаем переменные в шаблон
		 */				
		$this->Viewer_Assign('aComments',$aComments);
		$this->Viewer_AddHtmlTitle($this->Lang_Get('top_comments'));
	}
	/**
	 * Переводит параметр в нужный период времени
	 *
	 * @return unknown
	 */
	protected function GetTimeDelta() {
		$aDateParam=$this->GetParam(0);
		switch ($aDateParam) {
			case 'all':
				/**
				 * за последние 100 лет :)
				 */
				$iTimeDelta=60*60*24*350*100;
				break;
			case '30d':
				/**
				 * за последние 30 дней
				 */
				$iTimeDelta=60*60*24*30;
				break;
			case '7d':
				/**
				 * за последние 7 дней
				 */
				$iTimeDelta=60*60*24*7;
				break;
			case '24h':
				/**
				 * за последние 24 часа
				 */
				$iTimeDelta=60*60*24*1;
				break;
			default:
				$iTimeDelta=60*60*24*7;
				$this->SetParam(0,'7d');
				break;
		}
		return $iTimeDelta;
	}
	/**
	 * После завершшения экшена загружаем переменные
	 *
	 */
	public function EventShutdown() {
		/**
		 * Получаем список новых топиков
		 */
		$iCountTopicsCollectiveNew=$this->Topic_GetCountTopicsCollectiveNew();
		$iCountTopicsPersonalNew=$this->Topic_GetCountTopicsPersonalNew();
		$iCountTopicsNew=$iCountTopicsCollectiveNew+$iCountTopicsPersonalNew;
		/**
		 * Загружаем переменные в шаблон
		 */
		$this->Viewer_Assign('sMenuItemSelect',$this->sMenuItemSelect);
		$this->Viewer_Assign('sMenuSubItemSelect',$this->sMenuSubItemSelect);
		$this->Viewer_Assign('iCountTopicsCollectiveNew',$iCountTopicsCollectiveNew);
		$this->Viewer_Assign('iCountTopicsPersonalNew',$iCountTopicsPersonalNew);
		$this->Viewer_Assign('iCountTopicsNew',$iCountTopicsNew);
	}
}
?>