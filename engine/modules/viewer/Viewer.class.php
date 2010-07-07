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
require_once(Config::Get('path.root.engine').'/modules/viewer/lsSmarty.class.php');
require_once(Config::Get('path.root.engine').'/lib/external/CSSTidy-1.3/class.csstidy.php');
require_once(Config::Get('path.root.engine').'/lib/external/JSMin-1.1.1/jsmin.php');

/**
 * Модуль обработки шаблонов используя шаблонизатор Smarty
 *
 */
class ModuleViewer extends Module {
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
	 * Массив правил организации блоков
	 * 
	 * @var array
	 */
	protected $aBlockRules = array();
	/**
	 * Стандартные настройки вывода js, css файлов
	 *
	 * @var array
	 */
	protected $aFilesDefault=array(
		'js'  => array(),
		'css' => array()
	);	
	/**
	 * Параметры отображения js, css файлов
	 *
	 * @var array
	 */
	protected $aFilesParams=array(
		'js'  => array(),
		'css' => array()
	);			
	/**
	 * Правила переопределение массивов js и css
	 *
	 * @var array
	 */
	protected $aFileRules=array();
	/**
	 * Список JS, которые нужно добавить в начало и в конец
	 *
	 * @var array
	 */
	protected $aJsInclude = array(
		'append'  => array(),
		'prepend' => array()
	);
	/**
	 * Список CSS, которые нужно добавить в начало и в конец
	 *
	 * @var array
	 */
	protected $aCssInclude = array(
		'append'  => array(),
		'prepend' => array()
	);	
	/**
	 * Каталог для кешировния js,css файлов
	 *
	 * @var string
	 */
	protected $sCacheDir='';
	/**
	 * Объект CSSTidy для компрессии css-файлов
	 *
	 * @var csstidy
	 */
	protected $oCssCompressor = null;
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
	 * Html код для подключения js,css
	 *
	 * @var string
	 */
	protected $aHtmlHeadFiles='';	
	
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
	 * Список меню для рендеринга
	 *
	 * @var array
	 */
	protected $aMenu=array();
	/**
	 * Скомпилированные меню
	 *
	 * @var array
	 */
	protected $aMenuFetch=array();
	/**
	 * Инициализация модуля
	 *
	 */
	public function Init() {
		$this->Hook_Run('viewer_init_start');
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
		$this->oSmarty = new lsSmarty();		
		$this->oSmarty->template_dir=array(Config::Get('path.smarty.template'),Config::Get('path.root.server').'/plugins/');
		/**
		 * Для каждого скина устанавливаем свою директорию компиляции шаблонов
		 */
		$sCompilePath = Config::Get('path.smarty.compiled').'/'.Config::Get('view.skin');
		if(!is_dir($sCompilePath)) @mkdir($sCompilePath);
		$this->oSmarty->compile_dir=$sCompilePath;
		
		$this->oSmarty->cache_dir=Config::Get('path.smarty.cache');
		$this->oSmarty->plugins_dir=array(Config::Get('path.smarty.plug'),'plugins');	
		/**
		 * Получаем настройки блоков из конфигов
		 */
		$this->InitBlockParams();
		/**
		 * Добавляем блоки по предзагруженным правилам из конфигов
		 */
		$this->BuildBlocks();
		/**
		 * Получаем настройки JS, CSS файлов
		 */
		$this->InitFileParams();
		$this->sCacheDir = Config::Get('path.smarty.cache');
	}
	
