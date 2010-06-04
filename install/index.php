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

error_reporting(E_ALL);
set_time_limit(0);
define('LS_VERSION','0.4.1');

class Install {
	/**
	 * Название первого шага (используется, если другое не указано)
	 *
	 * @var string
	 */
	const INSTALL_DEFAULT_STEP = 'Start';
	/**
	 * Ключ сессии для хранения название следующего шага
	 *
	 * @var string
	 */
	const SESSSION_KEY_STEP_NAME = 'livestreet_install_step';
	/**
	 * Название файла локальной конфигурации
	 * 
	 * @var string
	 */
	const LOCAL_CONFIG_FILE_NAME = 'config.local.php';
	/**
	 * Передача этого ключа как параметра, указавает функции извлечения параметра
	 * запросить значение переменной сначала из сессии, в случае не нахождения нужного
	 * ключа - установить значение по умолчанию. 
	 * 
	 * Используется в фукнциях Assign(), GetRequest().
	 *
	 * @see $this->Assign()
	 * @see $this->GetRequest()
	 * @var string
	 */
	const GET_VAR_FROM_SESSION = 'get';
	/**
	 * Передача этого ключа как параметра, указавает функции предварительно сохранить
	 * переменную в сессию с одноименным ключем.
	 * 
	 * Используется в фукнциях Assign(), GetRequest().
	 *
	 * @see $this->Assign()
	 * @see $this->GetRequest()
	 * @var string
	 */	
	const SET_VAR_IN_SESSION = 'set';
	/**
	 * Массив разрешенных шагов инсталяции
	 *
	 * @var array
	 */
	var $aSteps = array(0=>'Start',1=>'Db',2=>'Admin',3=>'End',4=>'Extend',5=>'Finish');
	/**
	 * Шаги в обычном режиме инсталляции
	 *
	 * @var array
	 */
	var $aSimpleModeSteps = array('Start','Db','Admin','End');
	/**
	 * Количество шагов, которые необходимо указывать в инсталляционных параметрах
	 * 
	 * @var int
	 */
	var $iStepCount = null;
	/**
	 * Массив сообщений для пользователя
	 *
	 * @var array
	 */
	var $aMessages = array();
	/**
	 * Директория с шаблонами
	 *
	 * @var string
	 */
	var $sTemplatesDir = 'templates';
	/**
	 * Директория с языковыми файлами инсталлятора
	 *
	 * @var string
	 */
	var $sLangInstallDir = 'language';	
	/**
	 * Массив с переменными шаблонизатора
	 *
	 * @var array
	 */
	var $aTemplateVars = array(
		'___CONTENT___' => '',
		'___FORM_ACTION___' => '',
		'___NEXT_STEP_DISABLED___' => '',
		'___NEXT_STEP_DISPLAY___' => 'block',
		'___PREV_STEP_DISABLED___' => '',
		'___PREV_STEP_DISPLAY___' => 'block',
		'___SYSTEM_MESSAGES___' => '',
		'___INSTALL_VERSION___' => LS_VERSION,
	);
	/**
	 * Описание требований для успешной инсталяции
	 *
	 * @var array
	 */
	var $aValidEnv = array(
        'safe_mode'  => array ('0','off',''), 
        'register_globals' => array ('0','off',''), 
        'allow_url_fopen' => array ('1','on'), 
        'UTF8_support' => '1', 
        'http_input' => array ('','pass'), 
        'http_output' => array ('0','pass'), 
        'func_overload' => array ('0','4', 'no overload'), 
    );
    /**
     * Директория, в которой хранятся конфиг-файлы
     *
     * @var string
     */
    var $sConfigDir="";
    /**
     * Директория хранения скинов сайта 
     *
     * @var string
     */
    var $sSkinDir="";
    /**
     * Директория хранения языковых файлов движка
     *
     * @var string
     */
    var $sLangDir="";
    /**
     * Текущий язык инсталлятора
     *
     * @var string
     */
    var $sLangCurrent = '';
    /**
     * Язык инсталлятора, который будет использован по умолчанию
     *
     * @var string
     */
    var $sLangDefault = 'russian';
    /**
     * Языковые текстовки
     *
     * @var array
     */
    var $aLang = array();    
    /**
     * Инициализация основных настроек
     *
     */
    function __construct() {
    	$this->sConfigDir = dirname(__FILE__).'/../config';
    	$this->sSkinDir   = dirname(__FILE__).'/../templates/skin';
    	$this->sLangDir   = dirname(__FILE__).'/../templates/language';
    	/**
    	 * Загружаем языковые файлы
    	 */
    	$this->LoadLanguageFile($this->sLangDefault);
    	if($sLang=$this->GetRequest('lang')) {
    		$this->sLangCurrent = $sLang;
    		if($this->sLangCurrent!=$this->sLangDefault) $this->LoadLanguageFile($this->sLangCurrent);
    	}
    	/**
    	 * Передаем языковые тикеты во вьювер
    	 */
    	foreach ($this->aLang as $sKey=>$sItem) {
    		$this->Assign("lang_{$sKey}",$sItem);
    	}
    }
    /**
     * Подгружает указанный языковой файл и записывает поверх существующего языкового массива
     *
     * @access protected
     * @param  string $sLang
     */
    function LoadLanguageFile($sLang) {
    	$sFilePath=$this->sLangInstallDir.'/'.$sLang.'.php';
    	if(!file_exists($sFilePath)) return false;
    	
    	$aLang = include($sFilePath);
    	$this->aLang = array_merge($this->aLang,$aLang);
    }
    /**
     * Возвращает языковую текстовку
     *
     * @param  string $sKey
     * @param  array  $aParams
     * @return string
     */
    function Lang($sKey,$aParams=array()) {
    	if(!array_key_exists($sKey,$this->aLang))
    		return 'Unknown language key';
    	
    	$sValue=$this->aLang[$sKey];
    	if(count($aParams)==0) return $sValue;
    	
    	foreach ($aParams as $k=>$v) {
    		$sValue=str_replace("%%{$k}%%",$v,$sValue);
    	}
    	return $sValue;
    }
	/**
	 * Вытягивает переменную из сессии
	 *
	 * @param  string $sKey
	 * @return mixed
	 */
	function GetSessionVar($sKey,$mDefault=null) {
		return array_key_exists($sKey,$_SESSION) ? unserialize($_SESSION[$sKey]) : $mDefault;
	}
	/**
	 * Вкладывает переменную в сессию
	 *
	 * @param  string $sKey
	 * @param  mixed  $mVar
	 * @return bool
	 */
	function SetSessionVar($sKey,$mVar) {
		$_SESSION[$sKey] = serialize($mVar);
		return true;
	}
	/**
	 * Уничтожает переменную в сессии
	 *
	 * @param  string $sKey
	 * @return bool
	 */
	function DestroySessionVar($sKey) {
		if(!array_key_exists($sKey,$_SESSION)) return false;
		
		unset($_SESSION[$sKey]);
		return true;
	}
	
