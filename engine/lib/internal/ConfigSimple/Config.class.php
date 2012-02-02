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
 * Управление простым конфигом в виде массива
 */
class Config {

	/**
	 * Локальный кеш
	 * @var array
	 */
	protected $aLocalCache = array();
	/**
	 * Default instance to operate with
	 *
	 * @var string
	 */
	const DEFAULT_CONFIG_INSTANCE = 'general';

	/**
	 * Mapper rules for Config Path <-> Constant Name relations
	 *
	 * @var array
	 */
	static protected $aMapper = array(

	);

	/**
	 * Массив сущностей класса
	 *
	 * @var array
	 */
	static protected $aInstance=array();

	/**
	 * Store for configuration entries for current instance
	 *
	 * @var array
	 */
	protected $aConfig=array();

	/**
	 * Disabled constract process
	 */
	protected function __construct() {

	}

	/**
	 * Ограничиваем объект только одним экземпляром
	 *
	 * @param string $sName
	 * @return Config
	 */
	static public function getInstance($sName=self::DEFAULT_CONFIG_INSTANCE) {
		if (isset(self::$aInstance[$sName])) {
			return self::$aInstance[$sName];
		} else {
			self::$aInstance[$sName]= new self();
			return self::$aInstance[$sName];
		}
	}

	/**
	 * Load configuration array from file
	 *
	 * @param  string $sFile
	 * @param  bool $bRewrite
	 * @param string $sInstance
	 * @return ConfigSimple
	 */
	static public function LoadFromFile($sFile,$bRewrite=true,$sInstance=self::DEFAULT_CONFIG_INSTANCE) {
		// Check if file exists
		if (!is_file($sFile)) {
			return false;
		}
		// Get config from file
		$aConfig=include($sFile);
		return self::Load($aConfig,$bRewrite,$sInstance);
	}

	/**
	 * Load configuration array from given array
	 *
	 * @param  string $aConfig
	 * @param  bool   $bRewrite
	 * @param string $sInstance
	 * @return ConfigSimple
	 */
	static public function Load($aConfig,$bRewrite=true,$sInstance=self::DEFAULT_CONFIG_INSTANCE) {
		// Check if it`s array
		if(!is_array($aConfig)) {
			return false;
		}
		// Set config to current or handle instance
		self::getInstance($sInstance)->SetConfig($aConfig,$bRewrite);
		return self::getInstance($sInstance);
	}

	/**
	 * @return array
	 */
	public function GetConfig() {
		return $this->aConfig;
	}

	/**
	 * @param array $aConfig
	 * @param bool $bRewrite
	 * @return bool
	 */
	public function SetConfig($aConfig=array(),$bRewrite=true) {

		if (is_array($aConfig)) {
			if ($bRewrite) {
				$this->aConfig=$aConfig;
				$this->aLocalCache=array();
			} else {
				$this->aConfig=$this->ArrayEmerge($this->aConfig,$aConfig);
				$this->CleanLocalCache($aConfig);
			}
			return true;
		}
		$this->aLocalCache=$this->aConfig=array();
		return false;
	}

	/**
	 * Retrive information from configuration array
	 *
	 * @param  string $sKey      Path to needed value
	 * @param  string $sInstance Name of needed instance
	 * @return mixed
	 */
	static public function Get($sKey='', $sInstance=self::DEFAULT_CONFIG_INSTANCE) {
		// Return all config array
		if($sKey=='') {
			return self::getInstance($sInstance)->GetConfig();
		}

		return self::getInstance($sInstance)->GetValue($sKey, $sInstance);
	}

	/**
	 * Получает значение из конфигурации по переданному ключу
	 *
	 * @param  string $sKey
	 * @param  string $sInstance
	 * @return mixed
	 */
	public function GetValue($sKey, $sInstance = self::DEFAULT_CONFIG_INSTANCE)
	{
		//Отдаем значение из кеша, если мы уже считали это значение
		if (!isset($this->aLocalCache[$sKey])) {
			// Return config by path (separator=".")
			$aKeys = explode('.', $sKey);

			$this->aLocalCache[$sKey] = $this->GetConfig();
			foreach ((array)$aKeys as $sK) {
				if (isset($this->aLocalCache[$sKey][$sK])) {
					$this->aLocalCache[$sKey] = $this->aLocalCache[$sKey][$sK];
				}
				else {
					return $this->aLocalCache[$sKey] = null;
				}
			}

			$this->aLocalCache[$sKey] = self::KeyReplace($this->aLocalCache[$sKey], $sInstance);
		}
		return $this->aLocalCache[$sKey];
	}

	static public function KeyReplace($cfg,$sInstance=self::DEFAULT_CONFIG_INSTANCE) {
		if(is_array($cfg)) {
			foreach($cfg as $k=>$v) {
				$k_replaced = self::KeyReplace($k, $sInstance);
				unset($cfg[$k]);
				$cfg[$k_replaced] = self::KeyReplace($v,$sInstance);
			}
		}
		else {
			$cfg = preg_replace_callback(
				'~___([\S|\.]+)___~Ui',
				create_function('$value', 'return Config::Get($value[1],"' . $sInstance . '");'),
				$cfg
			);
		}
		return $cfg;
	}

