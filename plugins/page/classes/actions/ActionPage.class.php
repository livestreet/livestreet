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

class PluginPage_ActionPage extends ActionPlugin {
	protected $sUserLogin=null;
	protected $aBadPageUrl=array('admin');

	public function Init() {
	}
	/**
	 * Регистрируем евенты
	 *
	 */
	protected function RegisterEvent() {
		$this->AddEvent('admin','EventAdmin');
		$this->AddEventPreg('/^[\w\-\_]*$/i','EventShowPage');
	}


	/**********************************************************************************
	************************ РЕАЛИЗАЦИЯ ЭКШЕНА ***************************************
	**********************************************************************************
	*/

	/**
	 * Отображение страницы
	 *
	 * @return unknown
	 */
	protected function EventShowPage() {
		if (!$this->sCurrentEvent) {
			/**
			 * Показывает дефолтную страницу
			 */
			//а это какая страница?
		}
		/**
		 * Составляем полный URL страницы для поиска по нему в БД
		 */
		$sUrlFull=join('/',$this->GetParams());
		if ($sUrlFull!='') {
			$sUrlFull=$this->sCurrentEvent.'/'.$sUrlFull;
		} else {
			$sUrlFull=$this->sCurrentEvent;
		}
		/**
		 * Ищем страничку в БД
		 */
		if (!($oPage=$this->PluginPage_Page_GetPageByUrlFull($sUrlFull,1))) {
			return $this->EventNotFound();
		}
		/**
		 * Заполняем HTML теги и SEO
		 */
		$this->Viewer_AddHtmlTitle($oPage->getTitle());
		if ($oPage->getSeoKeywords()) {
			$this->Viewer_SetHtmlKeywords($oPage->getSeoKeywords());
		}
		if ($oPage->getSeoDescription()) {
			$this->Viewer_SetHtmlDescription($oPage->getSeoDescription());
		}
		
		$this->Viewer_Assign('oPage',$oPage);
		/**
		 * Устанавливаем шаблон для вывода
		 */		
		$this->SetTemplateAction('page');
	}

