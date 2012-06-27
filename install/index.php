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
define('LS_VERSION','1.0');

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
	protected $aSteps = array(0=>'Start',1=>'Db',2=>'Admin',3=>'End',4=>'Extend',5=>'Finish');
	/**
	 * Шаги в обычном режиме инсталляции
	 *
	 * @var array
	 */
	protected $aSimpleModeSteps = array('Start','Db','Admin','End');
	/**
	 * Количество шагов, которые необходимо указывать в инсталляционных параметрах
	 * 
	 * @var int
	 */
	protected $iStepCount = null;
	/**
	 * Массив сообщений для пользователя
	 *
	 * @var array
	 */
	protected $aMessages = array();
	/**
	 * Директория с шаблонами
	 *
	 * @var string
	 */
	protected $sTemplatesDir = 'templates';
	/**
	 * Директория с языковыми файлами инсталлятора
	 *
	 * @var string
	 */
	protected $sLangInstallDir = 'language';	
	/**
	 * Массив с переменными шаблонизатора
	 *
	 * @var array
	 */
	protected $aTemplateVars = array(
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
	protected $aValidEnv = array(
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
    protected $sConfigDir="";
    /**
     * Директория хранения скинов сайта 
     *
     * @var string
     */
    protected $sSkinDir="";
    /**
     * Директория хранения языковых файлов движка
     *
     * @var string
     */
    protected $sLangDir="";
    /**
     * Текущий язык инсталлятора
     *
     * @var string
     */
    protected $sLangCurrent = '';
    /**
     * Язык инсталлятора, который будет использован по умолчанию
     *
     * @var string
     */
    protected $sLangDefault = 'russian';
    /**
     * Языковые текстовки
     *
     * @var array
     */
    protected $aLang = array();    
    /**
     * Инициализация основных настроек
     *
     */
    public function __construct() {
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
    protected function LoadLanguageFile($sLang) {
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
    protected function Lang($sKey,$aParams=array()) {
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
	protected function GetSessionVar($sKey,$mDefault=null) {
		return array_key_exists($sKey,$_SESSION) ? unserialize($_SESSION[$sKey]) : $mDefault;
	}
	/**
	 * Вкладывает переменную в сессию
	 *
	 * @param  string $sKey
	 * @param  mixed  $mVar
	 * @return bool
	 */
	protected function SetSessionVar($sKey,$mVar) {
		$_SESSION[$sKey] = serialize($mVar);
		return true;
	}
	/**
	 * Уничтожает переменную в сессии
	 *
	 * @param  string $sKey
	 * @return bool
	 */
	protected function DestroySessionVar($sKey) {
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
	protected function Fetch($sTemplateName) {
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
	protected function FetchString($sTempString) {
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
	protected function Assign($sName,$sValue,$sFromSession=null) {
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
	protected function Layout($sTemplate) {
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
	protected function SaveConfig($sName,$sVar,$sPath) {
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
	protected function ConvertToString($mVar) {
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
	protected function GetRequest($sName,$default=null,$bSession=null) {		
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
	public function Run($sStepName=null) {
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

		$this->Assign('next_step_display', ($iKey == count($this->aSteps)-1)?'none':'inline-block');
		$this->Assign('prev_step_display', ($iKey == 0) ? 'none' : 'inline-block');
		
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
	protected function SetStep($sStepName) {
		if(!$sStepName or !in_array($sStepName,$this->aSteps)) return null;
		$this->Assign('install_step_number',array_search($sStepName,$this->aSteps)+1);		
	}
	/**
	 * Устанавливает количество шагов для отображения в шаблонах
	 *
	 * @param int $iStepCount
	 */
	protected function SetStepCount($iStepCount) {
		$this->iStepCount = $iStepCount;
	}
	
	/**
	 * Первый шаг инсталяции.
	 * Валидация окружения.
	 */
	protected function StepStart() {
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
	protected function StepDb() {
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
		$aParams['convert_from_10']  = $this->GetRequest('install_db_convert_from_10',0);
		$aParams['prefix']   = $this->GetRequest('install_db_prefix','prefix_');
		$aParams['engine']   = $this->GetRequest('install_db_engine','InnoDB');

		$this->Assign('install_db_server', $aParams['server'], self::SET_VAR_IN_SESSION);
		$this->Assign('install_db_port', $aParams['port'], self::SET_VAR_IN_SESSION);
		$this->Assign('install_db_name', $aParams['name'], self::SET_VAR_IN_SESSION);
		$this->Assign('install_db_user', $aParams['user'], self::SET_VAR_IN_SESSION);
		$this->Assign('install_db_password', $aParams['password'], self::SET_VAR_IN_SESSION);
		$this->Assign('install_db_create_check', (($aParams['create'])?'checked="checked"':''), self::SET_VAR_IN_SESSION);
		$this->Assign('install_db_convert_check', (($aParams['convert'])?'checked="checked"':''), self::SET_VAR_IN_SESSION);
		$this->Assign('install_db_convert_from_10_check', (($aParams['convert_from_10'])?'checked="checked"':''), self::SET_VAR_IN_SESSION);
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
				/**
				 * Отдельным файлом запускаем создание GEO-базы
				 */
				$aRes=$this->CreateTables('geo_base.sql',array_merge($aParams,array('check_table'=>'geo_city')));
				if ($aRes) {
					list($bResult,$aErrors) = array_values($aRes);
					if(!$bResult) {
						foreach($aErrors as $sError) $this->aMessages[] = array('type'=>'error','text'=>$sError);
						$this->Layout('steps/db.tpl');
						return false;
					}
				}

				if(!$aParams['convert'] && !$aParams['convert_from_10']) {
					$aRes=$this->CreateTables('sql.sql',array_merge($aParams,array('check_table'=>'topic')));
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
				} elseif ($aParams['convert']) {
					/**
					 * Если указана конвертация старой базы данных
					 */
					list($bResult,$aErrors) = array_values($this->ConvertDatabase('convert_0.5.1_to_1.0.sql',$aParams));
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
	protected function StepAdmin() {
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
	protected function StepEnd() {
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
	protected function StepExtend() {
		/**
		 * Выводим на экран кнопку @Next
		 */
		$this->Assign('next_step_display','inline-block');
		/**
		 * Сохраняем в сессию название текущего шага
		 */
		$this->SetSessionVar(self::SESSSION_KEY_STEP_NAME,'Extend');
		$this->SetStep('Extend');
		/**
		 * Получаем значения запрашиваемых данных либо устанавливаем принятые по умолчанию
		 */
		$aParams['install_view_name']       = $this->GetRequest('install_view_name','Your Site',self::GET_VAR_FROM_SESSION);
		$aParams['install_view_description']= $this->GetRequest('install_view_description','Description your site',self::GET_VAR_FROM_SESSION);
		$aParams['install_view_keywords']   = $this->GetRequest('install_view_keywords','site, google, internet',self::GET_VAR_FROM_SESSION);
		$aParams['install_view_skin']       = $this->GetRequest('install_view_skin','synio',self::GET_VAR_FROM_SESSION);
		
		$aParams['install_mail_sender']     = $this->GetRequest('install_mail_sender',$this->GetSessionVar('install_admin_mail','rus.engine@gmail.com'),self::GET_VAR_FROM_SESSION);
		$aParams['install_mail_name']       = $this->GetRequest('install_mail_name','Почтовик Your Site',self::GET_VAR_FROM_SESSION);
		
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
	protected function StepFinish() {
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
	protected function ValidateEnviroment() {
		$bOk = true;
		
		if(!version_compare(PHP_VERSION, '5.2.0', '>=')) {
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
	        $this->Assign('validate_mbstring', '<span style="color:green;">'.$this->Lang('yes').'</span>');
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
	protected function ValidateDBConnection($aParams) {
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
	protected function SelectDatabase($sName,$bCreate=false) {
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
	protected function CreateTables($sFilePath,$aParams) {
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
		if (in_array($aParams['prefix'].$aParams['check_table'],$aDbTables)) {
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
	protected function ValidateConvertDatabase($aParams) {
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
		 * Смотрим на наличие в базе таблицы prefix_user_note
		 */
		return !in_array($aParams['prefix'].'user_note',$aDbTables);
	}
	/**
	 * Конвертирует базу данных версии 0.5.1 в базу данных версии 1.0
	 *
	 * @return bool
	 */
	protected function ConvertDatabase($sFilePath,$aParams) {	
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
		 * Необходимая конвертация в 1.0 из 0.5.1
		 */

		/**
		 * Пересчет количества избранного для топиков
		 */
		$sTable1=$aParams['prefix'].'topic';
		$sTable2=$aParams['prefix'].'favourite';
		$sQuery = "
                UPDATE {$sTable1} t
                SET t.topic_count_favourite = (
                    SELECT count(f.user_id)
                    FROM {$sTable2} f
                    WHERE
                        f.target_id = t.topic_id
                    AND
                        f.target_publish = 1
                    AND
                        f.target_type = 'topic'
                )
            ";
		if(!mysql_query($sQuery)) $aErrors[] = mysql_error();
		/**
		 * Пересчет количества избранного для комментов
		 */
		$sTable1=$aParams['prefix'].'comment';
		$sQuery = "
            UPDATE {$sTable1} c
            SET c.comment_count_favourite = (
                SELECT count(f.user_id)
                FROM {$sTable2} f
                WHERE
                    f.target_id = c.comment_id
                AND
					f.target_publish = 1
				AND
					f.target_type = 'comment'
            )
		";
		if(!mysql_query($sQuery)) $aErrors[] = mysql_error();
		/**
		 * Пересчет счетчиков голосования за топик
		 */
		$sTable1=$aParams['prefix'].'topic';
		$sTable2=$aParams['prefix'].'vote';
		$sQuery = "
                UPDATE {$sTable1} t
                SET t.topic_count_vote_up = (
                    SELECT count(*)
                    FROM {$sTable2} v
                    WHERE
                        v.target_id = t.topic_id
                    AND
                        v.vote_direction = 1
                    AND
                        v.target_type = 'topic'
                ), t.topic_count_vote_down = (
                    SELECT count(*)
                    FROM {$sTable2} v
                    WHERE
                        v.target_id = t.topic_id
                    AND
                        v.vote_direction = -1
                    AND
                        v.target_type = 'topic'
                ), t.topic_count_vote_abstain = (
                    SELECT count(*)
                    FROM {$sTable2} v
                    WHERE
                        v.target_id = t.topic_id
                    AND
                        v.vote_direction = 0
                    AND
                        v.target_type = 'topic'
                )
            ";
		if(!mysql_query($sQuery)) $aErrors[] = mysql_error();
		/**
		 * Пересчет количества топиков в блогах
		 */
		$sTable1=$aParams['prefix'].'blog';
		$sTable2=$aParams['prefix'].'topic';
		$sQuery = "
                UPDATE {$sTable1} b
                SET b.blog_count_topic = (
                    SELECT count(*)
                    FROM {$sTable2} t
                    WHERE
                        t.blog_id = b.blog_id
                    AND
                        t.topic_publish = 1
                )
            ";
		if(!mysql_query($sQuery)) $aErrors[] = mysql_error();
		/**
		 * Проставляем последнего пользователя и последний комментарий во всех личных сообщениях
		 */
		$sTable1=$aParams['prefix'].'talk';
		$sTable2=$aParams['prefix'].'comment';
		$iPage=1;

		do {
			$iLimitStart=($iPage-1)*100;
			$sQuery="SELECT talk_id, user_id FROM {$sTable1} LIMIT {$iLimitStart},100";
			if(!$aResults = mysql_query($sQuery)){
				$aErrors[] = mysql_error();
				break;
			}
			if (mysql_num_rows($aResults)) {
				while($aRow = mysql_fetch_assoc($aResults)) {
					$iTalk=$aRow['talk_id'];
					$iUserLast=$aRow['user_id'];
					$iCommentLast=null;
					/**
					 * Запрашиваем последний комментарий из сообщения
					 */
					$sQuery2="SELECT comment_id, user_id FROM {$sTable2} WHERE target_id='{$iTalk}' and target_type='talk' ORDER BY comment_id desc LIMIT 0,1";
					if(!$aResults2 = mysql_query($sQuery2)){
						$aErrors[] = mysql_error();
						continue;
					}
					if($aRow2 = mysql_fetch_assoc($aResults2)) {
						$iCommentLast=$aRow2['comment_id'];
						$iUserLast=$aRow2['user_id'];
					}
					/**
					 * Обновляем значения
					 */
					$sQuery3="UPDATE {$sTable1} SET talk_user_id_last='{$iUserLast}', talk_comment_id_last=".($iCommentLast ? $iCommentLast : 'null')." WHERE talk_id='{$iTalk}' ";
					if(!mysql_query($sQuery3)) {
						$aErrors[] = mysql_error();
						continue;
					}
				}
			} else {
				break;
			}
			$iPage++;
		} while (1);
		/**
		 * Перенос стран и городов на новую структуру
		 */
		$sTableUser=$aParams['prefix'].'user';
		$sTableGeoCountry=$aParams['prefix'].'geo_country';
		$sTableGeoCity=$aParams['prefix'].'geo_city';
		$sTableGeoRegion=$aParams['prefix'].'geo_region';
		$sTableGeoTarget=$aParams['prefix'].'geo_target';
		$iPage=1;
		do {
			$iLimitStart=($iPage-1)*100;
			$sQuery="SELECT * FROM {$sTableUser} WHERE
					(`user_profile_country`  IS NOT NULL and `user_profile_country`<>'') or
					(`user_profile_region`  IS NOT NULL and `user_profile_region`<>'') or
					(`user_profile_city`  IS NOT NULL and `user_profile_city`<>'')

					 LIMIT {$iLimitStart},100";
			if(!$aResults = mysql_query($sQuery)){
				$aErrors[] = mysql_error();
				break;
			}
			if (mysql_num_rows($aResults)) {
				while($aRow = mysql_fetch_assoc($aResults)) {
					/**
					 * Обрабатываем каждого пользователя
					 */
					$iUserId=$aRow['user_id'];
					if (!$aRow['user_profile_country']) {
						$sQuery2="UPDATE {$sTableUser} SET user_profile_country=null, user_profile_region=null, user_profile_city=null WHERE user_id={$iUserId} ";
						if(!$aResults2 = mysql_query($sQuery2)){
							$aErrors[] = mysql_error();
						}
						continue;
					}
					$sCountry=mysql_real_escape_string($aRow['user_profile_country']);
					$sCity=mysql_real_escape_string((string)$aRow['user_profile_city']);
					/**
					 * Ищем страну в гео-базе
					 */
					$sQuery2="SELECT id, name_ru FROM {$sTableGeoCountry} WHERE name_ru='{$sCountry}' or name_en='{$sCountry}' LIMIT 0,1";
					if(!($aResults2 = mysql_query($sQuery2))){
						$aErrors[] = mysql_error();
						continue;
					}
					if($aRow2 = mysql_fetch_assoc($aResults2)) {
						$iCountryId=$aRow2['id'];
						$sCountryName=mysql_real_escape_string($aRow2['name_ru']);
					} else {
						$sQuery2="UPDATE {$sTableUser} SET user_profile_country=null, user_profile_region=null, user_profile_city=null WHERE user_id={$iUserId} ";
						if(!$aResults2 = mysql_query($sQuery2)){
							$aErrors[] = mysql_error();
						}
						continue;
					}
					/**
					 * Ищем город в гео-базе
					 */
					$iCityId=null;
					$sCityName=null;
					$iRegionId=null;
					$sRegionName=null;
					if ($sCity) {
						$sQuery2="SELECT id, region_id, name_ru FROM {$sTableGeoCity} WHERE country_id='{$iCountryId}' and (name_ru='{$sCity}' or name_en='{$sCity}') LIMIT 0,1";
						if(!($aResults2 = mysql_query($sQuery2))){
							$aErrors[] = mysql_error();
							continue;
						}
						if($aRow2 = mysql_fetch_assoc($aResults2)) {
							$iCityId=$aRow2['id'];
							$sCityName=mysql_real_escape_string($aRow2['name_ru']);
							$iRegionId=$aRow2['region_id'];
							/**
							 * Получаем название региона
							 */
							$sQuery3="SELECT name_ru FROM {$sTableGeoRegion} WHERE id='{$iRegionId}' LIMIT 0,1";
							if(!$aResults3 = mysql_query($sQuery3)){
								$aErrors[] = mysql_error();
								continue;
							}
							if($aRow3 = mysql_fetch_assoc($aResults3)) {
								$sRegionName=mysql_real_escape_string($aRow3['name_ru']);
							} else {
								continue;
							}
						}
					}
					/**
					 * Добавляем связь пользователя с гео-объектом
					 */
					$iGeoId=$iCountryId;
					$sGeoType='country';
					if ($iCityId) {
						$iGeoId=$iCityId;
						$sGeoType='city';
					}
					/**
					 * Проверяем отсутствие связи
					 */
					$sQuery2="SELECT * FROM {$sTableGeoTarget} WHERE target_type='user' and target_id='{$iUserId}' LIMIT 0,1";
					if(!($aResults2 = mysql_query($sQuery2))){
						$aErrors[] = mysql_error();
						continue;
					}
					if($aRow2 = mysql_fetch_assoc($aResults2)) {
						// пропускаем этого пользователя
						continue;
					}
					/**
					 * Создаем новую связь
					 */
					$sQuery2="INSERT INTO {$sTableGeoTarget} SET geo_type='{$sGeoType}', geo_id='{$iGeoId}', target_type='user', target_id='{$iUserId}', country_id=".($iCountryId ? $iCountryId : 'null').", region_id=".($iRegionId ? $iRegionId : 'null')." , city_id=".($iCityId ? $iCityId : 'null')."  ";
					if(!($aResults2 = mysql_query($sQuery2))){
						$aErrors[] = mysql_error();
						continue;
					}
					/**
					 * Обновляем информацию о пользователе
					 */
					$sQuery2="UPDATE {$sTableUser} SET user_profile_country=".($iCountryId ? "'$sCountryName'" : 'null').", user_profile_region=".($sRegionName ? "'$sRegionName'" : 'null').", user_profile_city=".($sCityName ? "'$sCityName'" : 'null')." WHERE user_id={$iUserId} ";
					if(!($aResults2 = mysql_query($sQuery2))){
						$aErrors[] = mysql_error();
						continue;
					}
				}
			} else {
				break;
			}
			$iPage++;
		} while (1);
		/**
		 * Перенос ICQ и сайта из профиля пользователя
		 */
		$sTableUser=$aParams['prefix'].'user';
		$sTableUserField=$aParams['prefix'].'user_field';
		$sTableUserFieldValue=$aParams['prefix'].'user_field_value';

		$sFieldIdIcq=null;
		$sFieldIdWww=null;

		/**
		 * Получаем ID необходимых полей
		 */
		$sQuery2="SELECT id FROM {$sTableUserField} WHERE `type`='contact' and name='icq' LIMIT 0,1";
		if(!($aResults2 = mysql_query($sQuery2))){
			$aErrors[] = mysql_error();
		} else {
			if($aRow2 = mysql_fetch_assoc($aResults2)) {
				$sFieldIdIcq=$aRow2['id'];
			}
		}
		$sQuery2="SELECT id FROM {$sTableUserField} WHERE `type`='contact' and name='www' LIMIT 0,1";
		if(!($aResults2 = mysql_query($sQuery2))){
			$aErrors[] = mysql_error();
		} else {
			if($aRow2 = mysql_fetch_assoc($aResults2)) {
				$sFieldIdWww=$aRow2['id'];
			}
		}

		if ($sFieldIdIcq and $sFieldIdWww) {
			$iPage=1;
			do {
				$iLimitStart=($iPage-1)*100;
				$sQuery="SELECT * FROM {$sTableUser} WHERE `user_profile_country`  IS NOT NULL and `user_profile_country`<>'' LIMIT {$iLimitStart},100";
				if(!($aResults = mysql_query($sQuery))){
					$aErrors[] = mysql_error();
					break;
				}
				if (mysql_num_rows($aResults)) {
					while($aRow = mysql_fetch_assoc($aResults)) {
						$iUserId=$aRow['user_id'];
						$sIcq=$aRow['user_profile_icq'];
						$sWww=$aRow['user_profile_site'];
						if ($sIcq) {
							$sIcq=mysql_real_escape_string($sIcq);
							/**
							 * Проверяем отсутствие связи
							 */
							$sQuery2="SELECT * FROM {$sTableUserFieldValue} WHERE user_id='{$iUserId}' and field_id='{$sFieldIdIcq}' LIMIT 0,1";
							if(!($aResults2 = mysql_query($sQuery2))){
								$aErrors[] = mysql_error();
							} else {
								if(!($aRow2 = mysql_fetch_assoc($aResults2))) {
									/**
									 * Создаем новую связь
									 */
									$sQuery3="INSERT INTO {$sTableUserFieldValue} SET user_id='{$iUserId}', field_id='{$sFieldIdIcq}', value='{$sIcq}' ";
									if(!$aResults3 = mysql_query($sQuery3)){
										$aErrors[] = mysql_error();
									}
								}
							}
						}
						if ($sWww) {
							$sWww=str_replace('https://','',$sWww);
							$sWww=str_replace('http://','',$sWww);
						}
						if ($sWww) {
							$sWww=mysql_real_escape_string($sWww);
							/**
							 * Проверяем отсутствие связи
							 */
							$sQuery2="SELECT * FROM {$sTableUserFieldValue} WHERE user_id='{$iUserId}' and field_id='{$sFieldIdWww}' LIMIT 0,1";
							if(!($aResults2 = mysql_query($sQuery2))){
								$aErrors[] = mysql_error();
							} else {
								if(!($aRow2 = mysql_fetch_assoc($aResults2))) {
									/**
									 * Создаем новую связь
									 */
									$sQuery3="INSERT INTO {$sTableUserFieldValue} SET user_id='{$iUserId}', field_id='{$sFieldIdWww}', value='{$sWww}' ";
									if(!$aResults3 = mysql_query($sQuery3)){
										$aErrors[] = mysql_error();
									}
								}
							}
						}
					}
				} else {
					break;
				}
				$iPage++;
			} while (1);
		}
		/**
		 * Удаляем поля
		 */
		$sQuery="ALTER TABLE `{$sTableUser}` DROP `user_profile_site` ";
		if(!mysql_query($sQuery)){
			$aErrors[] = mysql_error();
		}
		$sQuery="ALTER TABLE `{$sTableUser}` DROP `user_profile_site_name` ";
		if(!mysql_query($sQuery)){
			$aErrors[] = mysql_error();
		}
		$sQuery="ALTER TABLE `{$sTableUser}` DROP `user_profile_icq` ";
		if(!mysql_query($sQuery)){
			$aErrors[] = mysql_error();
		}
		/**
		 * Добавление тегов в избранное
		 */
		$sTablefFavourite=$aParams['prefix'].'favourite';
		$sTablefTopicTag=$aParams['prefix'].'topic_tag';
		$sTablefFavouriteTag=$aParams['prefix'].'favourite_tag';
		$iPage=1;
		do {
			$iLimitStart=($iPage-1)*100;
			$sQuery="SELECT f.user_id, f.target_id, t.topic_tag_text FROM `{$sTablefFavourite}` as f, `{$sTablefTopicTag}` as t WHERE f.`target_type`='topic' and f.`target_id`=t.topic_id  LIMIT {$iLimitStart},100";
			if(!$aResults = mysql_query($sQuery)){
				$aErrors[] = mysql_error();
				break;
			}
			if (mysql_num_rows($aResults)) {
				while($aRow = mysql_fetch_assoc($aResults)) {
					$iUserId=$aRow['user_id'];
					$iTargetId=$aRow['target_id'];
					$sText=mysql_real_escape_string($aRow['topic_tag_text']);
					/**
					 * Проверяем наличие
					 */
					$sQuery2="SELECT * FROM {$sTablefFavouriteTag} WHERE user_id='{$iUserId}' and target_id='{$iTargetId}' and target_type='topic' and is_user=0 and text='{$sText}' LIMIT 0,1";
					if(!($aResults2 = mysql_query($sQuery2))){
						$aErrors[] = mysql_error();
						continue;
					}
					if($aRow2 = mysql_fetch_assoc($aResults2)) {
						// пропускаем
						continue;
					}
					/**
					 * Создаем
					 */
					$sQuery2="INSERT INTO {$sTablefFavouriteTag} SET user_id='{$iUserId}', target_id='{$iTargetId}', target_type='topic', is_user=0, text='{$sText}' ";
					if(!($aResults2 = mysql_query($sQuery2))){
						$aErrors[] = mysql_error();
						continue;
					}
				}
			} else {
				break;
			}
			$iPage++;
		} while (1);
		/**
		 * Вырезаем теги из информации о пользователе
		 */
		$sTableUser=$aParams['prefix'].'user';
		$iPage=1;
		do {
			$iLimitStart=($iPage-1)*100;
			$sQuery="SELECT * FROM {$sTableUser} WHERE `user_profile_about`  IS NOT NULL and `user_profile_about`<>'' LIMIT {$iLimitStart},100";
			if(!$aResults = mysql_query($sQuery)){
				$aErrors[] = mysql_error();
				break;
			}
			if (mysql_num_rows($aResults)) {
				while($aRow = mysql_fetch_assoc($aResults)) {
					$sAbout=mysql_real_escape_string(htmlspecialchars(strip_tags($aRow['user_profile_about'])));
					$iUserId=$aRow['user_id'];
					/**
					 * Обновляем информацию о пользователе
					 */
					$sQuery2="UPDATE {$sTableUser} SET user_profile_about='{$sAbout}' WHERE user_id={$iUserId} ";
					if(!($aResults2 = mysql_query($sQuery2))){
						$aErrors[] = mysql_error();
						continue;
					}
				}
			} else {
				break;
			}
			$iPage++;
		} while (1);

		if(count($aErrors)==0) {
			return array('result'=>true,'errors'=>null);
		}
		return array('result'=>false,'errors'=>$aErrors);		
	}
	/**
	 * Добавление значения в поле таблицы с типом enum
	 *
	 * @param unknown_type $sTableName
	 * @param unknown_type $sFieldName
	 * @param unknown_type $sType
	 */
	public function addEnumTypeDatabase($sTableName,$sFieldName,$sType) {
		$sQuery="SHOW COLUMNS FROM  `{$sTableName}`";
		if ($res=mysql_query($sQuery)) {
			while($aRow = mysql_fetch_assoc($res)) {
				if ($aRow['Field'] == $sFieldName) break;
			}
			if (strpos($aRow['Type'], "'{$sType}'") === FALSE) {
				$aRow['Type'] =str_ireplace('enum(', "enum('{$sType}',", $aRow['Type']);
				$sQuery="ALTER TABLE `{$sTableName}` MODIFY `{$sFieldName}` ".$aRow['Type'];
				$sQuery.= ($aRow['Null']=='NO') ? ' NOT NULL ' : ' NULL ';
				$sQuery.= is_null($aRow['Default']) ? ' DEFAULT NULL ' : " DEFAULT '{$aRow['Default']}' ";
				mysql_query($sQuery);
			}
		}
	}
	/**
	 * Проверяет существование таблицы
	 *
	 * @param unknown_type $sTableName
	 * @return unknown
	 */
	public function isTableExistsDatabase($sTableName) {
		$sQuery="SHOW TABLES LIKE '{$sTableName}'";
		if ($res=mysql_query($sQuery)) {
			return true;
		}
		return false;
	}
	/**
	 * Проверяет существование поля таблицы
	 *
	 * @param unknown_type $sTableName
	 * @param unknown_type $sFieldName
	 * @return unknown
	 */
	public function isFieldExistsDatabase($sTableName,$sFieldName) {
		$sQuery="SHOW FIELDS FROM `{$sTableName}`";
		if ($res=mysql_query($sQuery)) {
			while($aRow = mysql_fetch_assoc($res)) {
				if ($aRow['Field'] == $sFieldName){
					return true;
				}
			}
		}
		return false;
	}
	/**
	 * Валидирует данные администратора
	 *
	 * @return bool;
	 */
	protected function ValidateAdminFields() {
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
	protected function UpdateDBUser($sLogin,$sPassword,$sMail,$sPrefix="prefix_") {
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
	protected function UpdateUserBlog($sBlogName,$sPrefix="prefix_") {
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
	protected function IsUseDbTable($sQuery,$aTables) {
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
	protected function GetSkinList() {
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
	protected function GetLangList() {
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
	protected function SavePath() {
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
	
	protected function GetPathRootWeb() {
		return rtrim('http://'.$_SERVER['HTTP_HOST'],'/').str_replace('/install/index.php','',$_SERVER['PHP_SELF']);
	}
	
	protected function GetPathRootServer() {
		return rtrim(dirname(dirname(__FILE__)),'/');
	}
}

session_start();
$oInstaller = new Install;
$oInstaller->Run();
?>