	/**
	 * Выполняет рендеринг указанного шаблона
	 *
	 * @param  string $sTemplateName
	 * @return string
	 */
	function Fetch($sTemplateName) {
		if(!file_exists($this->sTemplatesDir.'/'.$sTemplateName)) return false;
		
		$sTemplate = file_get_contents($this->sTemplatesDir.'/'.$sTemplateName);
		return $this->FetchString($sTemplate);
	}
	/**
	 * Выполняет рендеринг строки
	 *
	 * @param  string $sTempString
	 * @return string
	 */
	function FetchString($sTempString) {
		return str_replace(array_keys($this->aTemplateVars),array_values($this->aTemplateVars),$sTempString);		
	}
	/**
	 * Добавляет переменную для отображение в шаблоне.
	 * 
	 * Если параметр $sFromSession установлен в значение GET, 
	 * то переменная сначала будет запрошена из сессии.
	 * 
	 * Если параметр $sFromSession установлен в значение SET, 
	 * то переменная сначала вложена в сессию с одноименным ключем.
	 *
	 * @param string $sName
	 * @param string $sValue
	 * @param string $sGetFromSession
	 */
	function Assign($sName,$sValue,$sFromSession=null) {
		if($sFromSession==self::GET_VAR_FROM_SESSION) $sValue=$this->GetSessionVar($sName,$sValue);
		if($sFromSession==self::SET_VAR_IN_SESSION) $this->SetSessionVar($sName,$sValue);
		
		$this->aTemplateVars['___'.strtoupper($sName).'___'] = $sValue;
	}
	/**
	 * Выполняет рендер layout`а (двухуровневый)
	 *
	 * @param  string $sTemplate
	 * @return null
	 */
	function Layout($sTemplate) {
		if(!$sLayoutContent = $this->Fetch($sTemplate)) {
			return false;
		}
		/**
		 * Рендерим сообщения по списку
		 */
		if(count($this->aMessages)) {
			/**
			 * Уникализируем содержимое списка сообщений
			 */
			$aMessages = array();
			foreach ($this->aMessages as &$sMessage) {
				if(array_key_exists('type',$sMessage) and array_key_exists('text',$sMessage)) {
					$aMessages[$sMessage['type']][md5(serialize($sMessage))] = "<b>".ucfirst($sMessage['type'])."</b>: ".$sMessage['text'];				
				}
				unset($sMessage);
			}
			$this->aMessages = $aMessages;
			
			$sMessageContent = "";
			foreach ($this->aMessages as $sType => $aMessageTexts) {
				$this->Assign('message_style_class', $sType);
				$this->Assign('message_content', implode('<br />',$aMessageTexts));
				$sMessageContent.=$this->Fetch('message.tpl');
			}
			$this->Assign('system_messages',$sMessageContent);
		}
		
		$this->Assign('content', $sLayoutContent);
		print $this->Fetch('layout.tpl');
	}
	
	/**
	 * Сохранить данные в конфиг-файл
	 *
	 * @param  string $sName
	 * @param  string $sVar
	 * @param  string $sPath
	 * @return bool
	 */
	function SaveConfig($sName,$sVar,$sPath) {
		if(!file_exists($sPath)) {
			$this->aMessages[] = array('type'=>'error', 'text'=>$this->Lang('config_file_not_exists',array('path'=>$sPath)));			
			return false;
		}
		if(!is_writeable($sPath)) { 
			$this->aMessages[] = array('type'=>'error', 'text'=>$this->Lang('config_file_not_writable',array('path'=>$sPath)));
			return false; 
		}
		
		$sConfig = file_get_contents($sPath);
		$sName   = '$config[\''.implode('\'][\'', explode('.',$sName)).'\']';
		$sVar    = $this->ConvertToString($sVar);
		
		/**
		 * Если переменная уже определена в конфиге, 
		 * то меняем значение.
		 */
		if(substr_count($sConfig, $sName)) {
			$sConfig=preg_replace("~".preg_quote($sName).".+;~Ui", $sName.' = '.$sVar.';', $sConfig);
		} else {
			$sConfig=str_replace('return $config;', $sName.' = '.$sVar.';'.PHP_EOL.'return $config;', $sConfig);
		}
		file_put_contents($sPath,$sConfig);
		return true;
	}
	/**
	 * Преобразует переменную в формат для записи в текстовый файл
	 *
	 * @param  mixed $mVar
	 * @return string
	 */
	function ConvertToString($mVar) {
		switch(true) {
			case is_string($mVar):
				return "'".addslashes($mVar)."'";
					
			case is_bool($mVar):
				return ($mVar)?"true":"false";
				
			case is_array($mVar):
				$sArrayString="";
				foreach($mVar as $sKey=>$sValue) {
					$sArrayString .= "'{$sKey}'=>".$this->ConvertToString($sValue).",";
				}
				return "array(".$sArrayString.")";
				
			default:	
			case is_numeric($mVar):
				return "'".(string)$mVar."'";
		}
	}
	
	/**
	 * Получает значение переданных параметров
	 *
	 * @param  string $sName
	 * @param  mixed  $default
	 * @return mixed
	 */
	function GetRequest($sName,$default=null,$bSession=null) {		
		if (array_key_exists($sName,$_REQUEST)) {
			$sResult = (is_string($_REQUEST[$sName])) 
				? trim(stripslashes($_REQUEST[$sName]))
				: $_REQUEST[$sName];
		} else {
			$sResult = ($bSession==self::GET_VAR_FROM_SESSION)
				? $this->GetSessionVar($sName,$default)
				: $default;
		}
		/**
		 * При необходимости сохраняем в сессию
		 */
		if($bSession==self::SET_VAR_IN_SESSION) $this->SetSessionVar($sName,$sResult);
		
		return $sResult;
	}	
	
	/**
	 * Функция отвечающая за проверку входных параметров
	 * и передающая управление на фукнцию текущего шага
	 *
	 * @call $this->Step{__Name__} 
	 */
	function Run($sStepName=null) {
		if(is_null($sStepName)){ 
			$sStepName = $this->GetSessionVar(self::SESSSION_KEY_STEP_NAME, self::INSTALL_DEFAULT_STEP);
		} else {
			$this->SetSessionVar(self::SESSSION_KEY_STEP_NAME,$sStepName);
		}
		
		if(!in_array($sStepName,$this->aSteps)) die('Unknown step');
		
		$iKey = array_search($sStepName,$this->aSteps);
		/**
		 * Если была нажата кнопка "Назад", перемещаемся на шаг назад
		 */
		if($this->GetRequest('install_step_prev') && $iKey!=0) {
			$sStepName = $this->aSteps[--$iKey];
			$this->SetSessionVar(self::SESSSION_KEY_STEP_NAME,$sStepName);
		}

		$this->Assign('next_step_display', ($iKey == count($this->aSteps)-1)?'none':'block');
		$this->Assign('prev_step_display', ($iKey == 0) ? 'none' : 'block');
		
		/**
		 * Если шаг отновиться к simple mode, то корректируем количество шагов
		 */
		if(in_array($sStepName,$this->aSimpleModeSteps)) 
			$this->SetStepCount(count($this->aSimpleModeSteps));
		/**
		 * Передаем во вьевер данные для формирование таймлайна шагов
		 */
		$this->Assign('install_step_number',$iKey+1);
		$this->Assign('install_step_count',is_null($this->iStepCount) ? count($this->aSteps) : $this->iStepCount);
		/**
		 * Пердаем управление на метод текущего шага
		 */
		$sFunctionName = 'Step'.$sStepName;
		if(@method_exists($this,$sFunctionName)) { 
			$this->$sFunctionName();
		} else {
			$sFunctionName = 'Step'.self::INSTALL_DEFAULT_STEP;
			$this->SetSessionVar(self::SESSSION_KEY_STEP_NAME,self::INSTALL_DEFAULT_STEP);
			$this->$sFunctionName();
		}
	}
	/**
	 * Сохраняет данные о текущем шаге и передает их во вьювер
	 *
	 * @access protected
	 * @return bool
	 */
	function SetStep($sStepName) {
		if(!$sStepName or !in_array($sStepName,$this->aSteps)) return null;
		$this->Assign('install_step_number',array_search($sStepName,$this->aSteps)+1);		
	}
	/**
	 * Устанавливает количество шагов для отображения в шаблонах
	 *
	 * @param int $iStepCount
	 */
	function SetStepCount($iStepCount) {
		$this->iStepCount = $iStepCount;
	}
	
