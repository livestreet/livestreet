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
	}
		
	
	/**********************************************************************************
	 ************************ РЕАЛИЗАЦИЯ ЭКШЕНА ***************************************
	 **********************************************************************************
	 */
	
	/**
	 * Выводит список топиков которые написал юзер
	 *
	 * @param unknown_type $sPage
	 */
	protected function ShowBlog($sPage) {
		/**
		 * Передан ли номер страницы
		 */	
		if (preg_match("/^page(\d+)$/i",$sPage,$aMatch)) {			
			$iPage=$aMatch[1];
		} else {
			$iPage=1;
		}
		/**
		 * Получаем список топиков
		 */
		$iCount=0;			
		$aResult=$this->Topic_GetTopicsPersonalByUser($this->oUserProfile->getId(),1,$iCount,$iPage,BLOG_TOPIC_PER_PAGE);	
		$aTopics=$aResult['collection'];	
		/**
		 * Формируем постраничность
		 */				
		$aPaging=$this->Viewer_MakePaging($aResult['count'],$iPage,BLOG_TOPIC_PER_PAGE,4,DIR_WEB_ROOT.'/my/'.$this->oUserProfile->getLogin());		
		/**
		 * Загружаем переменные в шаблон
		 */			
		$this->Viewer_Assign('aPaging',$aPaging);			
		$this->Viewer_Assign('aTopics',$aTopics);
		$this->Viewer_AddHtmlTitle('Публикации '.$this->oUserProfile->getLogin());
		$this->Viewer_AddHtmlTitle('Блог');
		/**
		 * Устанавливаем шаблон вывода
		 */
		$this->SetTemplateAction('blog');		
	}
	
	/**
	 * Выводит список комментариев которые написал юзер
	 *
	 * @param unknown_type $sPage
	 */
	protected function ShowComment($sPage) {
		/**
		 * Передан ли номер страницы
		 */	
		if (preg_match("/^page(\d+)$/i",$sPage,$aMatch)) {			
			$iPage=$aMatch[1];
		} else {
			$iPage=1;
		}
		/**
		 * Получаем список комментов
		 */
		$iCount=0;			
		$aResult=$this->Comment_GetCommentsByUserId($this->oUserProfile->getId(),$iCount,$iPage,BLOG_COMMENT_PER_PAGE);	
		$aComments=$aResult['collection'];		
		/**
		 * Формируем постраничность
		 */			
		$aPaging=$this->Viewer_MakePaging($aResult['count'],$iPage,BLOG_COMMENT_PER_PAGE,4,DIR_WEB_ROOT.'/my/'.$this->oUserProfile->getLogin().'/comment');		
		/**
		 * Загружаем переменные в шаблон
		 */		
		$this->Viewer_Assign('aPaging',$aPaging);			
		$this->Viewer_Assign('aComments',$aComments);	
		$this->Viewer_AddHtmlTitle('Публикации '.$this->oUserProfile->getLogin());
		$this->Viewer_AddHtmlTitle('Комментарии');
		/**
		 * Устанавливаем шаблон вывода
		 */	
		$this->SetTemplateAction('comment');		
	}
	
	/**
	 * Определяет какой обработчик запустить, т.е. по сути что показать то? :)
	 *
	 * @return unknown
	 */
	protected function EventNotFound() {	
		/**
		 * Получаем логин юзера из URL'а
		 */
		$this->sUserLogin=$this->sCurrentEvent;		
		/**
		 * Проверяем есть ли такой юзер
		 */
		if (!$this->sUserLogin or !($this->oUserProfile=$this->User_GetUserByLogin($this->sUserLogin))) {			
			return parent::EventNotFound();
		}		
		$iCountTopicUser=$this->Topic_GetCountTopicsPersonalByUser($this->oUserProfile->getId(),1);
		$iCountCommentUser=$this->Comment_GetCountCommentsByUserId($this->oUserProfile->getId());
		$this->Viewer_Assign('oUserProfile',$this->oUserProfile);		
		$this->Viewer_Assign('iCountTopicUser',$iCountTopicUser);		
		$this->Viewer_Assign('iCountCommentUser',$iCountCommentUser);		
		/**
		 * Для блога		  
		 */
		if (is_null($this->getParam(0)) or preg_match("/^page(\d+)$/i",$this->getParam(0))) {
			return $this->ShowBlog($this->getParam(0));
		}
		if ($this->GetParam(0)=='blog') {
			if ((is_null($this->getParam(1)) or preg_match("/^page(\d+)$/i",$this->getParam(1)))) {
				return $this->ShowBlog($this->getParam(1));
			}
		}				
		/**
		 * Для комментов
		 */
		if ($this->GetParam(0)=='comment') {
			if ((is_null($this->getParam(1)) or preg_match("/^page(\d+)$/i",$this->getParam(1)))) {
				return $this->ShowComment($this->getParam(1));
			}
		}		
		/**
		 * Иначе страницу ошибки
		 */
		return parent::EventNotFound();	
	}
	
	
}
?>