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
 * Обработка статических страниц
 *
 */
class ActionPage extends Action {
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
		if (!($oPage=$this->Page_GetPageByUrlFull($sUrlFull,1))) {
			return $this->EventNotFound();
		}
		/**
		 * Заполняем HTML теги и SEO
		 */
		$this->Viewer_AddHtmlTitle($oPage->getTitle());
		if ($oPage->getSeoKyewords()) {
			$this->Viewer_SetHtmlKeywords($oPage->getSeoKyewords());
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
		
		$this->Viewer_AddHtmlTitle('Управление страницами');
		/**
		 * Обработка создания новой странички
		 */
		if (getRequest('submit_page_save')) {
			if (!getRequest('page_id')) {							
				$this->SubmitAddPage();				
			}
		}
		/**
		 * Обработка показа странички для редактирования
		 */
		if ($this->GetParam(0)=='edit') {
			if ($oPageEdit=$this->Page_GetPageById($this->GetParam(1))) {
				if (!getRequest('submit_page_save')) {
					$_REQUEST['page_title']=$oPageEdit->getTitle();
					$_REQUEST['page_pid']=$oPageEdit->getPid();
					$_REQUEST['page_url']=$oPageEdit->getUrl();
					$_REQUEST['page_text']=$oPageEdit->getText();
					$_REQUEST['page_seo_keywords']=$oPageEdit->getSeoKyewords();
					$_REQUEST['page_seo_description']=$oPageEdit->getSeoDescription();
					$_REQUEST['page_active']=$oPageEdit->getActive();	
					$_REQUEST['page_id']=$oPageEdit->getId();						
				}	else {
					/**
					 * Если отправили форму с редактированием, то обрабатываем её
					 */
					$this->SubmitEditPage($oPageEdit);
				}
				$this->Viewer_Assign('oPageEdit',$oPageEdit);
			} else {
				$this->Message_AddError('Страница для редактирования не найдена','Ошибка');
				$this->SetParam(0,null);
			}
		}
		/**
		 * Обработка удаления страницы
		 * Замечание: если используется тип таблиц MyISAM, а InnoDB то возможно некорректное удаление вложенных страниц
		 */
		if ($this->GetParam(0)=='delete') {
			if ($this->Page_deletePageById($this->GetParam(1))) {
				$this->Message_AddNotice('Страница удалена');
			} else {
				$this->Message_AddError('Возникла ошибка при удалении страницы','Ошибка');
			}
		}
		/**
		 * Получаем и загружаем список всех страниц
		 */
		$aPages=$this->Page_GetPages();
		if (count($aPages)==0 and $this->Page_GetCountPage()) {
			$this->Page_SetPagesPidToNull();
			$aPages=$this->Page_GetPages();
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
			$this->Message_AddError('Пытаетесь вложить страницу саму в себя?','Ошибка');
			return;
		}
		
		/**
		 * Обновляем свойства страницы
		 */		
		$oPageEdit->setActive(getRequest('page_active') ? 1 : 0);
		$oPageEdit->setDateEdit(date("Y-m-d H:i:s"));
		if (getRequest('page_pid')==0) {
			$oPageEdit->setUrlFull(getRequest('page_url'));
			$oPageEdit->setPid(null);
		} else {
			$oPageEdit->setPid(getRequest('page_pid'));			
			$oPageParent=$this->Page_GetPageById(getRequest('page_pid'));
			$oPageEdit->setUrlFull($oPageParent->getUrlFull().'/'.getRequest('page_url'));
		}		
		$oPageEdit->setSeoDescription(getRequest('page_seo_description'));
		$oPageEdit->setSeoKyewords(getRequest('page_seo_keywords'));
		$oPageEdit->setText(getRequest('page_text'));
		$oPageEdit->setTitle(getRequest('page_title'));
		$oPageEdit->setUrl(getRequest('page_url'));
		/**
		 * Обновляем страницу
		 */
		if ($this->Page_UpdatePage($oPageEdit)) {
			$this->Page_RebuildUrlFull($oPageEdit);
			$this->Message_AddNotice('Страница обновлена');
			$this->SetParam(0,null);
			$this->SetParam(1,null);
		} else {
			$this->Message_AddError('Внутреняя ошибка, повторите позже','Ошибка');
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
		$oPage=new PageEntity_Page();
		$oPage->setActive(getRequest('page_active') ? 1 : 0);
		$oPage->setDateAdd(date("Y-m-d H:i:s"));
		if (getRequest('page_pid')==0) {
			$oPage->setUrlFull(getRequest('page_url'));
			$oPage->setPid(null);
		} else {
			$oPage->setPid(getRequest('page_pid'));			
			$oPageParent=$this->Page_GetPageById(getRequest('page_pid'));
			$oPage->setUrlFull($oPageParent->getUrlFull().'/'.getRequest('page_url'));
		}		
		$oPage->setSeoDescription(getRequest('page_seo_description'));
		$oPage->setSeoKyewords(getRequest('page_seo_keywords'));
		$oPage->setText(getRequest('page_text'));
		$oPage->setTitle(getRequest('page_title'));
		$oPage->setUrl(getRequest('page_url'));
		/**
		 * Добавляем страницу
		 */		
		if ($this->Page_AddPage($oPage)) {
			$this->Message_AddNotice('Новая страница добавлена');
			$this->SetParam(0,null);
		} else {
			$this->Message_AddError('Внутреняя ошибка, повторите позже','Ошибка');
		}
	}
	/**
	 * Проверка полей на корректность
	 *
	 * @return unknown
	 */
	protected function CheckPageFields() {		
			$bOk=true;
			/**
		 	* Проверяем есть ли заголовок топика
		 	*/
			if (!func_check(getRequest('page_title'),'text',2,200)) {
				$this->Message_AddError('Название страницы должно быть от 2 до 200 символов','Ошибка');
				$bOk=false;
			}
			/**
			* Проверяем есть ли заголовок топика, с заменой всех пробельных символов на "_"
			*/		
			$pageUrl=preg_replace("/\s+/",'_',getRequest('page_url'));
			$_REQUEST['page_url']=$pageUrl;
			if (!func_check(getRequest('page_url'),'login',1,50)) {
				$this->Message_AddError('URL должен быть от 1 до 50 символов и только на латинице + цифры и знаки "-", "_"','Ошибка');
				$bOk=false;
			}
			/**
		 	* Проверяем на счет плохих УРЛов
			 */
			if (in_array(getRequest('page_url'),$this->aBadPageUrl)) {
				$this->Message_AddError('URL должен отличаться от: '.join(',',$this->aBadPageUrl),'Ошибка');
				$bOk=false;
			}
			/**
		 	* Проверяем есть ли содержание страницы
		 	*/
			if (!func_check(getRequest('page_text'),'text',1,50000)) {
				$this->Message_AddError('Текст страницы должен быть от 1 до 50000 символов','Ошибка');
				$bOk=false;
			}
			/**
			 * Проверяем страницу в которую хотим вложить
			 */
			if (getRequest('page_pid')!=0 and !($oPageParent=$this->Page_GetPageById(getRequest('page_pid')))) {
				$this->Message_AddError('Неверно выбрана страница для вложения','Ошибка');
				$bOk=false;
			}		
			
			return $bOk;
	}
}
?>