	/**
	 * Первый шаг инсталяции.
	 * Валидация окружения.
	 */
	function StepStart() {
		if(!$this->ValidateEnviroment()) {
			$this->Assign('next_step_disabled', 'disabled');
		} else {
			/**
			 * Прописываем в конфигурацию абсолютные пути
			 */
			$this->SavePath();

			if($this->GetRequest('install_step_next')) {
				return $this->Run('Db');
			}
		}
		$this->SetStep('Start');
		$this->Layout('steps/start.tpl');
	}
	/**
	 * Запрос данных соединения с базой данных.
	 * Запись полученных данных в лог.
	 */	
	function StepDb() {
		if(!$this->GetRequest('install_db_params')) {
			/**
			 * Получаем данные из сессии (если они туда были вложены на предыдущих итерациях шага)
			 */
			$this->Assign('install_db_server',   'localhost', self::GET_VAR_FROM_SESSION);
			$this->Assign('install_db_port',     '3306',      self::GET_VAR_FROM_SESSION);
			$this->Assign('install_db_name',     'social',    self::GET_VAR_FROM_SESSION);
			$this->Assign('install_db_user',     'root',      self::GET_VAR_FROM_SESSION);
			$this->Assign('install_db_password', '',          self::GET_VAR_FROM_SESSION);
			$this->Assign('install_db_create_check', '',      self::GET_VAR_FROM_SESSION);
			$this->Assign('install_db_prefix',   'prefix_',   self::GET_VAR_FROM_SESSION);
			$this->Assign('install_db_engine',   'InnoDB',    self::GET_VAR_FROM_SESSION);
			$this->Assign('install_db_engine_innodb', '',     self::GET_VAR_FROM_SESSION);
			$this->Assign('install_db_engine_myisam', '',     self::GET_VAR_FROM_SESSION);
			
			$this->Layout('steps/db.tpl');
			return true;
		}
		/**
		 * Если переданны данные формы, проверяем их на валидность
		 */
		$aParams['server']   = $this->GetRequest('install_db_server','');
		$aParams['port']     = $this->GetRequest('install_db_port','');
		$aParams['name']     = $this->GetRequest('install_db_name','');
		$aParams['user']     = $this->GetRequest('install_db_user','');
		$aParams['password'] = $this->GetRequest('install_db_password','');
		$aParams['create']   = $this->GetRequest('install_db_create',0);
		$aParams['convert']  = $this->GetRequest('install_db_convert',0);
		$aParams['prefix']   = $this->GetRequest('install_db_prefix','prefix_');
		$aParams['engine']   = $this->GetRequest('install_db_engine','InnoDB');

		$this->Assign('install_db_server', $aParams['server'], self::SET_VAR_IN_SESSION);
		$this->Assign('install_db_port', $aParams['port'], self::SET_VAR_IN_SESSION);
		$this->Assign('install_db_name', $aParams['name'], self::SET_VAR_IN_SESSION);
		$this->Assign('install_db_user', $aParams['user'], self::SET_VAR_IN_SESSION);
		$this->Assign('install_db_password', $aParams['password'], self::SET_VAR_IN_SESSION);
		$this->Assign('install_db_create_check', (($aParams['create'])?'checked="checked"':''), self::SET_VAR_IN_SESSION);
		$this->Assign('install_db_convert_check', (($aParams['convert'])?'checked="checked"':''), self::SET_VAR_IN_SESSION);
		$this->Assign('install_db_prefix', $aParams['prefix'], self::SET_VAR_IN_SESSION);
		$this->Assign('install_db_engine', $aParams['engine'], self::SET_VAR_IN_SESSION);
		/**
		 * Передаем данные о выделенном пункте в списке tables engine
		 */
		$this->Assign('install_db_engine_innodb', ($aParams['engine']=='InnoDB')?'selected="selected"':'', self::SET_VAR_IN_SESSION);
		$this->Assign('install_db_engine_myisam', ($aParams['engine']=='MyISAM')?'selected="selected"':'', self::SET_VAR_IN_SESSION);
		
		if($oDb=$this->ValidateDBConnection($aParams)) {
			$bSelect = $this->SelectDatabase($aParams['name'],$aParams['create']);
			/**
			 * Если не удалось выбрать базу данных, возвращаем ошибку
			 */
			if(!$bSelect) {
				$this->aMessages[] = array('type'=>'error','text'=>$this->Lang('error_db_invalid'));
				$this->Layout('steps/db.tpl');
				return false;
			}
			
			/**
			 * Сохраняем в config.local.php настройки соединения
			 */
			$sLocalConfigFile = $this->sConfigDir.'/'.self::LOCAL_CONFIG_FILE_NAME;
			if(!file_exists($sLocalConfigFile)) {
				$this->aMessages[] = array('type'=>'error','text'=>$this->Lang('error_local_config_invalid'));
				$this->Layout('steps/db.tpl');
				return false;
			}
			@chmod($sLocalConfigFile, 0777);
			
			$this->SaveConfig('db.params.host',  $aParams['server'],   $sLocalConfigFile);
			$this->SaveConfig('db.params.port',  $aParams['port'],     $sLocalConfigFile);
			$this->SaveConfig('db.params.user',  $aParams['user'],     $sLocalConfigFile);
			$this->SaveConfig('db.params.pass',  $aParams['password'], $sLocalConfigFile);
			$this->SaveConfig('db.params.dbname',$aParams['name'],     $sLocalConfigFile);
			$this->SaveConfig('db.table.prefix', $aParams['prefix'],   $sLocalConfigFile);
			
			if($aParams['engine']=='InnoDB') {
				/**
				 * Проверяем поддержку InnoDB в MySQL
				 */
				$aParams['engine']='MyISAM';				
				if($aRes = @mysql_query('SHOW ENGINES')) {
					while ($aRow = mysql_fetch_assoc($aRes)) {						
						if ($aRow['Engine']=='InnoDB' and in_array($aRow['Support'],array('DEFAULT','YES'))) {							
							$aParams['engine']='InnoDB';
						}
					}					
				}
			}
			$this->SaveConfig('db.tables.engine',$aParams['engine'],   $sLocalConfigFile);
			/**
			 * Сохраняем данные в сессию
			 */
			$this->SetSessionVar('INSTALL_DATABASE_PARAMS',$aParams);
			/**
			 * Проверяем была ли проведена установка базы в течении сеанса.
			 * Открываем .sql файл и добавляем в базу недостающие таблицы
			 */
			if($this->GetSessionVar('INSTALL_DATABASE_DONE','')!=md5(serialize(array($aParams['server'],$aParams['name'])))){
				if(!$aParams['convert']) {
					$aRes=$this->CreateTables('sql.sql',$aParams);
					if ($aRes) {
						list($bResult,$aErrors) = array_values($aRes);
						if(!$bResult) {
							foreach($aErrors as $sError) $this->aMessages[] = array('type'=>'error','text'=>$sError);
							$this->Layout('steps/db.tpl');
							return false;
						}
					} else {
						return $this->StepAdmin();
					}
				} else {
					/**
					 * Если указана конвертация старой базы данных
					 */
					list($bResult,$aErrors) = array_values($this->ConvertDatabase('convert.sql',$aParams));
					if(!$bResult) {
						foreach($aErrors as $sError) $this->aMessages[] = array('type'=>'error','text'=>$sError);
						$this->Layout('steps/db.tpl');
						return false;
					}
				}
			}
			/**
			 * Сохраняем в сессии информацию о том, что преобразование базы данных уже было выполнено.
			 * При этом сохраняем хеш сервера и названия базы данных, для последующего сравнения.
			 */
			$this->SetSessionVar('INSTALL_DATABASE_DONE',md5(serialize(array($aParams['server'],$aParams['name']))));
			/**
			 * Передаем управление на следующий шаг
			 */
			$this->aMessages[] = array('type'=>'notice','text'=>$this->Lang('ok_db_created'));
			return $this->StepAdmin();
		} else {
			$this->SetStep('Db');
			$this->Layout('steps/db.tpl');
			return false;
		}
	}
	/**
	 * Запрос данных администратора и сохранение их в базе данных
	 * 
	 */
	function StepAdmin() {
		$this->SetSessionVar(self::SESSSION_KEY_STEP_NAME,'Admin');
		$this->SetStep('Admin');
		/**
		 * Передаем данные из запроса во вьювер, сохраняя значение в сессии
		 */
		$this->Assign('install_admin_login', $this->GetRequest('install_admin_login','admin',self::GET_VAR_FROM_SESSION), self::SET_VAR_IN_SESSION);
		$this->Assign('install_admin_mail', $this->GetRequest('install_admin_mail','admin@admin.adm',self::GET_VAR_FROM_SESSION), self::SET_VAR_IN_SESSION);
		/**
		 * Если данные формы не были отправлены, передаем значения по умолчанию
		 */
		if(!$this->GetRequest('install_admin_params',false)) {
			return $this->Layout('steps/admin.tpl');
		}
		/**
		 * Проверяем валидность введенных данных
		 */
		list($bResult,$aErrors) = $this->ValidateAdminFields();
		if(!$bResult) {
			foreach($aErrors as $sError) $this->aMessages[] = array('type'=>'error','text'=>$sError);
			$this->Layout('steps/admin.tpl');
			return false;			
		}
		/**
		 * Подключаемся к базе данных и сохраняем новые данные администратора
		 */
		$aParams = $this->GetSessionVar('INSTALL_DATABASE_PARAMS');
		if(!$this->ValidateDBConnection($aParams)) {
			$this->aMessages[] = array('type'=>'error','text'=>$this->Lang('error_db_connection_invalid'));
			$this->Layout('steps/admin.tpl');
			return false;
		}
		$this->SelectDatabase($aParams['name']);
		
		$bUpdated = $this->UpdateDBUser(
			$this->GetRequest('install_admin_login'),
			$this->GetRequest('install_admin_pass'),
			$this->GetRequest('install_admin_mail'),
			$aParams['prefix']
		);
		if(!$bUpdated) {
			$this->aMessages[] = array('type'=>'error','text'=>$this->Lang('error_db_saved').'<br />'.mysql_error());
			$this->Layout('steps/admin.tpl');
			return false;	
		}
		/**
		 * Обновляем данные о пользовательском блоге
		 */
		$this->UpdateUserBlog("Blog by ".$this->GetRequest('install_admin_login'),$aParams['prefix']);
		
		/**
		 * Передаем управление на следующий шаг
		 */
		return $this->StepEnd();
	}
	/**
	 * Завершающий этап. Переход в расширенный режим
	 */
	function StepEnd() {
		$this->SetStep('End');
		$this->Assign('next_step_display','none');
		$this->SetSessionVar(self::SESSSION_KEY_STEP_NAME,'End');
		/**
		 * Если пользователь выбрал расширенный режим, переводим на новый шаг
		 */
		return ($this->GetRequest('install_step_extend')) 
			? $this->StepExtend() 
			: $this->Layout('steps/end.tpl');
	}
	/**
	 * Расширенный режим ввода дополнительных настроек.
	 */
	function StepExtend() {
		/**
		 * Выводим на экран кнопку @Next
		 */
		$this->Assign('next_step_display','block');
		/**
		 * Сохраняем в сессию название текущего шага
		 */
		$this->SetSessionVar(self::SESSSION_KEY_STEP_NAME,'Extend');
		$this->SetStep('Extend');
		/**
		 * Получаем значения запрашиваемых данных либо устанавливаем принятые по умолчанию
		 */
		$aParams['install_view_name']       = $this->GetRequest('install_view_name','LiveStreet - бесплатный движок социальной сети',self::GET_VAR_FROM_SESSION);
		$aParams['install_view_description']= $this->GetRequest('install_view_description','LiveStreet - официальный сайт бесплатного движка социальной сети',self::GET_VAR_FROM_SESSION);
		$aParams['install_view_keywords']   = $this->GetRequest('install_view_keywords','движок, livestreet, блоги, социальная сеть, бесплатный, php',self::GET_VAR_FROM_SESSION);
		$aParams['install_view_skin']       = $this->GetRequest('install_view_skin','new',self::GET_VAR_FROM_SESSION);
		
		$aParams['install_mail_sender']     = $this->GetRequest('install_mail_sender',$this->GetSessionVar('install_admin_mail','rus.engine@gmail.com'),self::GET_VAR_FROM_SESSION);
		$aParams['install_mail_name']       = $this->GetRequest('install_mail_name','Почтовик LiveStreet',self::GET_VAR_FROM_SESSION);
		
		$aParams['install_general_close']  = (bool)$this->GetRequest('install_general_close',false,self::GET_VAR_FROM_SESSION);
		$aParams['install_general_invite'] = (bool)$this->GetRequest('install_general_invite',false,self::GET_VAR_FROM_SESSION);
		$aParams['install_general_active'] = (bool)$this->GetRequest('install_general_active',false,self::GET_VAR_FROM_SESSION);
		
		$aParams['install_lang_current']    = $this->GetRequest('install_lang_current','russian',self::GET_VAR_FROM_SESSION);
		$aParams['install_lang_default']    = $this->GetRequest('install_lang_default','russian',self::GET_VAR_FROM_SESSION);
		
		/**
		 * Передаем параметры во Viewer
		 */
		foreach($aParams as $sName=>$sParam) {
			/**
			 * Если передано булево значение, значит это чек-бокс
			 */
			if(!is_bool($sParam)) {
				$this->Assign($sName,trim($sParam));
			} else {
				$this->Assign($sName.'_check',($sParam)?'checked':'');
			}
		}
		/**
		 * Передаем во вьевер список доступных языков
		 */
		$aLangs = $this->GetLangList();
		$sLangOptions = "";
		foreach ($aLangs as $sLang) {
			$this->Assign('language_array_item',$sLang);
			$this->Assign('language_array_item_selected', ($aParams['install_lang_current']==$sLang)?'selected="selected"':'');			
			$sLangOptions.=$this->FetchString("<option value='___LANGUAGE_ARRAY_ITEM___' ___LANGUAGE_ARRAY_ITEM_SELECTED___>___LANGUAGE_ARRAY_ITEM___</option>");
		}
		$this->Assign('install_lang_options',$sLangOptions);
		/**
		 * Передаем во вьевер список доступных языков для дефолтного определения
		 */
		$sLangOptions = "";
		foreach ($aLangs as $sLang) {
			$this->Assign('language_array_item',$sLang);
			$this->Assign('language_array_item_selected', ($aParams['install_lang_default']==$sLang)?'selected="selected"':'');			
			$sLangOptions.=$this->FetchString("<option value='___LANGUAGE_ARRAY_ITEM___' ___LANGUAGE_ARRAY_ITEM_SELECTED___>___LANGUAGE_ARRAY_ITEM___</option>");
		}
		$this->Assign('install_lang_default_options',$sLangOptions);
		/**
		 * Передаем во вьевер список доступных скинов
		 */
		$aSkins = $this->GetSkinList();
		$sSkinOptions = "";
		foreach ($aSkins as $sSkin) {
			$this->Assign('skin_array_item',$sSkin);
			$this->Assign('skin_array_item_selected', ($aParams['install_view_skin']==$sSkin)?'selected="selected"':'');			
			$sSkinOptions.=$this->FetchString("<option value='___SKIN_ARRAY_ITEM___' ___SKIN_ARRAY_ITEM_SELECTED___>___SKIN_ARRAY_ITEM___</option>");
		}
		$this->Assign('install_view_skin_options',$sSkinOptions);		
		
		/**
		 * Если были переданные данные формы, то обрабатываем добавление
		 */
		if($this->GetRequest('install_extend_params')) {
			$bOk = true;
			$sLocalConfigFile = $this->sConfigDir.'/'.self::LOCAL_CONFIG_FILE_NAME;
			
			/**
			 * Название сайта
			 */
			if($aParams['install_view_name'] && strlen($aParams['install_view_name'])>2){
				if($this->SaveConfig('view.name',$aParams['install_view_name'],$sLocalConfigFile))
					$this->SetSessionVar('install_view_name',$aParams['install_view_name']);
			} else {
				$bOk = false;
				$this->aMessages[] = array('type'=>'error','text'=>$this->Lang('site_name_invalid'));
			}
			/**
			 * Описание сайта
			 */			
			if($aParams['install_view_description']){
				if($this->SaveConfig('view.description',$aParams['install_view_description'],$sLocalConfigFile))
				 $this->SetSessionVar('install_view_description',$aParams['install_view_description']);
			} else {
				$bOk = false;
				$this->aMessages[] = array('type'=>'error','text'=>$this->Lang('site_description_invalid'));
			}
			/**
			 * Ключевые слова
			 */
			if($aParams['install_view_keywords'] && strlen($aParams['install_view_keywords'])>2){
				if($this->SaveConfig('view.keywords',$aParams['install_view_keywords'],$sLocalConfigFile))
					$this->SetSessionVar('install_view_keywords',$aParams['install_view_keywords']);				
			} else {
				$bOk = false;
				$this->aMessages[] = array('type'=>'error','text'=>$this->Lang('site_keywords_invalid'));
			}
			/**
			 * Название шаблона оформления
			 */
			if($aParams['install_view_skin'] && strlen($aParams['install_view_skin'])>1){
				if($this->SaveConfig('view.skin',$aParams['install_view_skin'],$sLocalConfigFile))
					$this->SetSessionVar('install_view_skin',$aParams['install_view_skin']);
			} else {
				$bOk = false;
				$this->aMessages[] = array('type'=>'error','text'=>'skin_name_invalid');
			}
			
			/**
			 * E-mail, с которого отправляются уведомления
			 */
			if($aParams['install_mail_sender'] && strlen($aParams['install_mail_sender'])>5){
				if($this->SaveConfig('sys.mail.from_email',$aParams['install_mail_sender'],$sLocalConfigFile))
					$this->SetSessionVar('install_mail_sender',$aParams['install_mail_sender']);
			} else {
				$bOk = false;
				$this->aMessages[] = array('type'=>'error','text'=>$this->Lang('mail_sender_invalid'));
			}
			/**
			 * Имя, от которого отправляются уведомления
			 */
			if($aParams['install_mail_name'] && strlen($aParams['install_mail_name'])>1){
				if($this->SaveConfig('sys.mail.from_name',$aParams['install_mail_name'],$sLocalConfigFile))
					$this->SetSessionVar('install_mail_name',$aParams['install_mail_name']);
			} else {
				$bOk = false;
				$this->aMessages[] = array('type'=>'error','text'=>$this->Lang('mail_name_invalid'));
			}

			/**
			 * Использовать закрытый режим работы сайта
			 */
			if($this->SaveConfig('general.close',$aParams['install_general_close'],$sLocalConfigFile))
				$this->SetSessionVar('install_general_close',$aParams['install_general_close']);
			/**
			 * Использовать активацию при регистрации
			 */
			if($this->SaveConfig('general.reg.activation',$aParams['install_general_active'],$sLocalConfigFile))
				$this->SetSessionVar('install_general_active',$aParams['install_general_active']);
			/**
			 * Использоватьт режим регистрации по приглашению
			 */
			if($this->SaveConfig('general.reg.invite',$aParams['install_general_invite'],$sLocalConfigFile))
				$this->SetSessionVar('install_general_invite',$aParams['install_general_invite']);
					
			/**
			 * Текущий язык
			 */
			if($aParams['install_lang_current'] && strlen($aParams['install_lang_current'])>1){
				if($this->SaveConfig('lang.current',$aParams['install_lang_current'],$sLocalConfigFile)) {
					$this->SetSessionVar('install_lang_current',$aParams['install_lang_current']);
					/**
					 * Если выбран русский язык, то перезаписываем название блога
					 */
					if($aParams['install_lang_current']=='russian'){ 
						$aDbParams = $this->GetSessionVar('INSTALL_DATABASE_PARAMS');
						$oDb = $this->ValidateDBConnection($aDbParams);
						
						if($oDb and $this->SelectDatabase($aDbParams['name'])) $this->UpdateUserBlog("Блог им. ".$this->GetSessionVar('install_admin_login'),$aDbParams['prefix']);
					}
				}
			} else {
				$bOk = false;
				$this->aMessages[] = array('type'=>'error','text'=>$this->Lang('lang_current_invalid'));
			}
			/**
			 * Язык, который будет использоваться по умолчанию
			 */
			if($aParams['install_lang_default'] && strlen($aParams['install_lang_default'])>1){
				if($this->SaveConfig('lang.default',$aParams['install_lang_default'],$sLocalConfigFile))
					$this->SetSessionVar('install_lang_default',$aParams['install_lang_default']);
			} else {
				$bOk = false;
				$this->aMessages[] = array('type'=>'error','text'=>$this->Lang('lang_default_invalid'));
			}		
		}
		
		return ($this->GetRequest('install_step_next')) 
					? $this->StepFinish() 
					: $this->Layout('steps/extend.tpl');
	}
	/**
	 * Окончание работы инсталлятора. Предупреждение о необходимости удаления.
	 */
	function StepFinish() {
		$this->SetStep('Finish');
		$this->Assign('next_step_display','none');
		$this->SetSessionVar(self::SESSSION_KEY_STEP_NAME,'Finish');
		$this->Layout('steps/finish.tpl');
	}
	/**
	 * Проверяем возможность инсталяции
	 * 
	 * @return bool
	 */
	function ValidateEnviroment() {
		$bOk = true;
		
		if(!version_compare(PHP_VERSION, '5.1.2', '>=')) {
			$bOk = false;
			$this->Assign('validate_php_version', '<span style="color:red;">'.$this->Lang('no').'</span>');			
		} else {
			$this->Assign('validate_php_version', '<span style="color:green;">'.$this->Lang('yes').'</span>');			
		}
		
		if(!in_array(strtolower(@ini_get('safe_mode')), $this->aValidEnv['safe_mode'])) {
			$bOk = false;
			$this->Assign('validate_safe_mode', '<span style="color:red;">'.$this->Lang('no').'</span>');
		} else {
			$this->Assign('validate_safe_mode', '<span style="color:green;">'.$this->Lang('yes').'</span>');			
		}

		if(@preg_match('//u', '')!=$this->aValidEnv['UTF8_support']) {
			$bOk = false;
			$this->Assign('validate_utf8', '<span style="color:red;">'.$this->Lang('no').'</span>');
		} else {
			$this->Assign('validate_utf8', '<span style="color:green;">'.$this->Lang('yes').'</span>');
		}

	    if (@extension_loaded('mbstring')){
	        $aMbInfo=mb_get_info();
			
	        if(!in_array(strtolower($aMbInfo['http_input']), $this->aValidEnv['http_input']) 
	        	or !in_array(strtolower($aMbInfo['http_output']), $this->aValidEnv['http_output']) 
	        		or !in_array(strtolower($aMbInfo['func_overload']), $this->aValidEnv['func_overload'])) {
	        			$bOk = false;
	        			$this->Assign('validate_mbstring', '<span style="color:red;">'.$this->Lang('no').'</span>');
	        } else {
	        	$this->Assign('validate_mbstring', '<span style="color:green;">'.$this->Lang('yes').'</span>');
	        }
	    } else {
   			$bOk = false;
   			$this->Assign('validate_mbstring', '<span style="color:red;">'.$this->Lang('no').'</span>');	    	
	    }
	    
	    if (@extension_loaded('SimpleXML')){
	        $this->Assign('validate_simplexml', '<span style="color:green;">'.$this->Lang('yes').'</span>');	        
	    } else {
   			$bOk = false;
   			$this->Assign('validate_simplexml', '<span style="color:red;">'.$this->Lang('no').'</span>');	    	
	    }
	    
	    $sLocalConfigPath = $this->sConfigDir.'/config.local.php';
	    if(!file_exists($sLocalConfigPath) or !is_writeable($sLocalConfigPath)) {
	    	// пытаемся создать файл локального конфига
	    	@copy($this->sConfigDir.'/config.local.php.dist',$sLocalConfigPath);
	    }
	    if(!file_exists($sLocalConfigPath) or !is_writeable($sLocalConfigPath)) {
			$bOk = false;
			$this->Assign('validate_local_config', '<span style="color:red;">'.$this->Lang('no').'</span>');
		} else {
			$this->Assign('validate_local_config', '<span style="color:green;">'.$this->Lang('yes').'</span>');			
		}
	    
		/**
		 * Проверяем доступность и достаточность прав у директории
		 * для сохранения файлового кеша, /logs, /uploads, /templates/compiled, /plugins
		 */
		$sTempDir = dirname(dirname(__FILE__)).'/tmp';
		if(!is_dir($sTempDir) or !is_writable($sTempDir)) {
			$bOk = false;
			$this->Assign('validate_local_temp', '<span style="color:red;">'.$this->Lang('no').'</span>');			
		} else {
			$this->Assign('validate_local_temp', '<span style="color:green;">'.$this->Lang('yes').'</span>');			
		}

		$sLogsDir = dirname(dirname(__FILE__)).'/logs';
		if(!is_dir($sLogsDir) or !is_writable($sLogsDir)) {
			$bOk = false;
			$this->Assign('validate_local_logs', '<span style="color:red;">'.$this->Lang('no').'</span>');			
		} else {
			$this->Assign('validate_local_logs', '<span style="color:green;">'.$this->Lang('yes').'</span>');			
		}
		
		$sUploadsDir = dirname(dirname(__FILE__)).'/uploads';
		if(!is_dir($sUploadsDir) or !is_writable($sUploadsDir)) {
			$bOk = false;
			$this->Assign('validate_local_uploads', '<span style="color:red;">'.$this->Lang('no').'</span>');			
		} else {
			$this->Assign('validate_local_uploads', '<span style="color:green;">'.$this->Lang('yes').'</span>');			
		}

		$sTemplatesDir = dirname(dirname(__FILE__)).'/templates/compiled';
		if(!is_dir($sTemplatesDir) or !is_writable($sTemplatesDir)) {
			$bOk = false;
			$this->Assign('validate_local_templates', '<span style="color:red;">'.$this->Lang('no').'</span>');			
		} else {
			$this->Assign('validate_local_templates', '<span style="color:green;">'.$this->Lang('yes').'</span>');			
		}			


		$sTemplatesCacheDir = dirname(dirname(__FILE__)).'/templates/cache';
		if(!is_dir($sTemplatesCacheDir) or !is_writable($sTemplatesCacheDir)) {
			$bOk = false;
			$this->Assign('validate_local_templates_cache', '<span style="color:red;">'.$this->Lang('no').'</span>');			
		} else {
			$this->Assign('validate_local_templates_cache', '<span style="color:green;">'.$this->Lang('yes').'</span>');			
		}

		$sPluginsDir = dirname(dirname(__FILE__)).'/plugins';
		if(!is_dir($sPluginsDir) or !is_writable($sPluginsDir)) {
			$bOk = false;
			$this->Assign('validate_local_plugins', '<span style="color:red;">'.$this->Lang('no').'</span>');			
		} else {
			$this->Assign('validate_local_plugins', '<span style="color:green;">'.$this->Lang('yes').'</span>');			
		}
		
	    return $bOk;
	}	
	/**
	 * Проверяет соединение с базой данных
	 *
	 * @param  array $aParams
	 * @return mixed
	 */
	function ValidateDBConnection($aParams) {
		$oDb = @mysql_connect($aParams['server'].':'.$aParams['port'],$aParams['user'],$aParams['password']);
		if( $oDb ) {
			/**
			 * Валидация версии MySQL сервера
			 */
			if(!version_compare(mysql_get_server_info(), '5.0.0', '>')) {
				$this->aMessages[] = array('type'=>'error','text'=>$this->Lang('valid_mysql_server'));
				return false;
			}
			
			mysql_query('set names utf8');
			return $oDb;
		}
		
		$this->aMessages[] = array('type'=>'error','text'=>$this->Lang('error_db_connection_invalid'));
		return null;
	}
	/**
	 * Выбрать базу данных (либо создать в случае необходимости).
	 *
	 * @param  string $sName
	 * @param  bool   $bCreate
	 * @return bool
	 */
	function SelectDatabase($sName,$bCreate=false) {
		if(@mysql_select_db($sName)) return true;

		if($bCreate){ 
			@mysql_query("CREATE DATABASE $sName");
			return @mysql_select_db($sName);
		} 
		return false;
	}
	/**
	 * Добавляет в базу данных необходимые таблицы
	 *
	 * @param  string $sFilePath
	 * @return array
	 */
	function CreateTables($sFilePath,$aParams) {
		$sFileQuery = @file_get_contents($sFilePath);
		if(!$sFileQuery) return array('result'=>false,'errors'=>array($this->Lang("config_file_not_exists", array('path'=>$sFilePath))));
		
		if(isset($aParams['prefix'])) $sFileQuery = str_replace('prefix_', $aParams['prefix'], $sFileQuery);
		$aQuery=explode(';',$sFileQuery);
		/**
		 * Массив для сбора ошибок
		 */
		$aErrors = array();
		/**
		 * Смотрим, какие таблицы существуют в базе данных
		 */ 
		$aDbTables = array();
		$aResult = @mysql_query("SHOW TABLES");
		if(!$aResult){  
			return array('result'=>false,'errors'=>array($this->Lang('error_db_no_data')));
		}
        while($aRow = mysql_fetch_array($aResult, MYSQL_NUM)){
			$aDbTables[] = $aRow[0];
		}
		/**
		 * Если среди таблиц БД уже есть таблица prefix_topic, то выполнять SQL-дамп не нужно
		 */
		if (in_array($aParams['prefix'].'topic',$aDbTables)) {
			return false;
		}		
		/**
		 * Выполняем запросы по очереди
		 */
		foreach($aQuery as $sQuery){
			$sQuery = trim($sQuery);
			/**
			 * Заменяем движек, если таковой указан в запросе
			 */
			if(isset($aParams['engine'])) $sQuery=str_ireplace('ENGINE=InnoDB', "ENGINE={$aParams['engine']}",$sQuery);
			
			if($sQuery!='' and !$this->IsUseDbTable($sQuery,$aDbTables)) {
				$bResult=mysql_query($sQuery);
				if(!$bResult) $aErrors[] = mysql_error();
			}
		}
		
		if(count($aErrors)==0) {
			return array('result'=>true,'errors'=>null);
		}
		return array('result'=>false,'errors'=>$aErrors);
	}
	