	/**
	 * Try to find element by given key
	 * Using function ARRAY_KEY_EXISTS (like in SPL)
	 *
	 * Workaround for http://bugs.php.net/bug.php?id=40442
	 *
	 * @param  string $sKey      Path to needed value
	 * @param  string $sInstance Name of needed instance
	 * @return bool
	 */
	static public function isExist($sKey, $sInstance=self::DEFAULT_CONFIG_INSTANCE) {
		// Return all config array
		if($sKey=='') {
			return (count((array)self::getInstance($sInstance)->GetConfig())>0);
		}
		// Analyze config by path (separator=".")
		$aKeys=explode('.',$sKey);
		$cfg=self::getInstance($sInstance)->GetConfig();
		foreach ((array)$aKeys as $sK) {
			if (array_key_exists($sK, $cfg)) {
				$cfg=$cfg[$sK];
			} else {
				return false;
			}
		}
		return true;
	}

	/**
	 * Add information in config array by handle path
	 *
	 * @param  string $sKey
	 * @param  mixed $value
	 * @param  string $sInstance
	 * @return bool
	 */
	static public function Set($sKey,$value,$sInstance=self::DEFAULT_CONFIG_INSTANCE) {
		$aKeys=explode('.',$sKey);
		if(isset($value['$root$']) && is_array($value['$root$'])){
			$aRoot = $value['$root$'];
			unset($value['$root$']);
			foreach($aRoot as $sRk => $mRv){
				self::Set(
					$sRk,
					self::isExist($sRk)
						? func_array_merge_assoc(Config::Get($sRk, $sInstance), $mRv)
						: $mRv
					,
					$sInstance
				);
			}
		}

		$sEval='self::getInstance($sInstance)->aConfig';
		foreach ($aKeys as $sK) {
			$sEval.='['.var_export((string)$sK,true).']';
		}
		$sEval.='=$value;';
		eval($sEval);
		//очищаем значение в кеше
		self::getInstance($sInstance)->CleanLocalCache(array($sKey=>$value));
		return true;
	}


	/**
	 * Очищаем кеш по ключам массива.
	 * @param $value
	 */
	protected function CleanLocalCache($value) {
		if ($aKeys = $this->func_array_keys_recursive($value)) {
			foreach ($aKeys as $sK) {
				$part = array();
				foreach (explode('.', $sK) as $sTmp) {
					$part[] = $sTmp;
					unset($this->aLocalCache[implode('.', $part)]);
				}
			}
		}
	}
	/**
	 * Find all keys recursivly in config array
	 *
	 * @return array
	 */
	public function GetKeys() {
		$cfg=$this->GetConfig();
		// If it`s not array, return key
		if(!is_array($cfg)) {
			return false;
		}
		// If it`s array, get array_keys recursive
		return $this->func_array_keys_recursive($cfg);
	}

	/**
	 * Define constants using config-constant mapping
	 *
	 * @param  string $sKey
	 * @param  string $sInstance
	 * @return bool
	 */
	static public function DefineConstant($sKey='',$sInstance=self::DEFAULT_CONFIG_INSTANCE) {
		if($aKeys=self::getInstance($sInstance)->GetKeys()) {
			foreach($aKeys as $key) {
				// If there is key-mapping rool, replace it
				$sName = isset(self::$aMapper[$key])
					? self::$aMapper[$key]
					: strtoupper(str_replace('.','_',$key));
				if( (substr($key,0,strlen($sKey))==strtoupper($sKey))
						&& !defined($sName)
							&& (self::isExist($key,$sInstance)) )
				{
					$cfg=self::Get($key,$sInstance);
					// Define constant, if founded value is scalar or NULL
					if(is_scalar($cfg)||$cfg===NULL)define(strtoupper($sName),$cfg);
				}
			}
			return true;
		}
		return false;
	}

	protected function ArrayEmerge($aArr1,$aArr2) {
		return $this->func_array_merge_assoc($aArr1,$aArr2);
	}

	/**
	 * Рекурсивный вариант array_keys
	 *
	 * @param  array $array
	 * @return array
	 */
	protected function func_array_keys_recursive($array) {
		if(!is_array($array)) {
			return false;
		} else {
			$keys = array_keys($array);
			foreach ($keys as $k=>$v) {
				if($append = $this->func_array_keys_recursive($array[$v])){
					unset($keys[$k]);
					foreach ($append as $new_key){
						$keys[] = $v.".".$new_key;
					}
				}
			}
			return $keys;
		}
	}

	/**
	 * Сливает два ассоциативных массива
	 *
	 * @param unknown_type $aArr1
	 * @param unknown_type $aArr2
	 * @return unknown
	 */
	protected function func_array_merge_assoc($aArr1,$aArr2) {
		$aRes=$aArr1;
		foreach ($aArr2 as $k2 => $v2) {
			$bIsKeyInt=false;
			if (is_array($v2)) {
				foreach ($v2 as $k => $v) {
					if (is_int($k)) {
						$bIsKeyInt=true;
						break;
					}
				}
			}
			if (is_array($v2) and !$bIsKeyInt and isset($aArr1[$k2])) {
				$aRes[$k2]=$this->func_array_merge_assoc($aArr1[$k2],$v2);
			} else {
				$aRes[$k2]=$v2;
			}
		}
		return $aRes;
	}
}
?>