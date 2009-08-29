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

require_once(Config::Get('path.root.engine').'/lib/external/Smarty-2.6.19/libs/Smarty.class.php');

/**
 * Модуль обработки шаблонов используя шаблонизатор Smarty
 *
 */
class LsViewer extends Module {
	/**
	 * Объект Smarty
	 *
	 * @var Smarty
	 */
	protected $oSmarty;		
	/**
	 * Коллекция(массив) блоков
	 *
	 * @var array
	 */
	protected $aBlocks=array();	
	/**
	 * Заголовок HTML страницы
	 *
	 * @var unknown_type
	 */
	protected $sHtmlTitle;
	/**
	 * SEO ключевые слова страницы
	 *
	 * @var unknown_type
	 */
	protected $sHtmlKeywords;
	/**
	 * SEO описание страницы
	 *
	 * @var unknown_type
	 */
	protected $sHtmlDescription;
	
	/**
	 * Разделитель заголовка HTML страницы
	 *
	 * @var unknown_type
	 */
	protected $sHtmlTitleSeparation=' / ';
	
	/**
	 * Альтернативный адрес страницы по RSS
	 *
	 * @var array
	 */
	protected $aHtmlRssAlternate=null;
	
	/**
	 * Переменные для отдачи при ajax запросе
	 *
	 * @var unknown_type
	 */
	protected $aVarsAjax=array();
	/**
	 * Определяет тип ответа при ajax запросе
	 *
	 * @var unknown_type
	 */
	protected $sResponseAjax=null;
	/**
	 * Инициализация модуля
	 *
	 */
	public function Init() {	
		/**
		 * Заголовок HTML страницы
		 */
		$this->sHtmlTitle=Config::Get('view.name');
		/**
		 * SEO ключевые слова страницы
		 */
		$this->sHtmlKeywords=Config::Get('view.keywords');
		/**
		 * SEO описание страницы
		 */
		$this->sHtmlDescription=Config::Get('view.description');			

		/**
		 * Создаём объект Smarty и устанавливаем необходиму параметры
		 */
		$this->oSmarty = new Smarty();		
		$this->oSmarty->template_dir=Config::Get('path.smarty.template');
		$this->oSmarty->compile_dir=Config::Get('path.smarty.compiled');
		$this->oSmarty->cache_dir=Config::Get('path.smarty.cache');
		$this->oSmarty->plugins_dir=array(Config::Get('path.smarty.plug'),'plugins');	
		/**
		 * Подключаем к Smarty небольшой плагинчик форматирования даты
		 */
		$this->oSmarty->register_function("date_format", "func_date_smarty");		
	}
	
