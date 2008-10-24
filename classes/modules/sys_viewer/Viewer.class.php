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

require_once('classes/lib/external/Smarty-2.6.19/libs/Smarty.class.php');

/**
 * Модуль обработки шаблонов используя шаблонизатор Smarty
 *
 */
class Viewer extends Module {
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
	protected $sHtmlTitle=SITE_NAME;
	/**
	 * SEO ключевые слова страницы
	 *
	 * @var unknown_type
	 */
	protected $sHtmlKeywords=SITE_KYEWORDS;
	/**
	 * SEO описание страницы
	 *
	 * @var unknown_type
	 */
	protected $sHtmlDescription=SITE_DESCRIPTION;
	
	/**
	 * Разделитель заголовка HTML страницы
	 *
	 * @var unknown_type
	 */
	protected $sHtmlTitleSeparation=' / ';
	
	/**
	 * Инициализация модуля
	 *
	 */
	public function Init() {		
		/**
		 * Создаём объект Smarty и устанавливаем необходиму параметры
		 */
		$this->oSmarty = new Smarty();		
		$this->oSmarty->template_dir=DIR_SMARTY_TEMPLATE;
		$this->oSmarty->compile_dir=DIR_SMARTY_COMPILED;
		$this->oSmarty->cache_dir=DIR_SMARTY_CACHE;
		$this->oSmarty->plugins_dir=array(DIR_SMARTY_PLUG,'plugins');	
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
		 * Загружаем константы путей
		 */
		$this->Assign("DIR_STATIC_SKIN",DIR_STATIC_SKIN);
		$this->Assign("DIR_WEB_ROOT",DIR_WEB_ROOT);
		$this->Assign("DIR_STATIC_ROOT",DIR_STATIC_ROOT);
		$this->Assign("SITE_NAME",SITE_NAME);
		$this->Assign("DIR_UPLOADS_IMAGES",DIR_UPLOADS_IMAGES);
				
		$this->Assign("USER_USE_INVITE",USER_USE_INVITE);
		$this->Assign("SYS_MAIL_INCLUDE_COMMENT_TEXT",SYS_MAIL_INCLUDE_COMMENT_TEXT);
		$this->Assign("SYS_MAIL_INCLUDE_TALK_TEXT",SYS_MAIL_INCLUDE_TALK_TEXT);
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
				
	}
	
	/**
	 * Выводит на экран(браузер) обработанный шаблон
	 *
	 * @param string $sTemplate
	 */
	public function Display($sTemplate) {
		/**
		 * Если шаблон найден то выводим, иначе ошибка
		 */
		if ($this->TemplateExists($sTemplate)) {
			$this->oSmarty->display($sTemplate);
		} else {			
			throw new Exception('Не найден шаблон: '.$sTemplate);
		}
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
			throw new Exception('Не найден шаблон подключаемого блока: '.$sName);
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
	 * Формирует постраничный вывод
	 *
	 * @param int $iCount
	 * @param int $iCurrentPage
	 * @param int $iCountPerPage
	 * @param int $iCountPageLine
	 * @param string $sBaseUrl
	 * @return array()
	 */
	public function MakePaging($iCount,$iCurrentPage,$iCountPerPage,$iCountPageLine,$sBaseUrl) {		
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
		
		$aPaging=array(
			'aPagesLeft' => $aPagesLeft,
			'aPagesRight' => $aPagesRight,
			'iCountPage' => $iCountPage,
			'iCurrentPage' => $iCurrentPage,
			'iNextPage' => $iNextPage,
			'iPrevPage' => $iPrevPage,
			'sBaseUrl' => $sBaseUrl,
		);
		return $aPaging;
	}
}
?>