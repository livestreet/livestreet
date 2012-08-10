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
 * Экшен обработки УРЛа вида /comments/
 *
 * @package actions
 * @since 1.0
 */
<<<<<<< HEAD
class ActionBlogs extends Action {
	/**
	 * Инициализация
	 */
	public function Init() {
=======
class ActionBlogs extends Action {	
	
	public function Init() {		
>>>>>>> branch 'master' of git@github.com:1d10t/livestreet.git
		/**
		 * Загружаем в шаблон JS текстовки
		 */
		$this->Lang_AddLangJs(array(
								  'blog_join','blog_leave'
							  ));
	}
<<<<<<< HEAD
	/**
	 * Регистрируем евенты
	 */
	protected function RegisterEvent() {
		$this->AddEventPreg('/^(page([1-9]\d{0,5}))?$/i','EventShowBlogs');
=======
	
	protected function RegisterEvent() {	
		$this->AddEventPreg('/^(page(\d+))?$/i','EventShowBlogs');
>>>>>>> branch 'master' of git@github.com:1d10t/livestreet.git
		$this->AddEventPreg('/^ajax-search$/i','EventAjaxSearch');
	}


	/**********************************************************************************
	 ************************ РЕАЛИЗАЦИЯ ЭКШЕНА ***************************************
	 **********************************************************************************
<<<<<<< HEAD
	 */

	/**
	 * Поиск блогов по названию
	 */
	protected function EventAjaxSearch() {
		/**
		 * Устанавливаем формат Ajax ответа
		 */
		$this->Viewer_SetResponseAjax('json');
		/**
		 * Получаем из реквеста первые буквы блога
		 */
		if ($sTitle=getRequest('blog_title') and is_string($sTitle)) {
			$sTitle=str_replace('%','',$sTitle);
		}
		if (!$sTitle) {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'));
			return;
		}
		/**
		 * Ищем блоги
		 */
		$aResult=$this->Blog_GetBlogsByFilter(array('exclude_type' => 'personal','title'=>"%{$sTitle}%"),array('blog_title'=>'asc'),1,100);
		/**
		 * Формируем и возвращает ответ
		 */
		$oViewer=$this->Viewer_GetLocalViewer();
		$oViewer->Assign('aBlogs',$aResult['collection']);
		$oViewer->Assign('oUserCurrent',$this->User_GetUserCurrent());
		$oViewer->Assign('sBlogsEmptyList',$this->Lang_Get('blogs_search_empty'));
		$this->Viewer_AssignAjax('sText',$oViewer->Fetch("blog_list.tpl"));
	}
	/**
	 * Отображение списка блогов
	 */
	protected function EventShowBlogs() {
		/**
		 * По какому полю сортировать
		 */
		$sOrder='blog_rating';
		if (getRequest('order')) {
			$sOrder=(string)getRequest('order');
		}
		/**
		 * В каком направлении сортировать
		 */
		$sOrderWay='desc';
		if (getRequest('order_way')) {
			$sOrderWay=(string)getRequest('order_way');
		}
		/**
		 * Фильтр поиска блогов
		 */
		$aFilter=array(
			'exclude_type' => 'personal'
		);
=======
	 */	

	/**
	 * Поиск блогов
	 */
	protected function EventAjaxSearch() {
		$this->Viewer_SetResponseAjax('json');

		if ($sTitle=getRequest('blog_title') and is_string($sTitle)) {
			$sTitle=str_replace('%','',$sTitle);
		}
		if (!$sTitle) {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'));
			return;
		}

		$aResult=$this->Blog_GetBlogsByFilter(array('exclude_type' => 'personal','title'=>"%{$sTitle}%"),array('blog_title'=>'asc'),1,100);

		$oViewer=$this->Viewer_GetLocalViewer();
		$oViewer->Assign('aBlogs',$aResult['collection']);
		$oViewer->Assign('sBlogsEmptyList',$this->Lang_Get('blogs_search_empty'));
		$this->Viewer_AssignAjax('sText',$oViewer->Fetch("blog_list.tpl"));
	}
	
	protected function EventShowBlogs() {
		/**
		 * По какому полю сортировать
		 */
		$sOrder='blog_rating';
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
			'exclude_type' => 'personal'
		);

>>>>>>> branch 'master' of git@github.com:1d10t/livestreet.git
		/**
		 * Передан ли номер страницы
		 */
		$iPage=	preg_match("/^\d+$/i",$this->GetEventMatch(2)) ? $this->GetEventMatch(2) : 1;
		/**
		 * Получаем список блогов
		 */
<<<<<<< HEAD
		$aResult=$this->Blog_GetBlogsByFilter($aFilter,array($sOrder=>$sOrderWay),$iPage,Config::Get('module.blog.per_page'));
		$aBlogs=$aResult['collection'];
=======

		$aResult=$this->Blog_GetBlogsByFilter($aFilter,array($sOrder=>$sOrderWay),$iPage,Config::Get('module.blog.per_page'));
		$aBlogs=$aResult['collection'];				
>>>>>>> branch 'master' of git@github.com:1d10t/livestreet.git
		/**
		 * Формируем постраничность
<<<<<<< HEAD
		 */
		$aPaging=$this->Viewer_MakePaging($aResult['count'],$iPage,Config::Get('module.blog.per_page'),Config::Get('pagination.pages.count'),Router::GetPath('blogs'),array('order'=>$sOrder,'order_way'=>$sOrderWay));
=======
		 */		
		$aPaging=$this->Viewer_MakePaging($aResult['count'],$iPage,Config::Get('module.blog.per_page'),4,Router::GetPath('blogs'),array('order'=>$sOrder,'order_way'=>$sOrderWay));
>>>>>>> branch 'master' of git@github.com:1d10t/livestreet.git
		/**
		 * Загружаем переменные в шаблон
		 */
		$this->Viewer_Assign('aPaging',$aPaging);
		$this->Viewer_Assign("aBlogs",$aBlogs);
		$this->Viewer_Assign("sBlogOrder",htmlspecialchars($sOrder));
		$this->Viewer_Assign("sBlogOrderWay",htmlspecialchars($sOrderWay));
		$this->Viewer_Assign("sBlogOrderWayNext",htmlspecialchars($sOrderWay=='desc' ? 'asc' : 'desc'));
<<<<<<< HEAD
		/**
		 * Устанавливаем title страницы
		 */
=======
>>>>>>> branch 'master' of git@github.com:1d10t/livestreet.git
		$this->Viewer_AddHtmlTitle($this->Lang_Get('blog_menu_all_list'));
		/**
		 * Устанавливаем шаблон вывода
		 */
<<<<<<< HEAD
		$this->SetTemplateAction('index');
=======
		$this->SetTemplateAction('index');				
>>>>>>> branch 'master' of git@github.com:1d10t/livestreet.git
	}
}
?>
