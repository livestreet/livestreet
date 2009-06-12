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
 * Обработка УРЛа вида /my/
 *
 */
class ActionMy extends Action {
	/**
	 * Логин юзера из УРЛа
	 *
	 * @var unknown_type
	 */
	protected $sUserLogin=null;
	/**
	 * Объект юзера чей профиль мы смотрим
	 *
	 * @var unknown_type
	 */
	protected $oUserProfile=null;
	
	public function Init() {		
	}
	
	protected function RegisterEvent() {	
		$this->AddEventPreg('/^[\w\-\_]+$/i','/^(page(\d+))?$/i','EventTopics');						
		$this->AddEventPreg('/^[\w\-\_]+$/i','/^blog$/i','/^(page(\d+))?$/i','EventTopics');						
		$this->AddEventPreg('/^[\w\-\_]+$/i','/^comment$/i','/^(page(\d+))?$/i','EventComments');						
	}
		
	
	/**********************************************************************************
	 ************************ РЕАЛИЗАЦИЯ ЭКШЕНА ***************************************
	 **********************************************************************************
	 */
	
	/**
	 * Выводит список топиков которые написал юзер
	 *	 
	 */
	protected function EventTopics() {
		/**
		 * Получаем логин из УРЛа
		 */
		$sUserLogin=$this->sCurrentEvent;					
		/**
		 * Проверяем есть ли такой юзер
		 */		
		if (!($this->oUserProfile=$this->User_GetUserByLogin($sUserLogin))) {			
			return parent::EventNotFound();
		}
		/**
		 * Передан ли номер страницы
		 */			
		if ($this->GetParamEventMatch(0,0)=='blog') {			
			$iPage=$this->GetParamEventMatch(1,2) ? $this->GetParamEventMatch(1,2) : 1;	
		} else {
			$iPage=$this->GetParamEventMatch(0,2) ? $this->GetParamEventMatch(0,2) : 1;	
		}		
		/**
		 * Получаем список топиков
		 */					
		$aResult=$this->Topic_GetTopicsPersonalByUser($this->oUserProfile->getId(),1,$iPage,BLOG_TOPIC_PER_PAGE);	
		$aTopics=$aResult['collection'];	
		/**
		 * Формируем постраничность
		 */				
		$aPaging=$this->Viewer_MakePaging($aResult['count'],$iPage,BLOG_TOPIC_PER_PAGE,4,DIR_WEB_ROOT.'/'.ROUTE_PAGE_MY.'/'.$this->oUserProfile->getLogin());		
		/**
		 * Загружаем переменные в шаблон
		 */			
		$this->Viewer_Assign('aPaging',$aPaging);			
		$this->Viewer_Assign('aTopics',$aTopics);
		$this->Viewer_AddHtmlTitle($this->Lang_Get('user_menu_publication').' '.$this->oUserProfile->getLogin());
		$this->Viewer_AddHtmlTitle($this->Lang_Get('user_menu_publication_blog'));
		$this->Viewer_SetHtmlRssAlternate(DIR_WEB_ROOT.'/'.ROUTE_PAGE_RSS.'/log/'.$this->oUserProfile->getLogin().'/',$this->oUserProfile->getLogin());
		/**
		 * Устанавливаем шаблон вывода
		 */
		$this->SetTemplateAction('blog');		
	}
	
	/**
	 * Выводит список комментариев которые написал юзер
	 *	 
	 */
	protected function EventComments() {
		/**
		 * Получаем логин из УРЛа
		 */
		$sUserLogin=$this->sCurrentEvent;					
		/**
		 * Проверяем есть ли такой юзер
		 */		
		if (!($this->oUserProfile=$this->User_GetUserByLogin($sUserLogin))) {			
			return parent::EventNotFound();
		}
		/**
		 * Передан ли номер страницы
		 */	
		$iPage=$this->GetParamEventMatch(1,2) ? $this->GetParamEventMatch(1,2) : 1;
		/**
		 * Получаем список комментов
		 */
		$iCount=0;			
		$aResult=$this->Comment_GetCommentsByUserId($this->oUserProfile->getId(),$iCount,$iPage,BLOG_COMMENT_PER_PAGE);	
		$aComments=$aResult['collection'];		
		/**
		 * Формируем постраничность
		 */			
		$aPaging=$this->Viewer_MakePaging($aResult['count'],$iPage,BLOG_COMMENT_PER_PAGE,4,DIR_WEB_ROOT.'/'.ROUTE_PAGE_MY.'/'.$this->oUserProfile->getLogin().'/comment');		
		/**
		 * Загружаем переменные в шаблон
		 */		
		$this->Viewer_Assign('aPaging',$aPaging);			
		$this->Viewer_Assign('aComments',$aComments);	
		$this->Viewer_AddHtmlTitle($this->Lang_Get('user_menu_publication').' '.$this->oUserProfile->getLogin());
		$this->Viewer_AddHtmlTitle($this->Lang_Get('user_menu_publication_comment'));
		/**
		 * Устанавливаем шаблон вывода
		 */	
		$this->SetTemplateAction('comment');		
	}	
	/**
	 * Выполняется при завершении работы экшена
	 *
	 */
	public function EventShutdown() {	
		if (!$this->oUserProfile)	 {
			return ;
		}
		/**
		 * Загружаем в шаблон необходимые переменные
		 */
		$iCountTopicUser=$this->Topic_GetCountTopicsPersonalByUser($this->oUserProfile->getId(),1);
		$iCountCommentUser=$this->Comment_GetCountCommentsByUserId($this->oUserProfile->getId());
		$this->Viewer_Assign('oUserProfile',$this->oUserProfile);		
		$this->Viewer_Assign('iCountTopicUser',$iCountTopicUser);		
		$this->Viewer_Assign('iCountCommentUser',$iCountCommentUser);
	}
}
?>