	/**
	 * Получает локальную копию модуля
	 *
	 * return ModuleViewer
	 */
	public function GetLocalViewer() {
		$oViewerLocal=new ModuleViewer(Engine::getInstance());
		$oViewerLocal->Init();
		$oViewerLocal->VarAssign();
		$oViewerLocal->Assign('aLang',$this->Lang_GetLangMsg());
		
		return $oViewerLocal;
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
		 * Загружаем объект доступа к конфигурации 
		 */ 
		$this->Assign("oConfig",Config::getInstance());
		/**
		 * Загружаем роутинг с учетом правил rewrite
		 */
		$aRouter=array();
		$aPages=Config::Get('router.page');
		
		if(!$aPages or !is_array($aPages)) throw new Exception('Router rules is underfined.');	
		foreach ($aPages as $sPage=>$aAction) {
			$aRouter[$sPage]=Router::GetPath($sPage);
		}
		$this->Assign("aRouter",$aRouter);
		/**
		 * Загружаем в шаблон блоки
		 */
		$this->Assign("aBlocks",$this->aBlocks);	
		/**
		 * Загружаем HTML заголовки
		 */
		$this->Assign("sHtmlTitle",htmlspecialchars($this->sHtmlTitle));
		$this->Assign("sHtmlKeywords",htmlspecialchars($this->sHtmlKeywords));
		$this->Assign("sHtmlDescription",htmlspecialchars($this->sHtmlDescription));
		$this->Assign("aHtmlHeadFiles",$this->aHtmlHeadFiles);
		$this->Assign("aHtmlRssAlternate",$this->aHtmlRssAlternate);
		/**
		 * Загружаем список активных плагинов
		 */
		$aPlugins=$this->oEngine->GetPlugins();
		$this->Assign("aPluginActive",array_fill_keys(array_keys($aPlugins),true));		
	}
	
