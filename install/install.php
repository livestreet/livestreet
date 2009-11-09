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
	 * Массив разрешенных шагов инсталяции
	 *
	 * @var array
	 */
	protected $aSteps = array(0=>'Start',1=>'Db',2=>'Admin',3=>'End',4=>'Extend');
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
	 * Массив с переменными шаблонизатора
	 *
	 * @var array
	 */
	protected $aTemplateVars = array(
		'___CONTENT___' => '',
		'___FORM_ACTION___' => '',
		'___NEXT_STEP_DISABLED___' => '',
		'___NEXT_STEP_DISPLAY___' => 'block',
		'___SYSTEM_MESSAGES___' => '',
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
     * Инициализация основных настроек
     *
     */
    public function __construct() {
    	$this->sConfigDir = dirname(__FILE__).'/../config';
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
		return str_replace(array_keys($this->aTemplateVars),array_values($this->aTemplateVars),$sTemplate);
	}
	/**
	 * Добавляет переменную для отображение в шаблоне
	 *
	 * @param string $sName
	 * @param string $sValue
	 */
	protected function Assign($sName,$sValue) {
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
			$sMessageContent = "";
			foreach ($this->aMessages as $sMessage) {
				$this->Assign('message_style_class', $sMessage['type']);
				$this->Assign('message_content', $sMessage['text']);
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
		if(!file_exists($sPath)) return false;
		
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
	protected function GetRequest($sName,$default=null) {
		if (isset($_REQUEST[$sName])) {
			if (is_string($_REQUEST[$sName])) {
				return trim(stripcslashes($_REQUEST[$sName]));
			} else {
				return $_REQUEST[$sName];
			}
		}
		return $default;
	}	
	
	/**
	 * Функция отвечающая за проверку входных параметров
	 * и передающая управление на фукнцию текущего шага
	 *
	 * @call $this->Step{__Name__} 
	 */
	public function Run() {
		$sStepName = $this->GetSessionVar(self::SESSSION_KEY_STEP_NAME, self::INSTALL_DEFAULT_STEP);
		if(!$sStepName or !in_array($sStepName,$this->aSteps)) die('Unknown step');
		
		$iKey = array_search($sStepName,$this->aSteps);
		if($iKey == count($this->aSteps)-1) {
			$this->Assign('NEXT_STEP_DISPLAY', 'none');
		}
		
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
	protected function StepStart() {
		if(!$this->ValidateEnviroment()) {
			$this->Assign('next_step_disabled', 'disabled');
		} else {
			$this->SetSessionVar(self::SESSSION_KEY_STEP_NAME,'Db');			
		}
		$this->Layout('steps/start.tpl');
	}
	/**
	 * Запрос данных соединения с базой данных.
	 * Запись полученных данных в лог.
	 */	
	protected function StepDb() {
		if(!isset($_POST['install_db_params'])) {
			$this->Assign('install_db_server', 'localhost');
			$this->Assign('install_db_port', '3306');
			$this->Assign('install_db_name', 'social');
			$this->Assign('install_db_user', 'root');
			$this->Assign('install_db_password', '');
			$this->Assign('install_db_create_check', '');
			$this->Assign('install_db_prefix', 'prefix_');
			
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

		$this->Assign('install_db_server', $aParams['server']);
		$this->Assign('install_db_port', $aParams['port']);
		$this->Assign('install_db_name', $aParams['name']);
		$this->Assign('install_db_user', $aParams['user']);
		$this->Assign('install_db_password', $aParams['password']);
		$this->Assign('install_db_create_check', ($aParams['create'])?'checked="checked"':'');
		$this->Assign('install_db_prefix', $aParams['prefix']);
		
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
			/**
			 * Сохраняем данные в сессию
			 */
			$this->SetSessionVar('INSTALL_DATABASE_PARAMS',$aParams);
			/**
			 * Открываем .sql файл и добавляем в базу недостающие таблицы
			 */
			list($bResult,$aErrors) = array_values($this->CreateTables('sql.sql',$aParams['prefix']));
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
			$this->aMessages[] = array('type'=>'error','text'=>'Не удалось подключиться к базе данных');
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
		$this->Assign('install_admin_login', $this->GetRequest('install_admin_login','admin'));
		$this->Assign('install_admin_mail', $this->GetRequest('install_admin_mail','admin@admin.adm'));
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
	protected function StepEnd() {
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
		$this->Assign('next_step_display','block');
		/**
		 * Сохраняем в сессию название текущего шага
		 */
		$this->SetSessionVar(self::SESSSION_KEY_STEP_NAME,'Extend');

		/**
		 * Получаем значения запрашиваемых данных либо устанавливаем принятые по умолчанию
		 */
		$aParams['install_view_name']       = $this->GetRequest('install_view_name','LiveStreet - бесплатный движок социальной сети');
		$aParams['install_view_description']= $this->GetRequest('install_view_description','LiveStreet - официальный сайт бесплатного движка социальной сети');
		$aParams['install_view_keywords']   = $this->GetRequest('install_view_keywords','движок, livestreet, блоги, социальная сеть, бесплатный, php');
		$aParams['install_view_skin']       = $this->GetRequest('install_view_skin','new');
		
		$aParams['install_mail_sender']     = $this->GetRequest('install_mail_sender','rus.engine@gmail.com');
		$aParams['install_mail_name']       = $this->GetRequest('install_mail_name','Почтовик LiveStreet');
		
		$aParams['install_general_close']  = (bool)$this->GetRequest('install_general_close',false);
		$aParams['install_general_invite'] = (bool)$this->GetRequest('install_general_invite',false);
		$aParams['install_general_active'] = (bool)$this->GetRequest('install_general_active',false);
		
		$aParams['install_lang_current']    = $this->GetRequest('install_lang_current','russian');
		$aParams['install_lang_default']    = $this->GetRequest('install_lang_default','russian');
		
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
		 * Если были переданные данные формы, то обрабатываем добавление
		 */
		if($this->GetRequest('install_extend_params')) {
			$bOk = true;
			$sLocalConfigFile = $this->sConfigDir.'/'.self::LOCAL_CONFIG_FILE_NAME;
			
			/**
			 * Название сайта
			 */
			if($aParams['install_view_name'] && strlen($aParams['install_view_name'])>2){
				$this->SaveConfig('view.name',$aParams['install_view_name'],$sLocalConfigFile);
			} else {
				$bOk = false;
				$this->aMessages[] = array('type'=>'error','text'=>'Указано недопустимое название сайта.');
			}
			/**
			 * Описание сайта
			 */			
			if($aParams['install_view_description']){
				$this->SaveConfig('view.description',$aParams['install_view_description'],$sLocalConfigFile);
			} else {
				$bOk = false;
				$this->aMessages[] = array('type'=>'error','text'=>'Указано недопустимое описание сайта.');
			}
			/**
			 * Ключевые слова
			 */
			if($aParams['install_view_keywords'] && strlen($aParams['install_view_keywords'])>2){
				$this->SaveConfig('view.keywords',$aParams['install_view_keywords'],$sLocalConfigFile);
			} else {
				$bOk = false;
				$this->aMessages[] = array('type'=>'error','text'=>'Указано недопустимые ключевые слова.');
			}
			/**
			 * Название шаблона оформления
			 */
			if($aParams['install_view_skin'] && strlen($aParams['install_view_skin'])>1){
				$this->SaveConfig('view.skin',$aParams['install_view_skin'],$sLocalConfigFile);
			} else {
				$bOk = false;
				$this->aMessages[] = array('type'=>'error','text'=>'Указано недопустимое шаблон.');
			}
			
			/**
			 * E-mail, с которого отправляются уведомления
			 */
			if($aParams['install_mail_sender'] && strlen($aParams['install_mail_sender'])>5){
				$this->SaveConfig('sys.mail.from_email',$aParams['install_mail_sender'],$sLocalConfigFile);
			} else {
				$bOk = false;
				$this->aMessages[] = array('type'=>'error','text'=>'Указано недопустимый e-mail.');
			}
			/**
			 * Имя, от которого отправляются уведомления
			 */
			if($aParams['install_mail_name'] && strlen($aParams['install_mail_name'])>1){
				$this->SaveConfig('sys.mail.from_name',$aParams['install_mail_name'],$sLocalConfigFile);
			} else {
				$bOk = false;
				$this->aMessages[] = array('type'=>'error','text'=>'Указано недопустимое имя отправителя уведомлений.');
			}

			/**
			 * Использовать закрытый режим работы сайта
			 */
			$this->SaveConfig('general.close',$aParams['install_general_close'],$sLocalConfigFile);
			/**
			 * Использовать активацию при регистрации
			 */
			$this->SaveConfig('general.reg.activation',$aParams['install_general_active'],$sLocalConfigFile);
			/**
			 * Использоватьт режим регистрации по приглашению
			 */
			$this->SaveConfig('general.reg.invite',$aParams['install_general_invite'],$sLocalConfigFile);
					
			/**
			 * Текущий язык
			 */
			if($aParams['install_lang_current'] && strlen($aParams['install_lang_current'])>1){
				$this->SaveConfig('lang.current',$aParams['install_lang_current'],$sLocalConfigFile);
			} else {
				$bOk = false;
				$this->aMessages[] = array('type'=>'error','text'=>'Указан недопустимый язык.');
			}
			/**
			 * Язык, который будет использоваться по умолчанию
			 */
			if($aParams['install_lang_default'] && strlen($aParams['install_lang_default'])>1){
				$this->SaveConfig('lang.default',$aParams['install_lang_default'],$sLocalConfigFile);
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
	protected function ValidateEnviroment() {
		$bOk = true;
		
		if(!in_array(strtolower(@ini_get('safe_mode')), $this->aValidEnv['safe_mode'])) {
			$bOk = false;
			$this->Assign('validate_safe_mode', '<span style="color:red;">Нет</span>');
		} else {
			$this->Assign('validate_safe_mode', '<span style="color:green;">Да</span>');			
		}

		if(!in_array(strtolower(@ini_get('register_globals')), $this->aValidEnv['register_globals'])) {
			$bOk = false;
			$this->Assign('validate_register_globals', '<span style="color:red;">Нет</span>');
		} else {
			$this->Assign('validate_register_globals', '<span style="color:green;">Да</span>');			
		}
		
		if(@preg_match('//u', '')!=$this->aValidEnv['UTF8_support']) {
			$bOk = false;
			$this->Assign('validate_utf8', '<span style="color:red;">Нет</span>');
		} else {
			$this->Assign('validate_utf8', '<span style="color:green;">Да</span>');			
		}
		    
	    if (@extension_loaded('mbstring')){
	        $aMbInfo=mb_get_info();

			if(!in_array(strtolower($aMbInfo['http_input']), $this->aValidEnv['http_input'])) {
				$bOk = false;
				$this->Assign('validate_http_input', '<span style="color:red;">Нет</span>');
			} else {
				$this->Assign('validate_http_input', '<span style="color:green;">Да</span>');			
			}

			if(!in_array(strtolower($aMbInfo['http_output']), $this->aValidEnv['http_output'])) {
				$bOk = false;
				$this->Assign('validate_http_output', '<span style="color:red;">Нет</span>');
			} else {
				$this->Assign('validate_http_output', '<span style="color:green;">Да</span>');			
			}

			if(!in_array(strtolower($aMbInfo['func_overload']), $this->aValidEnv['func_overload'])) {
				$bOk = false;
				$this->Assign('validate_func_overload', '<span style="color:red;">Нет</span>');
			} else {
				$this->Assign('validate_func_overload', '<span style="color:green;">Да</span>');			
			}
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
		$oDb = @mysql_connect($aParams['server'],$aParams['user'],$aParams['password']);
		if( $oDb ) {
			mysql_query('set names utf8');
			return $oDb;
		}
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
	protected function CreateTables($sFilePath,$sPrefix=null) {
		$sFileQuery = @file_get_contents($sFilePath);
		if(!$sFileQuery) return array('result'=>false,'errors'=>array("Нет доступа к файлу {$sFilePath}"));
		
		if($sPrefix) $sFileQuery = str_replace('prefix_', $sPrefix, $sFileQuery);
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
			if($sQuery!='') {
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
	protected function ValidateAdminFields() {
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
}

session_start();
$oInstaller = new Install;
$oInstaller->Run();
?>