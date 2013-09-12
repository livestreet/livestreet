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

	protected $iPageCurrent=1;
	protected $sPageRoot=null;
	protected $aCategoriesCurrent=array();
	/**
	 * Инициализация
	 */
	public function Init() {
		/**
		 * Загружаем в шаблон JS текстовки
		 */
		$this->Lang_AddLangJs(array(
								  'blog_join','blog_leave'
							  ));
		$this->sPageRoot=Router::GetPath('blogs');
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
		$this->AddEventPreg('/^[\w\-\_]+$/i','EventShowBlogsCategory');
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
		 * Получаем из реквеста первые буквы блога
		 */
		if ($sTitle=getRequestStr('blog_title')) {
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
		$this->Viewer_AssignAjax('sText',$oViewer->Fetch("actions/ActionBlogs/blog_list.tpl"));
	}

	protected function EventShowBlogsCategory() {
		$aParams=$this->GetParams();
		if (count($aParams)) {
			if (preg_match('/^page(\d{1,5})$/i',$aParams[count($aParams)-1],$aMatch)) {
				$this->iPageCurrent=$aMatch[1];
				array_pop($aParams);
			}
		}
		$sUrlFull=join('/',$aParams);
		if ($sUrlFull!='') {
			$sUrlFull=$this->sCurrentEvent.'/'.$sUrlFull;
		} else {
			$sUrlFull=$this->sCurrentEvent;
		}

		/**
		 * Получаем текущую категорию
		 */
		if ($oCategory=$this->Blog_GetCategoryByUrlFull($sUrlFull)) {
			$this->Viewer_AddHtmlTitle($oCategory->getTitle());
			/**
			 * Получаем все дочерние категории
			 */
			$aCategoriesId=$this->Blog_GetChildrenCategoriesById($oCategory->getId(),true);
			$aCategoriesId[]=$oCategory->getId();

			$this->aCategoriesCurrent=$aCategoriesId;
			$this->sPageRoot=$oCategory->getUrlWeb();
			$this->Viewer_Assign('oBlogCategoryCurrent',$oCategory);
			$this->Viewer_Assign('sBlogsRootPage',$oCategory->getUrlWeb());
		} else {
			return $this->EventNotFound();
		}

		return $this->EventShowBlogs();
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
			$sOrder=getRequestStr('order');
		}
		/**
		 * В каком направлении сортировать
		 */
		$sOrderWay='desc';
		if (getRequest('order_way')) {
			$sOrderWay=getRequestStr('order_way');
		}
		/**
		 * Фильтр поиска блогов
		 */
		$aFilter=array(
			'exclude_type' => 'personal'
		);
		/**
		 * Передан ли номер страницы
		 */
		$iPage=$this->iPageCurrent;
		if ($this->GetEventMatch(2)) {
			$iPage=$this->GetEventMatch(2);
		}
		if ($this->aCategoriesCurrent) {
			$aFilter['category_id']=$this->aCategoriesCurrent;
		}
		/**
		 * Получаем список блогов
		 */
		$aResult=$this->Blog_GetBlogsByFilter($aFilter,array($sOrder=>$sOrderWay),$iPage,Config::Get('module.blog.per_page'));
		$aBlogs=$aResult['collection'];
		/**
		 * Формируем постраничность
		 */
		$aPaging=$this->Viewer_MakePaging($aResult['count'],$iPage,Config::Get('module.blog.per_page'),Config::Get('pagination.pages.count'),$this->sPageRoot,array('order'=>$sOrder,'order_way'=>$sOrderWay));
		/**
		 * Загружаем переменные в шаблон
		 */
		$this->Viewer_Assign('aPaging',$aPaging);
		$this->Viewer_Assign("aBlogs",$aBlogs);
		$this->Viewer_Assign("sBlogOrder",htmlspecialchars($sOrder));
		$this->Viewer_Assign("sBlogOrderWay",htmlspecialchars($sOrderWay));
		$this->Viewer_Assign("sBlogOrderWayNext",htmlspecialchars($sOrderWay=='desc' ? 'asc' : 'desc'));
		/**
		 * Устанавливаем шаблон вывода
		 */
		$this->SetTemplateAction('index');
	}
}
?>