	/**
	 * Загружаем содержимое menu-контейнеров
	 */	
	protected function MenuVarAssign() {
		$this->Assign("aMenuFetch",$this->aMenuFetch);
		$this->Assign("aMenuContainers",array_keys($this->aMenu));		
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
		 * Предварительно проверяем наличие делегата
		 */
		$sTemplate=$this->Plugin_GetDelegate('template',$sTemplate);
		if ($this->TemplateExists($sTemplate)) {
			$this->oSmarty->display($sTemplate);
		} else {
			throw new Exception('Can not find the template: '.$sTemplate);
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
		$this->AssignAjax('sMsgTitle',$sMsgTitle);
		$this->AssignAjax('sMsg',$sMsg);
		$this->AssignAjax('bStateError',$bStateError);		
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
		}
		$this->Security_ValidateSendForm();		
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
	public function AssignAjax($sName,$value) {
		$this->aVarsAjax[$sName]=$value;
	}
	/**
	 * Возвращает обработанный шаблон
	 *
	 * @param string $sTemplate
	 * @return string
	 */
	public function Fetch($sTemplate) {
		/**
		 * Проверяем наличие делегата
		 */
		$sTemplate=$this->Plugin_GetDelegate('template',$sTemplate);
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
	 * Инициализируем параметры отображения блоков
	 */
	protected function InitBlockParams() {
		if($aRules=Config::Get('block')) {
			$this->aBlockRules=$aRules;
		}
	}
	/**
	 * Добавляет блок для отображения
	 *
	 * @param string $sGroup
	 * @param string $sName
	 * @param array  $aParams - параметры блока, которые будут переданы обработчику блока
	 * @param int    $iPriority
	 * @return bool
	 */
	public function AddBlock($sGroup,$sName,$aParams=array(),$iPriority=5) {
		/**
		 * Если не указана директория шаблона, но указана приналежность к плагину,
		 * то "вычисляем" правильную директорию
		 */
		if(!isset($aParams['dir']) and isset($aParams['plugin'])) {
			$aParams['dir'] = Plugin::GetTemplatePath($aParams['plugin']);
		}	
		/**
		 * Если смогли определить тип блока то добавляем его
		 */
		$sType=$this->DefineTypeBlock($sName,isset($aParams['dir'])?$aParams['dir']:null);
		if ($sType=='undefined') {
			return false;
		}
		$this->aBlocks[$sGroup][]=array(
			'type'     => $sType,
			'name'     => $sName,
			'params'   => $aParams,
			'priority' => $iPriority,
		);
		return true;
	}

	/**
	 * Добавляет список блоков
	 *
	 * @param array $aBlocks
	 */
	public function AddBlocks($sGroup,$aBlocks,$ClearBlocks=true) {
		/**
		 * Удаляем ранее добавленые блоки
		 */
		if ($ClearBlocks) {
			$this->ClearBlocks($sGroup);
		}
		foreach ($aBlocks as $sBlock) {
			if (is_array($sBlock)) {
				$this->AddBlock(
					$sGroup,
					$sBlock['block'],
					isset($sBlock['params']) ? $sBlock['params'] : array(),
					isset($sBlock['priority']) ? $sBlock['priority'] : 5
				);
			} else {
				$this->AddBlock($sGroup,$sBlock);
			}
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
	protected function DefineTypeBlock($sName,$sDir=null) {	
		if ($this->TemplateExists(is_null($sDir)?'block.'.$sName.'.tpl':rtrim($sDir,'/').'/block.'.$sName.'.tpl')) {
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
			throw new Exception('Can not find the block`s template: '.$sName);
			return 'undefined';
		}
	}

	/**
	 * Анализируем правила и наборы массивов
	 * получаем окончательные списки блоков
	 */
	protected function BuildBlocks() {
		$sAction = strtolower(Router::GetAction());
		$sEvent  = strtolower(Router::GetActionEvent());		
		foreach($this->aBlockRules as $sName=>$aRule) {
			$bUse=false;
			/**
			 * Если в правиле не указан список блоков, нам такое не нужно
			 */
			if(!array_key_exists('blocks',$aRule)) continue;
			/**
			 * Если не задан action для исполнения и нет ни одного шаблона path, 
			 * или текущий не входит в перечисленные в правиле 
			 * то выбираем следующее правило
			 */
			if(!array_key_exists('action',$aRule) && !array_key_exists('path',$aRule)) continue;
			if(in_array($sAction, (array)$aRule['action'])) $bUse=true;			
			if(array_key_exists($sAction,(array)$aRule['action'])) {
				/**
				 * Если задан список event`ов и текущий в него не входит,
				 * переходи к следующему действию.
				 */
				foreach ((array)$aRule['action'][$sAction] as $sEventPreg) {
					if(substr($sEventPreg,0,1)!='/') {
						/**
						* значит это название event`a
						*/
						if($sEvent==$sEventPreg) { $bUse=true; break; }
					} else {
						/**
						* это регулярное выражение
						*/
						if(preg_match($sEventPreg,$sEvent)) { $bUse=true; break; }
					}
				}
			}
			/**
			 * Если не найдено совпадение по паре Action/Event,
			 * переходим к поиску по regexp путей.
			 */
			if(!$bUse && isset($aRule['path'])) {
				$sPath = rtrim(Router::GetPathWebCurrent(),"/");
				/**
				 * Проверяем последовательно каждый regexp
				 */
				foreach((array)$aRule['path'] as $sRulePath) {
					$sPattern = "~".str_replace(array('/','*'),array('\/','[\w\-]+'), $sRulePath)."~";	
					if(preg_match($sPattern, $sPath)) {
						$bUse=true;
						break 1;
					}
				}
				
			}
			
			if($bUse){
				/**
				 * Если задан режим очистки блоков, сначала чистим старые блоки
				 */
				if(isset($aRule['clear'])) {
					switch (true) {
						/**
						 * Если установлен в true, значит очищаем все
						 */						
						case  ($aRule['clear']===true):
							$this->ClearBlocksAll();
							break;
						
						case is_string($aRule['clear']):
							$this->ClearBlocks($aRule['clear']);
							break;
							
						case is_array($aRule['clear']):
							foreach ($aRule['clear'] as $sGroup) {
								$this->ClearBlocks($sGroup);
							}
							break;
					}
				}
				/**
				 * Добавляем все блоки, указанные в параметре blocks
				 */
				foreach ($aRule['blocks'] as $sGroup => $aBlocks) {
					foreach ((array)$aBlocks as $sName=>$aParams) {
						/**
						 * Если название блока указывается в параметрах
						 */
						if (is_int($sName)) {
							if (is_array($aParams)) {
								$sName=$aParams['block'];
							}
						}
						/**
						 * Если $aParams не являются массивом, значит передано только имя блока
						 */
						if(!is_array($aParams)) {
							$this->AddBlock($sGroup,$aParams);
						} else {
							$this->AddBlock(
								$sGroup,$sName,
								isset($aParams['params']) ? $aParams['params'] : array(),
								isset($aParams['priority']) ? $aParams['priority'] : 5
							);
						}
					}
				}
			}
		}		
		return true;
	}
	
	/**
	 * Вспомагательная функция для сортировки блоков по приоритетности
	 *
	 * @param  array $a
	 * @param  array $b
	 * @return int
	 */
	protected function _SortBlocks($a,$b) {
		return ($a["priority"]-$b["priority"]);
	}
	/**
	 * Сортируем блоки
	 *
	 */
	protected function SortBlocks() {
		/**
		 * Сортируем блоки по приоритетности
		 */
		foreach($this->aBlocks as $sGroup=>$aBlocks) {
			uasort($aBlocks,array(&$this,'_SortBlocks'));
			$this->aBlocks[$sGroup] = array_reverse($aBlocks);
		}
	}
	/**
	 * Инициализирует параметры вывода js- и css- файлов
	 */	
	protected function InitFileParams() {
		foreach (array('js','css') as $sType) {
			/**
			 * Проверяем наличие списка файлов данного типа
			 */
			$aFiles = Config::Get('head.default.'.$sType);
			if(is_array($aFiles) and count($aFiles)) {
				foreach ($aFiles as $sFile=>$aParams) {
					if(!is_array($aParams)) {
						/**
						 * Параметры не определены
						 */
						$this->aFilesDefault[$sType][] = $aParams;
					} else {
						/**
						 * Добавляем файл и параметры
						 */
						$this->aFilesDefault[$sType][] = $sFile;
						$this->aFilesParams[$sType][$sFile] = $aParams;
					}
				}				
			}
		}
	}
	/**
	 * Создает css-компрессор и инициализирует его конфигурацию
	 *
	 * @return bool
	 */
	protected function InitCssCompressor() {
		/**
		 * Получаем параметры из конфигурации
		 */
		$aParams = Config::Get('compress.css');	
		$this->oCssCompressor =($aParams['use']) ? new csstidy() : null;
		/**
		 * Если компрессор не создан, завершаем работу инициализатора
		 */
		if(!$this->oCssCompressor) return false;
		/**
		 * Устанавливаем параметры
		 */
   		$this->oCssCompressor->set_cfg('case_properties',     $aParams['case_properties']);
   		$this->oCssCompressor->set_cfg('merge_selectors',     $aParams['merge_selectors']);
   		$this->oCssCompressor->set_cfg('optimise_shorthands', $aParams['optimise_shorthands']);
   		$this->oCssCompressor->set_cfg('remove_last_;',       $aParams['remove_last_;']);
   		$this->oCssCompressor->set_cfg('css_level',           $aParams['css_level']);
   		$this->oCssCompressor->load_template($aParams['template']);		
   		
   		return true;
	}
	
	/**
	 * Функции добавления js-скриптов и css-каскадов
	 */
	public function AppendScript($sJs,$aParams=array()) {
		$this->aJsInclude['append'][] = $sJs;
		$this->aFilesParams['js'][$sJs] = $aParams;
	}
	public function PrependScript($sJs,$aParams=array()) {
		$this->aJsInclude['prepend'][] = $sJs;
		$this->aFilesParams['js'][$sJs] = $aParams;		
	}
	public function AppendStyle($sCss,$aParams=array()) {
		$this->aCssInclude['append'][] = $sCss;
		$this->aFilesParams['css'][$sCss] = $aParams;
	}
	public function PrependStyle($sCss,$aParams=array()) {
		$this->aCssInclude['prepend'][] = $sCss;		
		$this->aFilesParams['css'][$sCss] = $aParams;
	}	
	
	/**
	 * Строит массив для подключения css и js,
	 * преобразовывает их в строку для HTML 
	 *
	 * @return bool
	 */
	protected function BuildHeadFiles() {	
		$sPath = Router::GetPathWebCurrent();
		/**
		 * По умолчанию имеем дефаултовые настройки
		 */
		$aResult = $this->aFilesDefault;
		
		$this->aFileRules = Config::Get('head.rules');
		foreach((array)$this->aFileRules as $sName => $aRule) {
			if(!$aRule['path']) continue;

			foreach((array)$aRule['path'] as $sRulePath) {
				$sPattern = "~".str_replace(array('/','*'),array('\/','\w+'), $sRulePath)."~";
				if(preg_match($sPattern, $sPath)) { 
					/**
					 * Преобразование JS
					 */
					if(isset($aRule['js']['empty']) && $aRule['js']['empty']) $aResult['js']=array();
					if(isset($aRule['js']['exclude']) && is_array($aRule['js']['exclude'])) $aResult['js']=array_diff($aResult['js'],$aRule['js']['exclude']);
					if(isset($aRule['js']['include']) && is_array($aRule['js']['include'])) $aResult['js']=array_merge($aResult['js'],$aRule['js']['include']);
					
					/**
					 * Преобразование CSS
					 */
					if(isset($aRule['css']['empty']) && $aRule['css']['empty']) $aResult['css']=array();
					if(isset($aRule['css']['exclude']) && is_array($aRule['css']['exclude'])) $aResult['css']=array_diff($aResult['css'],$aRule['css']['exclude']);
					if(isset($aRule['css']['include']) && is_array($aRule['css']['include'])) $aResult['css']=array_merge($aResult['css'],$aRule['css']['include']);
					
					/**
					 * Продолжаем поиск
					 */
					if(isset($aRule['stop'])) {
						break(2);
					}
				}
			}
		}
		
		/**
		 * Добавляем скрипты и css из массивов
		 */
		$aResult['js'] = array_values(
			array_merge(
				(array)$this->aJsInclude['prepend'],
				(array)$aResult['js'],
				(array)$this->aJsInclude['append']
			)
		);		
		$aResult['css'] = array_values(
			array_merge(
				(array)$this->aCssInclude['prepend'],
				(array)$aResult['css'],
				(array)$this->aCssInclude['append']
			)
		);
		
		/**
		 * Получаем список блоков
		 */
		$aBlocks['js'] = array_unique(
			array_map(
				create_function('$sJs','return isset($sJs["block"]) ? $sJs["block"] : null;'),
				$this->aFilesParams['js']
			)
		);
		$aBlocks['css'] = array_unique(
			array_map(
				create_function('$sCss','return isset($sCss["block"]) ? $sCss["block"] : null;'),
				$this->aFilesParams['css']
			)
		);
		
		/**
		 * Сливаем файлы в один, используя блочное разделение
		 */
		$aHeadFiles = array('js'=>array(),'css'=>array());
		
		foreach (array('js','css') as $sType) {
			/**
			 * Отдельно выделяем файлы, для которых указано отображение,
			 * привязанное к браузеру (ex. IE6, IE7)
			 */
			$aFilesHack = array_filter(
				$this->aFilesParams[$sType], 
				create_function(
					'$aParams',
					'return array_key_exists("browser",(array)$aParams);'
				)	
			);
			$aFilesHack = array_intersect(array_keys($aFilesHack),$aResult[$sType]);
			/**
			 * Исключаем эти файлы из основной выдачи
			 */
			$aResult[$sType] = array_diff($aResult[$sType],$aFilesHack);
			/**
			 * Добавляем файлы поблочно
			 */
			if($aBlocks[$sType] && count($aBlocks[$sType])) {
				foreach ($aBlocks[$sType] as $sBlock) {
					if(!$sBlock) continue;
					/**
					 * Выбираем все файлы, входящие в данный блок
					 */
					$aFiles = array_filter($this->aFilesParams[$sType],create_function('$aParams','return (isset($aParams)&&($aParams["block"]=="'.$sBlock.'"));'));					
					$aFiles = array_intersect(array_keys($aFiles),$aResult[$sType]);
					if($aFiles && count($aFiles)) {
						$aHeadFiles[$sType][] = $this->Compress($aFiles,$sType);
						/**
						 * Удаляем эти файлы из 
						 */
						$aResult[$sType] = array_diff($aResult[$sType],$aFiles);
					}
				}
			}
			/**
			 * Обрабатываем "последние" оставшиеся
			 */
			if(Config::Get("compress.{$sType}.merge")) {
				$aHeadFiles[$sType][] = $this->Compress($aResult[$sType],$sType);
			} else {
				$aHeadFiles[$sType] = array_map(array($this,'GetWebPath'),array_merge($aHeadFiles[$sType],$aResult[$sType]));
			}
			/**
			 * Добавляем файлы хаков
			 */
			if(is_array($aFilesHack) && count($aFilesHack)) $aHeadFiles[$sType] = array_merge($aHeadFiles[$sType],$aFilesHack);	
		}
		
		/**
		 * Получаем HTML код
		 */
		$aHtmlHeadFiles = $this->BuildHtmlHeadFiles($aHeadFiles);
		$this->SetHtmlHeadFiles($aHtmlHeadFiles);
		return true;
	}
	
	/**
	 * Сжимает все переданные файлы в один,
	 * использует файловое кеширование
	 *
	 * @param  array  $aFiles
	 * @param  string $sType
	 * @return array
	 */
	protected function Compress($aFiles,$sType) {
		$sCacheDir  = $this->sCacheDir."/".Config::Get('view.skin');
		$sCacheName = $sCacheDir."/".md5(serialize($aFiles).'_head').".{$sType}";
		$sPathServer = Config::Get('path.root.server');
		$sPathWeb    = Config::Get('path.root.web');
		/**
		 * Если кеш существует, то берем из кеша
		 */
		if(!file_exists($sCacheName)) {
			/**
			 * Создаем директорию для кеша текущего скина,
			 * если таковая отсутствует
			 */
			if(!is_dir($sCacheDir)){ 
				@mkdir($sCacheDir);
			}			
			/**
			 * Считываем содержимое
			 */
			ob_start();
			foreach ($aFiles as $sFile) {				
				$sFile=$this->GetServerPath($sFile);
				list($sFile,)=explode('?',$sFile,2);
				/**
				 * Если файл существует, обрабатываем
				 */
				if(file_exists($sFile)) { 
					$sFileContent = file_get_contents($sFile);
					if($sType=='css'){ 
						$sFileContent = $this->ConvertPathInCss($sFileContent,$sFile);
						$sFileContent = $this->CompressCss($sFileContent);
					} elseif($sType=='js') {
						$sFileContent = $this->CompressJs($sFileContent);
					}
					print $sFileContent;
				}
			}
			$sContent = ob_get_contents();
			ob_end_clean();
			
			/**
			 * Создаем новый файл и сливаем туда содержимое
			 */			
			file_put_contents($sCacheName,$sContent);
			@chmod($sCacheName, 0766);
		}
		/**
		 * Возвращаем имя файла, заменяя адрес сервера на веб-адрес
		 */
		return $this->GetWebPath($sCacheName);
	}

	/**
	 * Выполняет преобразование CSS файлов
	 *
	 * @param  string $sContent
	 * @return string 
	 */
	protected function CompressCss($sContent) {
		$this->InitCssCompressor();
		if(!$this->oCssCompressor) return $sContent;
		/**
		 * Парсим css и отдаем обработанный результат
		 */
		$this->oCssCompressor->parse($sContent);
	    return $this->oCssCompressor->print->plain();
	}
	
	/**
	 * Конвертирует относительные пути в css файлах в абсолютные
	 *
	 * @param  string $content
	 * @param  string $path
	 * @return string
	 */
	protected function ConvertPathInCss($sContent,$sPath) {
		preg_match_all( "/url\((.*?)\)/is",$sContent,$aMatches);
		if(count($aMatches[1])==0) return $sContent;

		/**
		 * Обрабатываем список файлов
		 */
		$aFiles = array_unique($aMatches[1]);
		$sDir = dirname($sPath)."/";		
		
		foreach($aFiles as $sFilePath) {
			/**
			 * Don't touch data URIs
			 */
			if(strstr($sFilePath,"data:")) {
				continue;
			}
			$sFilePathAbsolute = preg_replace("@'|\"@","",trim($sFilePath));
			/**
			 * Если путь является абсолютным, необрабатываем
			 */
			if(substr($sFilePathAbsolute,0,1) == "/" || substr($sFilePathAbsolute,0,5) == "http:") {
				continue;
			}
			/**
			 * Обрабатываем относительный путь
			 */
			$sFilePathAbsolute = $this->GetWebPath(realpath($sDir.$sFilePathAbsolute));
			/**
			 * Заменяем относительные пути в файле на абсолютные
			 */
			$sContent = str_replace($sFilePath,$sFilePathAbsolute,$sContent);
		}
		return $sContent;
	}

	/**
	 * Выполняет преобразование JS файла
	 *
	 * @param  string $sContent
	 * @return string
	 */
	protected function CompressJs($sContent) {
		$sContent = (Config::Get('compress.js.use')) 
			? JSMin::minify($sContent)
			: $sContent;
		/**
		 * Добавляем разделитель в конце файла 
		 * с расчетом на возможное их слияние в будущем
		 */
		return rtrim($sContent,";").";".PHP_EOL;
	}
	
	/**
	 * Преобразует абсолютный путь к файлу в WEB-вариант
	 *
	 * @param  string $sFile
	 * @return string
	 */
	protected function GetWebPath($sFile) {
		return str_replace(
			str_replace(DIRECTORY_SEPARATOR,'/',Config::Get('path.root.server')),
			Config::Get('path.root.web'),
			str_replace(DIRECTORY_SEPARATOR,'/',$sFile)
		);
	}
	/**
	 * Преобразует WEB-путь файла в серверный вариант
	 *
	 * @param  string $sFile
	 * @return string
	 */
	protected function GetServerPath($sFile) {
		/**
		 * Убираем из путей www
		 */
		$sFile = str_replace('//www.','//',$sFile);
		$sPathWeb  = str_replace('//www.','//',Config::Get('path.root.web'));
		/**
		 * Производим замену
		 */
		$sFile=str_replace($sPathWeb,Config::Get('path.root.server'),$sFile);
		return str_replace('/',DIRECTORY_SEPARATOR,$sFile);
	}	
		
	/**
	 * Строит HTML код по переданному массиву файлов
	 *
	 * @param  array  $aFileList
	 * @return string
	 */
	protected function BuildHtmlHeadFiles($aFileList) {
		$aHeader=array('js'=>'','css'=>'');

		foreach ((array)$aFileList['css'] as $sCss) {
			$aHeader['css'].=$this->WrapHtmlHack("<link rel='stylesheet' type='text/css' href='{$sCss}' />", $sCss, 'css').PHP_EOL;	
		}		
		foreach((array)$aFileList['js'] as $sJs) {
			$aHeader['js'].=$this->WrapHtmlHack("<script type='text/javascript' src='{$sJs}'></script>",$sJs,'js').PHP_EOL;
		}
		
		return $aHeader;
	}

	/**
	 * Обрамляет HTML код в браузер-хак (ex., [if IE 6])
	 * 
	 * @param  string $sHtml
	 * @param  string $sFile
	 * @param  string $sType (js|css)
	 * 
	 * @return string
	 */
	protected function WrapHtmlHack($sHtml,$sFile,$sType) {
		if(!isset($this->aFilesParams[$sType][$sFile]['browser'])) return $sHtml;
		return "<!--[if {$this->aFilesParams[$sType][$sFile]['browser']}]>$sHtml<![endif]-->"; 
	}
	
	public function SetHtmlHeadFiles($aText) {	
		$this->aHtmlHeadFiles=$aText;
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
			'sBaseUrl' => rtrim($sBaseUrl,'/'),
			'sGetParams' => $sGetParams,
		);
		return $aPaging;
	}
	
	/**
	 * Добавить меню в контейнер
	 *
	 * @param string $sContainer
	 * @param string $sTemplatePath
	 */
	public function AddMenu($sContainer, $sTemplate) {
		$this->aMenu[strtolower($sContainer)]=$sTemplate;
	}
	/**
	 * Компилирует меню по контейнерам
	 *
	 * @return null
	 */
	protected function BuildMenu() {
		foreach ($this->aMenu as $sContainer=>$sTemplate) {
			$this->aMenuFetch[$sContainer]=$this->Fetch($sTemplate);
		}
	}
	
	/**
	 * Загружаем переменные в шаблон при завершении модуля
	 *
	 */
	public function Shutdown() {		
		$this->SortBlocks();
		/**
		 * Добавляем JS и CSS по предписанным правилам
		 */
		$this->BuildHeadFiles();
		$this->VarAssign();
		/**
		 * Рендерим меню для шаблонов и передаем контейнеры в шаблон
		 */
		$this->BuildMenu();
		$this->MenuVarAssign();		
	}
}
?>