	/**
	 * Выполняет загрузку необходимый(возможно даже системный :)) переменных в шалон
	 *
	 */
	public function VarAssign() {		
		/**
		 * Загружаем весь $_REQUEST, предварительно обработав его функцией func_htmlspecialchars()
		 */
		$aRequest=$_REQUEST;
		func_htmlspecialchars($aRequest);
		$this->Assign("_aRequest",$aRequest);
		/**
		 * Параметры стандартной сессии
		 */
		$this->Assign("_sPhpSessionName",session_name());
		$this->Assign("_sPhpSessionId",session_id());
		/**
		 * Загружаем часть конфигурации
		 */
		$aConfig=Config::Get();
		foreach ((array)Config::Get('view.no_assign') as $sGroup) {
			unset($aConfig[$sGroup]);
		}
		$this->Assign("aConfig",$aConfig);

		/**
		 * Загружаем роутинг с учетом правил rewrite
		 */
		$aRouter=array();
		foreach ($aConfig['router']['page'] as $sPage=>$aAction) {
			$aRouter[$sPage]=Router::GetPath($sPage);
		}
		$this->Assign("aRouter",$aRouter);

		/**
		 * Загружаем константы путей
		 * 
		 * Рефакторинг:
		 * переход на использование конфигурационных массивов
		 */
		//$this->Assign("DIR_STATIC_SKIN",DIR_STATIC_SKIN);
		//$this->Assign("DIR_WEB_ROOT",DIR_WEB_ROOT);
		//$this->Assign("DIR_WEB_ENGINE_LIB",DIR_WEB_ENGINE_LIB);
		//$this->Assign("DIR_STATIC_ROOT",DIR_STATIC_ROOT);
		//$this->Assign("SITE_NAME",SITE_NAME);
		//$this->Assign("DIR_UPLOADS_IMAGES",DIR_UPLOADS_IMAGES);
				
		//$this->Assign("BLOG_USE_TINYMCE",BLOG_USE_TINYMCE);
		//$this->Assign("USER_USE_INVITE",USER_USE_INVITE);
		//$this->Assign("SYS_MAIL_INCLUDE_COMMENT_TEXT",SYS_MAIL_INCLUDE_COMMENT_TEXT);
		//$this->Assign("SYS_MAIL_INCLUDE_TALK_TEXT",SYS_MAIL_INCLUDE_TALK_TEXT);
		//$this->Assign("BLOG_COMMENT_MAX_TREE_LEVEL",BLOG_COMMENT_MAX_TREE_LEVEL);
		
		//$this->Assign("VOTE_LIMIT_TIME_TOPIC",VOTE_LIMIT_TIME_TOPIC);
		//$this->Assign("VOTE_LIMIT_TIME_COMMENT",VOTE_LIMIT_TIME_COMMENT);
		/**
		 * Константы роутинга страниц
		 */
		/**
		 * Рефакторинг:
		 * переход на использование конфигурационных массивов
		 * 
		$this->Assign("ROUTE_PAGE_ERROR",ROUTE_PAGE_ERROR);
		$this->Assign("ROUTE_PAGE_REGISTRATION",ROUTE_PAGE_REGISTRATION);
		$this->Assign("ROUTE_PAGE_PROFILE",ROUTE_PAGE_PROFILE);
		$this->Assign("ROUTE_PAGE_MY",ROUTE_PAGE_MY);
		$this->Assign("ROUTE_PAGE_BLOG",ROUTE_PAGE_BLOG);
		$this->Assign("ROUTE_PAGE_PERSONAL_BLOG",ROUTE_PAGE_PERSONAL_BLOG);
		$this->Assign("ROUTE_PAGE_TOP",ROUTE_PAGE_TOP);
		$this->Assign("ROUTE_PAGE_INDEX",ROUTE_PAGE_INDEX);
		$this->Assign("ROUTE_PAGE_NEW",ROUTE_PAGE_NEW);
		$this->Assign("ROUTE_PAGE_TOPIC",ROUTE_PAGE_TOPIC);
		$this->Assign("ROUTE_PAGE_PAGE",ROUTE_PAGE_PAGE);
		$this->Assign("ROUTE_PAGE_LOGIN",ROUTE_PAGE_LOGIN);
		$this->Assign("ROUTE_PAGE_PEOPLE",ROUTE_PAGE_PEOPLE);
		$this->Assign("ROUTE_PAGE_SETTINGS",ROUTE_PAGE_SETTINGS);
		$this->Assign("ROUTE_PAGE_TAG",ROUTE_PAGE_TAG);
		$this->Assign("ROUTE_PAGE_COMMENTS",ROUTE_PAGE_COMMENTS);
		$this->Assign("ROUTE_PAGE_TALK",ROUTE_PAGE_TALK);
		$this->Assign("ROUTE_PAGE_RSS",ROUTE_PAGE_RSS);
		$this->Assign("ROUTE_PAGE_LINK",ROUTE_PAGE_LINK);
		$this->Assign("ROUTE_PAGE_QUESTION",ROUTE_PAGE_QUESTION);
		$this->Assign("ROUTE_PAGE_BLOGS",ROUTE_PAGE_BLOGS);
		$this->Assign("ROUTE_PAGE_SEARCH",ROUTE_PAGE_SEARCH);		
		**/
		/**
		 * Загружаем список блоков
		 */
		$this->Assign("aBlocks",$this->aBlocks);	
		/**
		 * Загружаем HTML заголовки
		 */
		$this->Assign("sHtmlTitle",htmlspecialchars($this->sHtmlTitle));
		$this->Assign("sHtmlKeywords",htmlspecialchars($this->sHtmlKeywords));
		$this->Assign("sHtmlDescription",htmlspecialchars($this->sHtmlDescription));
		$this->Assign("aHtmlRssAlternate",$this->aHtmlRssAlternate);
				
	}
	