	/**
	 * Проверяем, нуждается ли база в конвертации или нет
	 *
	 * @param  array $aParams
	 * @return bool
	 */
	function ValidateConvertDatabase($aParams) {
		/**
		 * Проверяем, нуждается ли база в конвертации или нет
		 * Смотрим, какие таблицы существуют в базе данных
		 */ 
		$aDbTables = array();
		$aResult = @mysql_query("SHOW TABLES");
		if(!$aResult){  
			return array('result'=>false,'errors'=>array($this->Lang('error_db_no_data')));
		}
        while($aRow = mysql_fetch_array($aResult, MYSQL_NUM)){
			$aDbTables[] = $aRow[0];
		}
		/**
		 * Смотрим на наличие в базе таблицы prefix_comment
		 */
		return !in_array($aParams['prefix'].'comment',$aDbTables);
	}
	/**
	 * Конвертирует базу данных версии 0.3.1 в базу данных версии 0.4
	 *
	 * @return bool
	 */
	function ConvertDatabase($sFilePath,$aParams) {	
		if(!$this->ValidateConvertDatabase($aParams)) {
			return array('result'=>true,'errors'=>array($this->Lang("error_database_converted_already")));
		}
		
		$sFileQuery = @file_get_contents($sFilePath);
		if(!$sFileQuery) return array('result'=>false,'errors'=>array($this->Lang("config_file_not_exists", array('path'=>$sFilePath))));
		
		if(isset($aParams['prefix'])) $sFileQuery = str_replace('prefix_', $aParams['prefix'], $sFileQuery);
		$aQuery=explode(';',$sFileQuery);
		/**
		 * Массив для сбора ошибок
		 */
		$aErrors = array();	

		/**
		 * Выполняем запросы по очереди
		 */
		foreach($aQuery as $sQuery){
			$sQuery = trim($sQuery);
			/**
			 * Заменяем движек, если таковой указан в запросе
			 */
			if(isset($aParams['engine'])) $sQuery=str_ireplace('ENGINE=InnoDB', "ENGINE={$aParams['engine']}",$sQuery);
			
			if($sQuery!='') {
				$bResult=mysql_query($sQuery);
				if(!$bResult) $aErrors[] = mysql_error();
			}
		}
		/**
		 * Обновляем пути до аватаров и фото у юзеров
		 */
		$sTable=$aParams['prefix'].'user';
		if($aResults = mysql_query("SELECT * FROM {$sTable}")){ 
			while($aRow = mysql_fetch_assoc($aResults)) {
				if ($aRow['user_profile_avatar']==0) {
					$sSqlUpdate="UPDATE {$sTable} SET user_profile_avatar = NULL WHERE user_id={$aRow['user_id']}";
				} else {
					$sAvatarPath=$this->GetPathRootWeb().'/uploads/images/'.$aRow['user_id'].'/avatar_100x100.'.$aRow['user_profile_avatar_type'];
					$sAvatarPath=mysql_escape_string($sAvatarPath);
					$sSqlUpdate="UPDATE {$sTable} SET user_profile_avatar = '{$sAvatarPath}' WHERE user_id={$aRow['user_id']}";
				}				
				if(!mysql_query($sSqlUpdate)) $aErrors[] = mysql_error();
				
				if ($aRow['user_profile_foto']) {
					$sAvatarPath=$this->GetPathRootWeb().$aRow['user_profile_foto'];
					$sAvatarPath=mysql_escape_string($sAvatarPath);
					$sSqlUpdate="UPDATE {$sTable} SET user_profile_foto = '{$sAvatarPath}' WHERE user_id={$aRow['user_id']}";
					if(!mysql_query($sSqlUpdate)) $aErrors[] = mysql_error();
				}
			}			
		}
		/**
		 * Удаляем поле user_profile_avatar_type
		 */
		if(!mysql_query("ALTER TABLE  `{$sTable}` DROP  `user_profile_avatar_type`;")) $aErrors[] = mysql_error();
				
		/**
		 * Обновляем пути до аватаров у блогов
		 */
		$sTable=$aParams['prefix'].'blog';
		if($aResults = mysql_query("SELECT * FROM {$sTable}")){ 
			while($aRow = mysql_fetch_assoc($aResults)) {
				if ($aRow['blog_avatar']==0) {
					$sSqlUpdate="UPDATE {$sTable} SET blog_avatar = NULL WHERE blog_id={$aRow['blog_id']}";
				} else {
					$sAvatarPath=$this->GetPathRootWeb().'/uploads/images/'.$aRow['user_owner_id'].'/avatar_blog_'.$aRow['blog_url'].'_48x48.'.$aRow['blog_avatar_type'];
					$sAvatarPath=mysql_escape_string($sAvatarPath);
					$sSqlUpdate="UPDATE {$sTable} SET blog_avatar = '{$sAvatarPath}' WHERE blog_id={$aRow['blog_id']}";
				}				
				if(!mysql_query($sSqlUpdate)) $aErrors[] = mysql_error();
			}			
		}
		/**
		 * Удаляем поле blog_avatar_type
		 */
		if(!mysql_query("ALTER TABLE  `{$sTable}` DROP  `blog_avatar_type`;")) $aErrors[] = mysql_error();
		
				
		/**
		 * Переводим в одну таблицу vote`ы
		 */
		$aVoteTables = array(
			$aParams['prefix'].'blog_vote'=>'blog',
			$aParams['prefix'].'user_vote'=>'user',
			$aParams['prefix'].'topic_comment_vote'=>'comment'
		);
		foreach ($aVoteTables as $sTable=>$sTarget) {
			$sVoteSelect = "SELECT * FROM {$sTable} WHERE 1";
			if(!$aResults = mysql_query($sVoteSelect)){ 
				$aErrors[] = $this->Lang('error_table_select',array('table'=>$sTable));
				continue;
			}
			/**
			 * Переносим в новую таблицу с указанием target`а
			 */
			while($aRow = mysql_fetch_array($aResults, MYSQL_ASSOC)) {
				$sQuery = "INSERT INTO `{$aParams['prefix']}vote` 
							SET
								target_id = '{$aRow[$sTarget.'_id']}',
								target_type = '{$sTarget}',
								user_voter_id = '{$aRow['user_voter_id']}',
								vote_direction = '".(($aRow['vote_delta']>=0)?1:-1)."', 
								vote_value = '{$aRow['vote_delta']}',
								vote_date = '".date("Y-m-d H:i:s")."'";
				if(!mysql_query($sQuery)) $aErrors[] = mysql_error();
			}
			mysql_free_result($aResults);
		}

		/**
		 * Переводим в одну таблицу комментарии
		 */
		$sCommentIdMaxQuery = "SELECT MAX( comment_id ) AS max_id FROM {$aParams['prefix']}comment";
		/**
		 * Получаем максимальный идентификатор комментариев к топикам
		 */
		if(!$aResults = mysql_query($sCommentIdMaxQuery) ){
			$aErrors[] = $this->Lang('error_table_select',array('table'=>'comments'));
		} else {
			$aRow=mysql_fetch_row($aResults);
			$iMaxId = $aRow[0]+1;

			$sTalkCommentSelect = "SELECT * FROM {$aParams['prefix']}talk_comment";
			if(!$aResults = mysql_query($sTalkCommentSelect)){ 
				$aErrors[] = $this->Lang('error_table_select', array('table'=>'talk_comment'));
			} else {
				$iAutoIncrement = $iMaxId;
				while($aRow = mysql_fetch_array($aResults, MYSQL_ASSOC)) {
					$aRow['talk_comment_id']+=$iMaxId;
					/**
					 * Выбираем максимальный айдишник
					 */
					$iAutoIncrement = ($aRow['talk_comment_id']>$iAutoIncrement) 
						? $aRow['talk_comment_id']
						: $iAutoIncrement;
						
					$aRow['talk_comment_pid']= is_int($aRow['talk_comment_pid']) ? $aRow['talk_comment_id']+$iMaxId : "NULL"; 
					$sQuery = "INSERT INTO `{$aParams['prefix']}comment` 
								SET
									comment_id = '{$aRow['talk_comment_id']}',
									comment_pid = {$aRow['talk_comment_pid']}, 
									target_id = '{$aRow['talk_id']}',
									target_type = 'talk',
									target_parent_id = '0',
									user_id = '{$aRow['user_id']}',
									comment_text = '".mysql_real_escape_string($aRow['talk_comment_text'])."',
									comment_text_hash = '".md5($aRow['talk_comment_text'])."',
									comment_date = '{$aRow['talk_comment_date']}',
									comment_user_ip = '{$aRow['talk_comment_user_ip']}',
									comment_rating = '0',
									comment_count_vote = '0',
									comment_delete = '0',
									comment_publish = '1' ";
					if(!mysql_query($sQuery)) $aErrors[] = mysql_error();
				}
				$iAutoIncrement++;
				/**
				 * Устанавливаем в таблице новое значение авто инкремента
				 */
				@mysql_query("ALTER {$aParams['prefix']}comment AUTO_INCREMENT={$iAutoIncrement}");
				mysql_free_result($aResults);
			}
		}
		/**
		 * Обновляем количество комментариев к письмам
		 */
		$sTalkSql = "SELECT talk_id FROM {$aParams['prefix']}talk";
		if($aResults = mysql_query($sTalkSql)){
			while($aRow = mysql_fetch_assoc($aResults)) {
				$sTalkCountSql = "SELECT count(comment_id) as c FROM {$aParams['prefix']}comment WHERE `target_id`={$aRow['talk_id']} AND `target_type`='talk'";
				if($aResultsCount = mysql_query($sTalkCountSql) and $aRowCount = mysql_fetch_assoc($aResultsCount)){
					mysql_query("UPDATE {$aParams['prefix']}talk SET talk_count_comment = {$aRowCount['c']} WHERE talk_id = {$aRow['talk_id']} ");
				}
			}
		}
		/**
		 * Для каждого комментария к топику указываем соответствующий ему идентификатор блога
		 */
		$sParentUpdateQuery = "
			UPDATE `{$aParams['prefix']}comment`
			SET `target_parent_id` = 
				( SELECT blog_id FROM `{$aParams['prefix']}topic` as t WHERE t.topic_id=target_id )
			WHERE `target_type` = 'topic'
		";
		if(!mysql_query($sParentUpdateQuery)) 
			$aErrors[] = mysql_error();
		
		/**
		 * Выбираем пары взаимной дружбы и заносим в базу данынх
		 */
		$sFriendsQuery = "SELECT * FROM {$aParams['prefix']}friend";
		/**
		 * Получаем максимальный идентификатор комментариев к топикам
		 */
		if(!$aResults = mysql_query($sFriendsQuery) ){
			$aErrors[] = $this->Lang('error_freind_table_select');
		} else {
			/**
			 * Архив для хранения индексов "не использованых" строк таблицы
			 */
			$aFriends=array();
			while($aRow = mysql_fetch_array($aResults, MYSQL_ASSOC)) {
				/**
				 * Если имеется запись с френдами, стоящими в обратном порядке,
				 * то вторую запись удаляем, первую приводим к нормальным статусам
				 */
				$sRevIndex = $aRow['user_to'].'_'.$aRow['user_from'];		

				$iPosition=array_search($sRevIndex, $aFriends);			
				if($iPosition!==false) {
					/**
					 * Обновляем статусы
					 */
					if(!mysql_query("UPDATE {$aParams['prefix']}friend SET status_from=1, status_to=2 WHERE user_from='{$aRow['user_to']}' AND user_to='{$aRow['user_from']}'")) 
						$aErrors[] = mysql_error();
					/**
					 * Удаляем дубль-строку
					 */
					if(!mysql_query("DELETE FROM {$aParams['prefix']}friend WHERE user_from='{$aRow['user_from']}' AND user_to='{$aRow['user_to']}'")) 
						$aErrors[] = mysql_error();
					/**
					 * Удаляем значение из списка индексов
					 */
					unset($aFriends[$iPosition]);
				} else {
					$aFriends[] = $aRow['user_from'].'_'.$aRow['user_to'];
				}
			}
			/**
			 * Если остались индексы, удаляем соответствующие им строки
			 */
			if(count($aFriends)>0) {
				foreach ($aFriends as $sIndex) {
					list($sFrom,$sTo)=explode('_',$sIndex,2);
					if(!mysql_query("DELETE FROM {$aParams['prefix']}friend WHERE user_from='{$sFrom}' AND user_to='{$sTo}'")) 
						$aErrors[] = mysql_error();
				}
			}
			mysql_free_result($aResults);			
		}
		
		/**
		 * Конвертируем пользователей блогов в роли
		 */
		$sTable=$aParams['prefix'].'blog_user';
		mysql_query("UPDATE {$sTable} SET user_role = 1 WHERE is_moderator = 0 AND is_administrator = 0 ");
		mysql_query("UPDATE {$sTable} SET user_role = 2 WHERE is_moderator = 1 ");
		mysql_query("UPDATE {$sTable} SET user_role = 4 WHERE is_administrator = 1 ");
		/**
		 * Удаляем старые поля
		 */		
		if(!mysql_query("ALTER TABLE `{$sTable}` DROP `is_moderator`, DROP `is_administrator`;")) $aErrors[] = mysql_error();
		
		
		if(count($aErrors)==0) {
			return array('result'=>true,'errors'=>null);
		}
		return array('result'=>false,'errors'=>$aErrors);		
	}	
	/**
	 * Валидирует данные администратора
	 *
	 * @return bool;
	 */
	function ValidateAdminFields() {
		$bOk = true;
		$aErrors = array();
		
		if(!$sLogin=$this->GetRequest('install_admin_login',false) or !preg_match("/^[\da-z\_\-]{3,30}$/i",$sLogin)) {
			$bOk = false;
			$aErrors[] = $this->Lang('admin_login_invalid');
		}

		if(!$sMail=$this->GetRequest('install_admin_mail',false) or !preg_match("/^[\da-z\_\-\.\+]+@[\da-z_\-\.]+\.[a-z]{2,5}$/i",$sMail)) {
			$bOk = false;
			$aErrors[] = $this->Lang('admin_mail_invalid');
		}
		if(!$sPass=$this->GetRequest('install_admin_pass',false) or strlen($sPass)<3) {
			$bOk = false;
			$aErrors[] = $this->Lang('admin_password_invalid');
		}
		if($this->GetRequest('install_admin_repass','') != $this->GetRequest('install_admin_pass','')) {
			$bOk = false;
			$aErrors[] = $this->Lang('admin_repassword_invalid');
		}
		
		return array($bOk, $aErrors);
	}	
	/**
	 * Сохраняет данные об администраторе в базу данных
	 *
	 * @param  string $sLogin
	 * @param  string $sPassword
	 * @param  string $sMail
	 * @param  string $sPrefix
	 * @return bool
	 */
	function UpdateDBUser($sLogin,$sPassword,$sMail,$sPrefix="prefix_") {
        $sQuery = "
        	UPDATE `{$sPrefix}user`
        	SET 
        		`user_login`    = '{$sLogin}',
        		`user_mail`     = '{$sMail}',
        		`user_password` = md5('{$sPassword}')
			WHERE `user_id` = 1";

		return mysql_query($sQuery);		
	}
	/**
	 * Перезаписывает название блога в базе данных
	 *
	 * @param  string $sBlogName
	 * @param  string [$sPrefix = "prefix_"
	 * @return bool
	 */
	function UpdateUserBlog($sBlogName,$sPrefix="prefix_") {
        $sQuery = "
        	UPDATE `{$sPrefix}blog`
        	SET 
        		`blog_title`    = '".mysql_real_escape_string($sBlogName)."'
			WHERE `blog_id` = 1";

		return mysql_query($sQuery);
	}
	/**
	 * Проверяет, использует ли mysql запрос, одну из указанных в массиве таблиц
	 *
	 * @param sring $sQuery
	 * @param array $aTables
	 * @return bool
	 */
	function IsUseDbTable($sQuery,$aTables) {
		foreach($aTables as $sTable){
			if(substr_count($sQuery, "`{$sTable}`")) return true;
		}
		return false;
	}	
	/**
	 * Отдает список доступных шаблонов
	 *
	 * @return array
	 */
	function GetSkinList() {
		/**
		 * Получаем список каталогов
		 */
		$aDir=glob($this->sSkinDir.'/*', GLOB_ONLYDIR);
		
		if(!is_array($aDir)) return array();
		return array_map(create_function('$sDir', 'return basename($sDir);'),$aDir);
	}
	/**
	 * Отдает список доступных языков
	 *
	 * @return array
	 */
	function GetLangList() {
		/**
		 * Получаем список каталогов
		 */
		$aDir=glob($this->sLangDir.'/*.php');
		
		if(!is_array($aDir)) return array();
		return array_map(create_function('$sDir', 'return basename($sDir,".php");'),$aDir);
	}	
	/**
	 * Сохраняет в конфигурации абсолютные пути 
	 *
	 * @access protected
	 * @return null
	 */
	function SavePath() {
		$sLocalConfigFile = $this->sConfigDir.'/'.self::LOCAL_CONFIG_FILE_NAME;
		$this->SaveConfig('path.root.web',$this->GetPathRootWeb(), $sLocalConfigFile); 
		$this->SaveConfig('path.root.server', $this->GetPathRootServer(), $sLocalConfigFile);		
		
		$aDirs=array();
		$sDirs=trim(str_replace('http://'.$_SERVER['HTTP_HOST'],'',$this->GetPathRootWeb()),'/');
		if ($sDirs!='') {
			$aDirs=explode('/',$sDirs);
		}		
		$this->SaveConfig('path.offset_request_url', count($aDirs), $sLocalConfigFile);
	}
	
	function GetPathRootWeb() {
		return rtrim('http://'.$_SERVER['HTTP_HOST'],'/').str_replace('/install/index.php','',$_SERVER['PHP_SELF']);
	}
	
	function GetPathRootServer() {
		return rtrim(dirname(dirname(__FILE__)),'/');
	}
}

session_start();
$oInstaller = new Install;
$oInstaller->Run();
?>