	/**
	 * Админка статическими страницами
	 *
	 */
	protected function EventAdmin() {
		/**
		 * Если пользователь не авторизован и не админ, то выкидываем его
		 */
		$this->oUserCurrent=$this->User_GetUserCurrent();
		if (!$this->oUserCurrent or !$this->oUserCurrent->isAdministrator()) {			
			return $this->EventNotFound();
		}
		
		$this->Viewer_AddHtmlTitle($this->Lang_Get('page_admin'));
		/**
		 * Обработка создания новой странички
		 */
		if (isPost('submit_page_save')) {
			if (!getRequest('page_id')) {
				$this->SubmitAddPage();
			}
		}
		/**
		 * Обработка показа странички для редактирования
		 */
		if ($this->GetParam(0)=='edit') {
			if ($oPageEdit=$this->PluginPage_Page_GetPageById($this->GetParam(1))) {
				if (!isPost('submit_page_save')) {
					$_REQUEST['page_title']=$oPageEdit->getTitle();
					$_REQUEST['page_pid']=$oPageEdit->getPid();
					$_REQUEST['page_url']=$oPageEdit->getUrl();
					$_REQUEST['page_text']=$oPageEdit->getText();
					$_REQUEST['page_seo_keywords']=$oPageEdit->getSeoKeywords();
					$_REQUEST['page_seo_description']=$oPageEdit->getSeoDescription();
					$_REQUEST['page_active']=$oPageEdit->getActive();	
					$_REQUEST['page_main']=$oPageEdit->getMain();	
					$_REQUEST['page_sort']=$oPageEdit->getSort();	
					$_REQUEST['page_id']=$oPageEdit->getId();						
				}	else {
					/**
					 * Если отправили форму с редактированием, то обрабатываем её
					 */
					$this->SubmitEditPage($oPageEdit);
				}
				$this->Viewer_Assign('oPageEdit',$oPageEdit);
			} else {
				$this->Message_AddError($this->Lang_Get('page_edit_notfound'),$this->Lang_Get('error'));
				$this->SetParam(0,null);
			}
		}
		/**
		 * Обработка удаления страницы
		 * Замечание: если используется тип таблиц MyISAM, а InnoDB то возможно некорректное удаление вложенных страниц
		 */
		if ($this->GetParam(0)=='delete') {
			$this->Security_ValidateSendForm();
			if ($this->PluginPage_Page_deletePageById($this->GetParam(1))) {
				$this->Message_AddNotice($this->Lang_Get('page_admin_action_delete_ok'));
			} else {
				$this->Message_AddError($this->Lang_Get('page_admin_action_delete_error'),$this->Lang_Get('error'));
			}
		}
		/**
		 * Обработка изменения сортировки страницы
		 */
		if ($this->GetParam(0)=='sort' and $oPage=$this->PluginPage_Page_GetPageById($this->GetParam(1))) {
			$this->Security_ValidateSendForm();
			$sWay=$this->GetParam(2)=='down' ? 'down' : 'up';
			$iSortOld=$oPage->getSort();
			if ($oPagePrev=$this->PluginPage_Page_GetNextPageBySort($iSortOld,$oPage->getPid(),$sWay)) {
				$iSortNew=$oPagePrev->getSort();
				$oPagePrev->setSort($iSortOld);
				$this->PluginPage_Page_UpdatePage($oPagePrev);
			} else {
				if ($sWay=='down') {
					$iSortNew=$iSortOld-1;
				} else {
					$iSortNew=$iSortOld+1;
				}				
			}
			/**
			 * Меняем значения сортировки местами
			 */
			$oPage->setSort($iSortNew);
			$this->PluginPage_Page_UpdatePage($oPage);
		}
		/**
		 * Получаем и загружаем список всех страниц
		 */
		$aPages=$this->PluginPage_Page_GetPages();
		if (count($aPages)==0 and $this->PluginPage_Page_GetCountPage()) {
			$this->PluginPage_Page_SetPagesPidToNull();
			$aPages=$this->PluginPage_Page_GetPages();
		}
		$this->Viewer_Assign('aPages',$aPages);
	}
	/**
	 * Обработка отправки формы при редактировании страницы
	 *
	 * @param unknown_type $oPageEdit
	 */
	protected function SubmitEditPage($oPageEdit) {
		/**
		 * Проверяем корректность полей
		 */
		if (!$this->CheckPageFields()) {
			return ;
		}
		if ($oPageEdit->getId()==getRequest('page_pid')) {
			$this->Message_AddError($this->Lang_Get('system_error'));
			return;
		}
		
		/**
		 * Обновляем свойства страницы
		 */		
		$oPageEdit->setActive(getRequest('page_active') ? 1 : 0);
		$oPageEdit->setMain(getRequest('page_main') ? 1 : 0);
		$oPageEdit->setDateEdit(date("Y-m-d H:i:s"));
		if (getRequest('page_pid')==0) {
			$oPageEdit->setUrlFull(getRequest('page_url'));
			$oPageEdit->setPid(null);
		} else {
			$oPageEdit->setPid(getRequest('page_pid'));			
			$oPageParent=$this->PluginPage_Page_GetPageById(getRequest('page_pid'));
			$oPageEdit->setUrlFull($oPageParent->getUrlFull().'/'.getRequest('page_url'));
		}		
		$oPageEdit->setSeoDescription(getRequest('page_seo_description'));
		$oPageEdit->setSeoKeywords(getRequest('page_seo_keywords'));
		$oPageEdit->setText(getRequest('page_text'));
		$oPageEdit->setTitle(getRequest('page_title'));
		$oPageEdit->setUrl(getRequest('page_url'));
		$oPageEdit->setSort(getRequest('page_sort'));
		/**
		 * Обновляем страницу
		 */
		if ($this->PluginPage_Page_UpdatePage($oPageEdit)) {
			$this->PluginPage_Page_RebuildUrlFull($oPageEdit);
			$this->Message_AddNotice($this->Lang_Get('page_edit_submit_save_ok'));
			$this->SetParam(0,null);
			$this->SetParam(1,null);
		} else {
			$this->Message_AddError($this->Lang_Get('system_error'));
		}
	}
	/**
	 * Обработка отправки формы добавления новой страницы
	 *
	 */
	protected function SubmitAddPage() {
		/**
		 * Проверяем корректность полей
		 */
		if (!$this->CheckPageFields()) {
			return ;
		}
		/**
		 * Заполняем свойства
		 */
		$oPage=Engine::GetEntity('PluginPage_Page');
		$oPage->setActive(getRequest('page_active') ? 1 : 0);
		$oPage->setMain(getRequest('page_main') ? 1 : 0);
		$oPage->setDateAdd(date("Y-m-d H:i:s"));
		if (getRequest('page_pid')==0) {
			$oPage->setUrlFull(getRequest('page_url'));
			$oPage->setPid(null);
		} else {
			$oPage->setPid(getRequest('page_pid'));			
			$oPageParent=$this->PluginPage_Page_GetPageById(getRequest('page_pid'));
			$oPage->setUrlFull($oPageParent->getUrlFull().'/'.getRequest('page_url'));
		}		
		$oPage->setSeoDescription(getRequest('page_seo_description'));
		$oPage->setSeoKeywords(getRequest('page_seo_keywords'));
		$oPage->setText(getRequest('page_text'));
		$oPage->setTitle(getRequest('page_title'));
		$oPage->setUrl(getRequest('page_url'));
		if (getRequest('page_sort')) {
			$oPage->setSort(getRequest('page_sort'));
		} else {
			$oPage->setSort($this->PluginPage_Page_GetMaxSortByPid($oPage->getPid())+1);
		}
		/**
		 * Добавляем страницу
		 */		
		if ($this->PluginPage_Page_AddPage($oPage)) {
			$this->Message_AddNotice($this->Lang_Get('page_create_submit_save_ok'));
			$this->SetParam(0,null);
		} else {
			$this->Message_AddError($this->Lang_Get('system_error'));
		}
	}
	/**
	 * Проверка полей на корректность
	 *
	 * @return unknown
	 */
	protected function CheckPageFields() {		
		$this->Security_ValidateSendForm();	
		
		$bOk=true;
		/**
		 * Проверяем есть ли заголовок топика
		 */
		if (!func_check(getRequest('page_title',null,'post'),'text',2,200)) {
			$this->Message_AddError($this->Lang_Get('page_create_title_error'),$this->Lang_Get('error'));
			$bOk=false;
		}
		/**
		 * Проверяем есть ли заголовок топика, с заменой всех пробельных символов на "_"
		 */		
		$pageUrl=preg_replace("/\s+/",'_',getRequest('page_url',null,'post'));
		$_REQUEST['page_url']=$pageUrl;
		if (!func_check(getRequest('page_url',null,'post'),'login',1,50)) {
			$this->Message_AddError($this->Lang_Get('page_create_url_error'),$this->Lang_Get('error'));
			$bOk=false;
		}
		/**
		 * Проверяем на счет плохих УРЛов
		 */
		if (in_array(getRequest('page_url',null,'post'),$this->aBadPageUrl)) {
			$this->Message_AddError($this->Lang_Get('page_create_url_error_bad').' '.join(',',$this->aBadPageUrl),$this->Lang_Get('error'));
			$bOk=false;
		}
		/**
		 * Проверяем есть ли содержание страницы
		 */
		if (!func_check(getRequest('page_text',null,'post'),'text',1,50000)) {
			$this->Message_AddError($this->Lang_Get('page_create_text_error'),$this->Lang_Get('error'));
			$bOk=false;
		}
		/**
		 * Проверяем страницу в которую хотим вложить
		 */
		if (getRequest('page_pid')!=0 and !($oPageParent=$this->PluginPage_Page_GetPageById(getRequest('page_pid')))) {
			$this->Message_AddError($this->Lang_Get('page_create_parent_page_error'),$this->Lang_Get('error'));
			$bOk=false;
		}		
		/**
		 * Проверяем сортировку
		 */
		if (getRequest('page_sort') and !is_numeric(getRequest('page_sort'))) {
			$this->Message_AddError($this->Lang_Get('page_create_sort_error'),$this->Lang_Get('error'));
			$bOk=false;
		}
		/**
		 * Выполнение хуков
		 */
		$this->Hook_Run('check_page_fields', array('bOk'=>&$bOk));

		return $bOk;
	}
}
?>