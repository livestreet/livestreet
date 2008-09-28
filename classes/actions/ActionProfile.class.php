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
 * Обрабатывает профайл юзера, т.е. УРЛ вида /profile/login/
 *
 */
class ActionProfile extends Action {
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
	protected $oUserProfile;
	
	public function Init() {		
	}
	
	protected function RegisterEvent() {						
	}
			
	/**********************************************************************************
	 ************************ РЕАЛИЗАЦИЯ ЭКШЕНА ***************************************
	 **********************************************************************************
	 */
	
	/**
	 * Определяет что показать
	 *
	 * @return unknown
	 */
	protected function EventNotFound() {	
		/**
		 * Получаем логин из УРЛа
		 */
		$this->sUserLogin=$this->sCurrentEvent;				
		/**
		 * Проверяем есть ли такой юзер
		 */
		if (!$this->sUserLogin or !($this->oUserProfile=$this->User_GetUserByLogin($this->sUserLogin))) {			
			return parent::EventNotFound();
		}	
		$iCountTopicFavourite=$this->Topic_GetCountTopicsFavouriteByUserId($this->oUserProfile->getId());
		$iCountTopicUser=$this->Topic_GetCountTopicsPersonalByUser($this->oUserProfile->getId(),1);
		$iCountCommentUser=$this->Comment_GetCountCommentsByUserId($this->oUserProfile->getId());
		$this->Viewer_Assign('oUserProfile',$this->oUserProfile);		
		$this->Viewer_Assign('iCountTopicUser',$iCountTopicUser);		
		$this->Viewer_Assign('iCountCommentUser',$iCountCommentUser);		
		$this->Viewer_Assign('iCountTopicFavourite',$iCountTopicFavourite);
		/**
		 * Определяем что запустить
		 */				
		$sParam=$this->GetParam(0);
		if ($sParam=='whois' or $sParam=='') {
			// инфу профиля
			return $this->ShowWhois();
		} elseif ($sParam=='favourites') {
			// избранное
			$this->ShowFavourite();
		} elseif ($sParam=='tags') {
			// теги
			$this->ShowTags();
		} else {						
			return parent::EventNotFound();
		}		
	}
	/**
	 * Выводит список избранноего юзера
	 *
	 */
	protected function ShowFavourite() {		
		/**
		 * Передан ли номер страницы
		 */
		if (preg_match("/^page(\d+)$/i",$this->GetParam(1),$aMatch)) {			
			$iPage=$aMatch[1];
		} else {
			$iPage=1;
		}
		/**
		 * Получаем список избранных топиков
		 */
		$iCount=0;			
		$aResult=$this->Topic_GetTopicsFavouriteByUserId($this->oUserProfile->getId(),$iCount,$iPage,BLOG_TOPIC_PER_PAGE);			
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
		$this->Viewer_AddHtmlTitle('Профиль '.$this->oUserProfile->getLogin());
		$this->Viewer_AddHtmlTitle('Избранное');
		/**
		 * Устанавливаем шаблон вывода
		 */
		$this->SetTemplateAction('favourites');
	}
	/**
	 * Показывает инфу профиля
	 *
	 */
	protected function ShowWhois() {
		/**
		 * Получаем список друзей
		 */
		$aUsersFrend=$this->User_GetUsersFrend($this->oUserProfile->getId());
		/**
		 * Получаем список тех у кого в друзьях
		 */
		$aUsersSelfFrend=$this->User_GetUsersSelfFrend($this->oUserProfile->getId());
		/**
		 * Получаем список блогов в которых состоит юзер
		 */
		$aBlogsUser=$this->Blog_GetRelationBlogUsersByUserId($this->oUserProfile->getId());	
		/**
		 * Получаем список блогов которые создал юзер
		 */
		$aBlogsOwner=$this->Blog_GetBlogsByOwnerId($this->oUserProfile->getId());	
		/**
		 * Загружаем переменные в шаблон
		 */
		$this->Viewer_Assign('aBlogsUser',$aBlogsUser);
		$this->Viewer_Assign('aBlogsOwner',$aBlogsOwner);
		$this->Viewer_Assign('aUsersFrend',$aUsersFrend);
		$this->Viewer_Assign('aUsersSelfFrend',$aUsersSelfFrend);
		$this->Viewer_AddHtmlTitle('Профиль '.$this->oUserProfile->getLogin());
		$this->Viewer_AddHtmlTitle('Whois');
		/**
		 * Устанавливаем шаблон вывода
		 */
		$this->SetTemplateAction('whois');				
	}	
	/**
	 * Выводит список тегов котрые использовал юзер при создании топиков
	 *
	 */
	protected function ShowTags() {
		/**
		 * Получаем список тегов
		 */
		$aTags=$this->Topic_GetTopicTagsByUserId($this->oUserProfile->getId(),100);
		/**
		 * Расчитываем логарифмическое облако тегов
		 */
		if ($aTags) {
			$iMinSize=15; // минимальный размер шрифта
			$iMaxSize=40; // максимальный размер шрифта
			$iSizeRange=$iMaxSize-$iMinSize;
			
			$iMin=10000;
			$iMax=0;
			foreach ($aTags as $oTag) {
				if ($iMax<$oTag->getCount()) {
					$iMax=$oTag->getCount();
				}
				if ($iMin>$oTag->getCount()) {
					$iMin=$oTag->getCount();
				}
			}			
			
			$iMinCount=log($iMin+1);
			$iMaxCount=log($iMax+1);
			$iCountRange=$iMaxCount-$iMinCount;
			if ($iCountRange==0) {
				$iCountRange=1;
			}
			foreach ($aTags as $oTag) {
				$iTagSize=$iMinSize+(log($oTag->getCount()+1)-$iMinCount)*($iSizeRange/$iCountRange);
				$oTag->setSize(round($iTagSize)); // результирующий размер шрифта для тега
			}
			$this->Viewer_Assign("aTags",$aTags);
		}
		$this->Viewer_AddHtmlTitle('Профиль '.$this->oUserProfile->getLogin());
		$this->Viewer_AddHtmlTitle('Метки');
		/**
		 * Устанавливаем шаблон вывода
		 */
		$this->SetTemplateAction('tags');
	}
}
?>