	/**
	 * Выводит на экран(браузер) обработанный шаблон
	 *
	 * @param string $sTemplate
	 */
	public function Display($sTemplate) {
		if ($this->sResponseAjax) {
			$this->DisplayAjax($this->sResponseAjax);
		}
		/**
		 * Если шаблон найден то выводим, иначе ошибка
		 */
		if ($this->TemplateExists($sTemplate)) {
			$this->oSmarty->display($sTemplate);
		} else {			
			throw new Exception($this->Lang_Get('system_error_template').': '.$sTemplate);
		}
	}
	/**
	 * Ответ на ajax запрос
	 *
	 * @param unknown_type $sType - jsHttpRequest или json
	 */
	public function DisplayAjax($sType='jsHttpRequest') {
		/**
		 * Загружаем статус ответа и сообщение
		 */
		$bStateError=false;
		$sMsgTitle='';
		$sMsg='';
		$aMsgError=$this->Message_GetError();
		$aMsgNotice=$this->Message_GetNotice();
		if (count($aMsgError)>0) {
			$bStateError=true;
			$sMsgTitle=$aMsgError[0]['title'];
			$sMsg=$aMsgError[0]['msg'];
		}
		if (count($aMsgNotice)>0) {			
			$sMsgTitle=$aMsgNotice[0]['title'];
			$sMsg=$aMsgNotice[0]['msg'];
		}
		$this->AssingAjax('sMsgTitle',$sMsgTitle);
		$this->AssingAjax('sMsg',$sMsg);
		$this->AssingAjax('bStateError',$bStateError);		
		if ($sType=='jsHttpRequest') {			
			foreach ($this->aVarsAjax as $key => $value) {
				$GLOBALS['_RESULT'][$key]=$value;
			}
		} elseif ($sType=='json') {
			if (!headers_sent()) {
				header('Content-type: application/json');
			} 	
			echo json_encode($this->aVarsAjax);
		}
		exit();
	}
	/**
	 * Устанавливает тип отдачи при ajax запросе, если null то выполняется обычный вывод шаблона в браузер
	 *
	 * @param unknown_type $sResponseAjax
	 */
	public function SetResponseAjax($sResponseAjax='jsHttpRequest') {
		/**
		 * Проверка на безопасную обработку ajax запроса
		 */
		if ($sResponseAjax) {			
			if ($sResponseAjax=='jsHttpRequest') {
				require_once(Config::Get('path.root.engine')."/lib/external/JsHttpRequest/JsHttpRequest.php");
				$JsHttpRequest = new JsHttpRequest("UTF-8");
			}
			$this->Security_ValidateSendForm();
		}		
		$this->sResponseAjax=$sResponseAjax;
	}
	/**
	 * Загружает переменную в шаблон
	 *
	 * @param string $sName
	 * @param unknown_type $value
	 */
	public function Assign($sName,$value) {		
		$this->oSmarty->assign($sName, $value);
	}
	/**
	 * Загружаем переменную в ajax ответ
	 *
	 * @param unknown_type $sName
	 * @param unknown_type $value
	 */
	public function AssingAjax($sName,$value) {
		$this->aVarsAjax[$sName]=$value;
	}
	/**
	 * Возвращает обработанный шаблон
	 *
	 * @param string $sTemplate
	 * @return string
	 */
	public function Fetch($sTemplate) {
		return $this->oSmarty->fetch($sTemplate);	
	}
	
	/**
	 * Проверяет существование шаблона
	 *
	 * @param string $sTemplate
	 * @return bool
	 */
	public function TemplateExists($sTemplate) {
		return $this->oSmarty->template_exists($sTemplate);
	}
	
	/**
	 * Добавляет блок для отображения
	 *
	 * @param string $sName
	 * @param arra $aParams - параметры блока, которые будут переданы обработчику блока
	 */
	public function AddBlock($sGroup,$sName,$aParams=array()) {
		/**
		 * Если смогли определить тип блока то добавляем его
		 */
		$sType=$this->DefineTypeBlock($sName);
		if ($sType=='undefined') {
			return false;
		}
		$this->aBlocks[$sGroup][]=array(
			'type' => $sType,
			'name' => $sName,
			'params' => $aParams,
		);
		return true;
	}
	
	/**
	 * Добавляет список блоков
	 *
	 * @param array $aBlocks
	 */
	public function AddBlocks($sGroup,$aBlocks) {
		/**
		 * Удаляем ранее добавленые блоки
		 */
		$this->ClearBlocks($sGroup);
		foreach ($aBlocks as $sBlock) {
			$this->AddBlock($sGroup,$sBlock);
		}
	}
	
	/**
	 * Удаляет блоки группы
	 *
	 */
	public function ClearBlocks($sGroup) {
		$this->aBlocks[$sGroup]=array();
	}
	/**
	 * Удаляет блоки всех групп
	 *
	 * @param unknown_type $sGroup
	 */
	public function ClearBlocksAll() {
		foreach ($this->aBlocks as $sGroup => $aBlock) {
			$this->aBlocks[$sGroup]=array();
		}
	}
	
