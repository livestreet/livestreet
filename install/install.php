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
	var $aSteps = array(0=>'Start',1=>'Db',2=>'Admin',3=>'End',4=>'Extend');
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
     * Директория хранения языковых файлов
     *
     * @var string
     */
    var $sLangDir="";
    /**
     * Инициализация основных настроек
     *
     */
    function __construct() {
    	$this->sConfigDir = dirname(__FILE__).'/../config';
    	$this->sSkinDir   = dirname(__FILE__).'/../templates/skin';
    	$this->sLangDir   = dirname(__FILE__).'/../templates/language';
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
			$this->aMessages[] = array('type'=>'error', 'text'=>"Файл конфигурации {$sPath} не существует.");			
			return false;
		}
		if(!is_writeable($sPath)) { 
			$this->aMessages[] = array('type'=>'error', 'text'=>"Файл {$sPath} недосупен для записи.");
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
				return "'".$mVar."'";
					
			case is_bool($mVar):
				return ($mVar)?"'true'":"'false'";
				
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
	function Run() {
		$sStepName = $this->GetSessionVar(self::SESSSION_KEY_STEP_NAME, self::INSTALL_DEFAULT_STEP);
		if(!$sStepName or !in_array($sStepName,$this->aSteps)) die('Unknown step');
		
		$iKey = array_search($sStepName,$this->aSteps);
		/**
		 * Если была нажата кнопка "Назад", перемещаемся на шаг назад
		 */
		if($this->GetRequest('install_step_prev') && $iKey!=0) {
			$sStepName = $this->aSteps[--$iKey];
			$this->SetSessionVar(self::SESSSION_KEY_STEP_NAME,$sStepName);
		}

		if($iKey == count($this->aSteps)-1) {
			$this->Assign('next_step_display', 'none');
		}				
		if($iKey == 0) {
			$this->Assign('prev_step_display', 'none');
		}
		/**
		 * Передаем во вьевер данные для формирование таймлайна шагов
		 */
		$this->Assign('install_step_number',$iKey+1);
		$this->Assign('install_step_count',count($this->aSteps));
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
			$this->SetSessionVar(self::SESSSION_KEY_STEP_NAME,'Db');
		}
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
			$this->Assign('install_db_server', 'localhost', self::GET_VAR_FROM_SESSION);
			$this->Assign('install_db_port', '3306', self::GET_VAR_FROM_SESSION);
			$this->Assign('install_db_name', 'social', self::GET_VAR_FROM_SESSION);
			$this->Assign('install_db_user', 'root', self::GET_VAR_FROM_SESSION);
			$this->Assign('install_db_password', '', self::GET_VAR_FROM_SESSION);
			$this->Assign('install_db_create_check', '', self::GET_VAR_FROM_SESSION);
			$this->Assign('install_db_prefix', 'prefix_', self::GET_VAR_FROM_SESSION);
			$this->Assign('install_db_engine', 'InnoDB', self::GET_VAR_FROM_SESSION);
			$this->Assign('install_db_engine_innodb', '', self::GET_VAR_FROM_SESSION);
			$this->Assign('install_db_engine_myisam', '', self::GET_VAR_FROM_SESSION);
			
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
		$aParams['prefix']   = $this->GetRequest('install_db_prefix','prefix_');
		$aParams['engine']   = $this->GetRequest('install_db_engine','InnoDB');

		$this->Assign('install_db_server', $aParams['server'], self::SET_VAR_IN_SESSION);
		$this->Assign('install_db_port', $aParams['port'], self::SET_VAR_IN_SESSION);
		$this->Assign('install_db_name', $aParams['name'], self::SET_VAR_IN_SESSION);
		$this->Assign('install_db_user', $aParams['user'], self::SET_VAR_IN_SESSION);
		$this->Assign('install_db_password', $aParams['password'], self::SET_VAR_IN_SESSION);
		$this->Assign('install_db_create_check', (($aParams['create'])?'checked="checked"':''), self::SET_VAR_IN_SESSION);
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
				$this->aMessages[] = array('type'=>'error','text'=>'Невозможно выбрать или создать базу данных');
				$this->Layout('steps/db.tpl');
				return false;
			}
			/**
			 * Сохраняем в config.local.php настройки соединения
			 */
			$sLocalConfigFile = $this->sConfigDir.'/'.self::LOCAL_CONFIG_FILE_NAME;
			if(!file_exists($sLocalConfigFile)) {
				$this->aMessages[] = array('type'=>'error','text'=>'Файл локальной конфигурации config.local.php не найден.');
				$this->Layout('steps/db.tpl');
				return false;
			}
			@chmod($sLocalConfigFile, 0777);
			
			$this->SaveConfig('db.params.host',  $aParams['server'],   $sLocalConfigFile);
			$this->SaveConfig('db.params.port',  $aParams['port'],     $sLocalConfigFile);
			$this->SaveConfig('db.params.user',  $aParams['user'],     $sLocalConfigFile);
			$this->SaveConfig('db.params.pass',  $aParams['password'], $sLocalConfigFile);
			$this->SaveConfig('db.params.pass',  $aParams['password'], $sLocalConfigFile);
			$this->SaveConfig('db.table.prefix', $aParams['prefix'],   $sLocalConfigFile);
			$this->SaveConfig('db.tables.engine',$aParams['engine'],   $sLocalConfigFile);
			/**
			 * Сохраняем данные в сессию
			 */
			$this->SetSessionVar('INSTALL_DATABASE_PARAMS',$aParams);
			/**
			 * Открываем .sql файл и добавляем в базу недостающие таблицы
			 */
			list($bResult,$aErrors) = array_values($this->CreateTables('sql.sql',$aParams));
			if(!$bResult) {
				foreach($aErrors as $sError) $this->aMessages[] = array('type'=>'error','text'=>$sError);
				$this->Layout('steps/db.tpl');
				return false;
			}
			/**
			 * Передаем управление на следующий шаг
			 */
			$this->aMessages[] = array('type'=>'notice','text'=>'База данных успешно создана. Данные записаны в конфигурационный файл.');
			return $this->StepAdmin();
		} else {
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
			$this->aMessages[] = array('type'=>'error','text'=>'Не удалось подключиться к базе данных');
			$this->Layout('steps/admin.tpl');
			return false;					
		}
		$this->SelectDatabase($aParams['name']);
		
		$bUpdated = $this->UpdateDBUser(
			$this->GetRequest('install_admin_login'),
			$this->GetRequest('install_admin_password'),
			$this->GetRequest('install_admin_mail'),
			$aParams['prefix']
		);
		if(!$bUpdated) {
			$this->aMessages[] = array('type'=>'error','text'=>'Не удалось сохранить данные в базе.<br />'.mysql_error());
			$this->Layout('steps/admin.tpl');
			return false;	
		}
		/**
		 * Передаем управление на следующий шаг
		 */
		return $this->StepEnd();
	}
	/**
	 * Завершающий этап. Переход в расширенный режим
	 */
	function StepEnd() {
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

		/**
		 * Получаем значения запрашиваемых данных либо устанавливаем принятые по умолчанию
		 */
		$aParams['install_view_name']       = $this->GetRequest('install_view_name','LiveStreet - бесплатный движок социальной сети',self::GET_VAR_FROM_SESSION);
		$aParams['install_view_description']= $this->GetRequest('install_view_description','LiveStreet - официальный сайт бесплатного движка социальной сети',self::GET_VAR_FROM_SESSION);
		$aParams['install_view_keywords']   = $this->GetRequest('install_view_keywords','движок, livestreet, блоги, социальная сеть, бесплатный, php',self::GET_VAR_FROM_SESSION);
		$aParams['install_view_skin']       = $this->GetRequest('install_view_skin','new',self::GET_VAR_FROM_SESSION);
		
		$aParams['install_mail_sender']     = $this->GetRequest('install_mail_sender','rus.engine@gmail.com',self::GET_VAR_FROM_SESSION);
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
				$this->aMessages[] = array('type'=>'error','text'=>'Указано недопустимое название сайта.');
			}
			/**
			 * Описание сайта
			 */			
			if($aParams['install_view_description']){
				if($this->SaveConfig('view.description',$aParams['install_view_description'],$sLocalConfigFile))
				 $this->SetSessionVar('install_view_description',$aParams['install_view_description']);
			} else {
				$bOk = false;
				$this->aMessages[] = array('type'=>'error','text'=>'Указано недопустимое описание сайта.');
			}
			/**
			 * Ключевые слова
			 */
			if($aParams['install_view_keywords'] && strlen($aParams['install_view_keywords'])>2){
				if($this->SaveConfig('view.keywords',$aParams['install_view_keywords'],$sLocalConfigFile))
					$this->SetSessionVar('install_view_keywords',$aParams['install_view_keywords']);				
			} else {
				$bOk = false;
				$this->aMessages[] = array('type'=>'error','text'=>'Указано недопустимые ключевые слова.');
			}
			/**
			 * Название шаблона оформления
			 */
			if($aParams['install_view_skin'] && strlen($aParams['install_view_skin'])>1){
				if($this->SaveConfig('view.skin',$aParams['install_view_skin'],$sLocalConfigFile))
					$this->SetSessionVar('install_view_skin',$aParams['install_view_skin']);
			} else {
				$bOk = false;
				$this->aMessages[] = array('type'=>'error','text'=>'Указано недопустимое имя шаблона.');
			}
			
			/**
			 * E-mail, с которого отправляются уведомления
			 */
			if($aParams['install_mail_sender'] && strlen($aParams['install_mail_sender'])>5){
				if($this->SaveConfig('sys.mail.from_email',$aParams['install_mail_sender'],$sLocalConfigFile))
					$this->SetSessionVar('install_mail_sender',$aParams['install_mail_sender']);
			} else {
				$bOk = false;
				$this->aMessages[] = array('type'=>'error','text'=>'Указано недопустимый e-mail.');
			}
			/**
			 * Имя, от которого отправляются уведомления
			 */
			if($aParams['install_mail_name'] && strlen($aParams['install_mail_name'])>1){
				if($this->SaveConfig('sys.mail.from_name',$aParams['install_mail_name'],$sLocalConfigFile))
					$this->SetSessionVar('install_mail_name',$aParams['install_mail_name']);
			} else {
				$bOk = false;
				$this->aMessages[] = array('type'=>'error','text'=>'Указано недопустимое имя отправителя уведомлений.');
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
				if($this->SaveConfig('lang.current',$aParams['install_lang_current'],$sLocalConfigFile))
					$this->SetSessionVar('install_lang_current',$aParams['install_lang_current']);
			} else {
				$bOk = false;
				$this->aMessages[] = array('type'=>'error','text'=>'Указан недопустимый язык.');
			}
			/**
			 * Язык, который будет использоваться по умолчанию
			 */
			if($aParams['install_lang_default'] && strlen($aParams['install_lang_default'])>1){
				if($this->SaveConfig('lang.default',$aParams['install_lang_default'],$sLocalConfigFile))
					$this->SetSessionVar('install_lang_default',$aParams['install_lang_default']);
			} else {
				$bOk = false;
				$this->aMessages[] = array('type'=>'error','text'=>'Указан недопустимый язык по-умолчанию.');
			}		
		}
		
		return $this->Layout('steps/extend.tpl');	
	}
	
	/**
	 * Проверяем возможность инсталяции
	 * 
	 * @return bool
	 */
	function ValidateEnviroment() {
		$bOk = true;
		
		if(!version_compare(PHP_VERSION, '5.0.0', '>')) {
			$bOk = false;
			$this->Assign('validate_php_version', '<span style="color:red;">Нет</span>');			
		} else {
			$this->Assign('validate_php_version', '<span style="color:green;">Да</span>');			
		}
		
		if(!in_array(strtolower(@ini_get('safe_mode')), $this->aValidEnv['safe_mode'])) {
			$bOk = false;
			$this->Assign('validate_safe_mode', '<span style="color:red;">Нет</span>');
		} else {
			$this->Assign('validate_safe_mode', '<span style="color:green;">Да</span>');			
		}

		if(@preg_match('//u', '')!=$this->aValidEnv['UTF8_support']) {
			$bOk = false;
			$this->Assign('validate_utf8', '<span style="color:red;">Нет</span>');
		} else {
			$this->Assign('validate_utf8', '<span style="color:green;">Да</span>');
		}

	    if (@extension_loaded('mbstring')){
	        $aMbInfo=mb_get_info();
			
	        if(!in_array(strtolower($aMbInfo['http_input']), $this->aValidEnv['http_input']) 
	        	or !in_array(strtolower($aMbInfo['http_output']), $this->aValidEnv['http_output']) 
	        		or !in_array(strtolower($aMbInfo['func_overload']), $this->aValidEnv['func_overload'])) {
	        			$bOk = false;
	        			$this->Assign('validate_mbstring', '<span style="color:red;">Нет</span>');
	        } else {
	        	$this->Assign('validate_mbstring', '<span style="color:green;">Да</span>');
	        }
	    } else {
   			$bOk = false;
   			$this->Assign('validate_mbstring', '<span style="color:red;">Нет</span>');	    	
	    }
	    
	    $sLocalConfigPath = $this->sConfigDir.'/config.local.php';
	    if(!file_exists($sLocalConfigPath) or !is_writeable($sLocalConfigPath)) {
			$bOk = false;
			$this->Assign('validate_local_config', '<span style="color:red;">Нет</span>');
		} else {
			$this->Assign('validate_local_config', '<span style="color:green;">Да</span>');			
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
		$oDb = @mysql_connect($aParams['server'],$aParams['user'],$aParams['password']);
		if( $oDb ) {
			/**
			 * Валидация версии MySQL сервера
			 */
			if(!version_compare(mysql_get_server_info(), '5.0.0', '>')) {
				$this->aMessages[] = array('type'=>'error', 'Для работы LiveStreet необходим сервер MySQL версии не ниже 5.');
				return false;
			}
			
			mysql_query('set names utf8');
			return $oDb;
		}
		$this->aMessages[] = array('type'=>'error','text'=>'Не удалось подключиться к базе данных');
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
		if(!$sFileQuery) return array('result'=>false,'errors'=>array("Нет доступа к файлу {$sFilePath}"));
		
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
			return array('result'=>false,'errors'=>array("Не удалось получить данные из базы."));
		}
        while($aRow = mysql_fetch_array($aResult, MYSQL_NUM)){
			$aDbTables[] = $aRow[0];
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
	 * Валидирует данные администратора
	 *
	 * @return bool;
	 */
	function ValidateAdminFields() {
		$bOk = true;
		$aErrors = array();
		
		if(!$sLogin=$this->GetRequest('install_admin_login',false) or strlen($sLogin)<3) {
			$bOk = false;
			$aErrors[] = 'Логин администратора введен не верно.';
		}

		if(!$sMail=$this->GetRequest('install_admin_mail',false) or strlen($sMail)<5) {
			$bOk = false;
			$aErrors[] = 'E-mail администратора введен не верно.';
		}
		if(!$sPass=$this->GetRequest('install_admin_pass',false) or strlen($sPass)<3) {
			$bOk = false;
			$aErrors[] = 'Пароль администратора введен не верно.';
		}
		if($this->GetRequest('install_admin_repass','') != $this->GetRequest('install_admin_pass','')) {
			$bOk = false;
			$aErrors[] = 'Подтверждение пароля не совпадает с самим паролем.';
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
		$this->SaveConfig('path.root.web','http://'.$_SERVER['HTTP_HOST'], $sLocalConfigFile); 
		$this->SaveConfig('path.root.server', $_SERVER['DOCUMENT_ROOT'], $sLocalConfigFile); 
	}
}

session_start();
$oInstaller = new Install;
$oInstaller->Run();
?>