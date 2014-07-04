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
class ActionBlogs extends Action {
	/**
	 * Инициализация
	 */
	public function Init() {
		/**
		 * Загружаем в шаблон JS текстовки
		 */
		$this->Lang_AddLangJs(array(
								  'blog.join.join','blog.join.leave'
							  ));
		/**
		 * Устанавливаем title страницы
		 */
		$this->Viewer_AddHtmlTitle($this->Lang_Get('blog_menu_all_list'));
	}
	/**
	 * Регистрируем евенты
	 */
	protected function RegisterEvent() {
		$this->AddEventPreg('/^(page([1-9]\d{0,5}))?$/i','EventShowBlogs');
		$this->AddEventPreg('/^ajax-search$/i','EventAjaxSearch');
	}


	/**********************************************************************************
	 ************************ РЕАЛИЗАЦИЯ ЭКШЕНА ***************************************
	 **********************************************************************************
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
		 * Фильтр
		 */
		$aFilter=array(
			'exclude_type' => 'personal',
		);
		$sOrderWay=in_array(getRequestStr('order'),array('desc','asc')) ? getRequestStr('order') : 'desc';
		$sOrderField=in_array(getRequestStr('sort_by'),array('blog_id','blog_title','blog_rating','blog_count_user','blog_count_topic')) ? getRequestStr('sort_by') : 'blog_rating';
		if (is_numeric(getRequestStr('pageNext')) and getRequestStr('pageNext')>0) {
			$iPage=getRequestStr('pageNext');
		} else {
			$iPage=1;
		}
		/**
		 * Получаем из реквеста первые буквы блога
		 */
		if ($sTitle=getRequestStr('sText')) {
			$sTitle=str_replace('%','',$sTitle);
		} else {
			$sTitle='';
		}
		if ($sTitle) {
			$aFilter['title']="%{$sTitle}%";
		}
		/**
		 * Категории
		 */
		if (getRequestStr('category') and $oCategory=$this->Blog_GetCategoryById(getRequestStr('category'))) {
			/**
			 * Получаем все дочерние категории
			 */
			$aCategoriesId=$this->Blog_GetChildrenCategoriesById($oCategory->getId(),true);
			$aCategoriesId[]=$oCategory->getId();
			$aFilter['category_id']=$aCategoriesId;
		}
		/**
		 * Тип
		 */
		if (in_array(getRequestStr('type'),array('open','close'))) {
			$aFilter['type']=getRequestStr('type');
		}
		/**
		 * Ищем блоги
		 */
		$aResult=$this->Blog_GetBlogsByFilter($aFilter,array($sOrderField=>$sOrderWay),$iPage,Config::Get('module.blog.per_page'));
		$bHideMore=$iPage*Config::Get('module.blog.per_page')>=$aResult['count'];
		/**
		 * Формируем и возвращает ответ
		 */
		$oViewer=$this->Viewer_GetLocalViewer();
		$oViewer->Assign('aBlogs',$aResult['collection']);
		$oViewer->Assign('oUserCurrent',$this->User_GetUserCurrent());
		$oViewer->Assign('sBlogsEmptyList',$this->Lang_Get('search.alerts.empty'));
		$oViewer->Assign('bUseMore', true);
		$oViewer->Assign('bHideMore', $bHideMore);
		$oViewer->Assign('iSearchCount', $aResult['count']);
		$this->Viewer_AssignAjax('sText',$oViewer->Fetch("components/blog/blog-list.tpl"));
		/**
		 * Для подгрузки
		 */
		$this->Viewer_AssignAjax('iCountLoaded',count($aResult['collection']));
		$this->Viewer_AssignAjax('pageNext',count($aResult['collection'])>0 ? $iPage+1 : $iPage);
		$this->Viewer_AssignAjax('bHideMore',$bHideMore);
	}
	/**
	 * Отображение списка блогов
	 */
	protected function EventShowBlogs() {
		/**
		 * Фильтр поиска блогов
		 */
		$aFilter=array(
			'exclude_type' => 'personal'
		);
		/**
		 * Получаем список блогов
		 */
		$aResult=$this->Blog_GetBlogsByFilter($aFilter,array('blog_rating'=>'desc'),1,Config::Get('module.blog.per_page'));
		$aBlogs=$aResult['collection'];
		/**
		 * Загружаем переменные в шаблон
		 */
		$this->Viewer_Assign("aBlogs",$aBlogs);
		$this->Viewer_Assign('iSearchCount', $aResult['count']);
		/**
		 * Устанавливаем шаблон вывода
		 */
		$this->SetTemplateAction('index');
	}
}