	/**
	 * Определяет тип блока
	 *
	 * @param string $sName
	 * @return string('block','template','undefined')
	 */
	protected function DefineTypeBlock($sName) {
		if ($this->TemplateExists('block.'.$sName.'.tpl')) {
			/**
			 * Если найден шаблон вида block.name.tpl то считаем что тип 'block'
			 */
			return 'block';
		} elseif ($this->TemplateExists($sName)) {
			/**
			 * Если найден шаблон по имени блока то считаем его простым шаблоном
			 */
			return 'template';
		} else {
			/**
			 * Считаем что тип не определен
			 */
			throw new Exception($this->Lang_Get('system_error_template_block').': '.$sName);
			return 'undefined';
		}
	}
	/**
	 * Устанавливаем заголовок страницы(тег <title>)
	 *
	 * @param string $sText
	 */
	public function SetHtmlTitle($sText) {
		$this->sHtmlTitle=$sText;
	}
	/**
	 * Добавляет часть заголовка страницы через разделитель
	 *
	 * @param string $sText
	 */
	public function AddHtmlTitle($sText) {
		$this->sHtmlTitle=$sText.$this->sHtmlTitleSeparation.$this->sHtmlTitle;
	}
	/**
	 * Возвращает текущий заголовок страницы
	 *
	 * @return unknown
	 */
	public function GetHtmlTitle() {
		return $this->sHtmlTitle;
	}	
	/**
	 * Устанавливает ключевые слова keywords
	 *
	 * @param string $sText
	 */
	public function SetHtmlKeywords($sText) {
		$this->sHtmlKeywords=$sText;
	}
	/**
	 * Устанавливает описание страницы desciption
	 *
	 * @param string $sText
	 */
	public function SetHtmlDescription($sText) {
		$this->sHtmlDescription=$sText;
	}
	/**
	 * Устанавливает альтернативный адрес страницы по RSS
	 *
	 * @param string $sText
	 */
	public function SetHtmlRssAlternate($sUrl,$sTitle) {
		$this->aHtmlRssAlternate['title']=htmlspecialchars($sTitle);
		$this->aHtmlRssAlternate['url']=htmlspecialchars($sUrl);
	}
	/**
	 * Формирует постраничный вывод
	 *
	 * @param int $iCount
	 * @param int $iCurrentPage
	 * @param int $iCountPerPage
	 * @param int $iCountPageLine
	 * @param string $sBaseUrl
	 * @param array(name=>value) $aGetParamsList
	 * @return array()
	 */
	public function MakePaging($iCount,$iCurrentPage,$iCountPerPage,$iCountPageLine,$sBaseUrl,$aGetParamsList=array()) {		
		if ($iCount==0) {
			return false;
		}
		
		$iCountPage=ceil($iCount/$iCountPerPage); 
		if (!preg_match("/^[1-9]\d*$/i",$iCurrentPage)) {
			$iCurrentPage=1;
		}		
		if ($iCurrentPage>$iCountPage) {
			$iCurrentPage=$iCountPage;
		}
		
		$aPagesLeft=array();		
		$iTemp=$iCurrentPage-$iCountPageLine;
		$iTemp = $iTemp<1 ? 1 : $iTemp; 
		for ($i=$iTemp;$i<$iCurrentPage;$i++) {
			$aPagesLeft[]=$i;
		}
		
		$aPagesRight=array();				 
		for ($i=$iCurrentPage+1;$i<=$iCurrentPage+$iCountPageLine and $i<=$iCountPage;$i++) {
			$aPagesRight[]=$i;
		}
		
		$iNextPage = $iCurrentPage<$iCountPage ? $iCurrentPage+1 : false;
		$iPrevPage = $iCurrentPage>1 ? $iCurrentPage-1 : false;
		
		$sGetParams='';
		foreach ($aGetParamsList as $sName => $sValue) {
			$sGetParams.=$sName.'='.urlencode($sValue).'&';
		}
		if ($sGetParams!='') {
			$sGetParams='?'.trim($sGetParams,'&');
		}
		
		$aPaging=array(
			'aPagesLeft' => $aPagesLeft,
			'aPagesRight' => $aPagesRight,
			'iCountPage' => $iCountPage,
			'iCurrentPage' => $iCurrentPage,
			'iNextPage' => $iNextPage,
			'iPrevPage' => $iPrevPage,
			'sBaseUrl' => $sBaseUrl,
			'sGetParams' => $sGetParams,
		);
		return $aPaging;
	}
	/**
	 * Загружаем переменные в шаблон при завершении модуля
	 *
	 */
	public function Shutdown() {
		$this->VarAssign();
	}
}
?>