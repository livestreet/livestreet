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

require_once(Config::Get('path.root.engine').'/lib/external/DklabCache/config.php');
require_once(LS_DKCACHE_PATH.'Zend/Cache.php');
require_once(LS_DKCACHE_PATH.'Cache/Backend/MemcachedMultiload.php');
require_once(LS_DKCACHE_PATH.'Cache/Backend/TagEmuWrapper.php');
require_once(LS_DKCACHE_PATH.'Cache/Backend/Profiler.php');

/**
 * Типы кеширования: file и memory
 *
 */
define('SYS_CACHE_TYPE_FILE','file');
define('SYS_CACHE_TYPE_MEMORY','memory');

/**
 * Модуль кеширования
 * Работает в двух режимах: файловый кеш через Cache Lite и кеш в памяти через Memcache
 *
 */
class ModuleCache extends Module {
	
	protected $oBackendCache=null;
	protected $bUseCache;
	/**
	 * Тип кеширования, прописан в глобльном конфиге config.php
	 *
	 * @var string
	 */
	protected $sCacheType;
	
	protected $aStats=array(
						'time' =>0,
						'count' => 0,
						'count_get' => 0,
						'count_set' => 0,
					);
	
	/**
	 * Инициализируем нужный тип кеша
	 *
	 */
	public function Init() {
		$this->bUseCache=Config::Get('sys.cache.use');
		$this->sCacheType=Config::Get('sys.cache.type');
		
		if (!$this->bUseCache) {
			return false;
		}
		if ($this->sCacheType==SYS_CACHE_TYPE_FILE) {
			require_once(LS_DKCACHE_PATH.'Zend/Cache/Backend/File.php');
			$oCahe = new Zend_Cache_Backend_File(
				array(
					'cache_dir' => Config::Get('sys.cache.dir'),
					'file_name_prefix'	=> Config::Get('sys.cache.prefix'),
					'read_control_type' => 'crc32',
					'hashed_directory_level' => Config::Get('sys.cache.directory_level'), 
					'read_control' => true,
					'file_locking' => true,
				)
			);
			$this->oBackendCache = new Dklab_Cache_Backend_Profiler($oCahe,array($this,'CalcStats'));
		} elseif ($this->sCacheType==SYS_CACHE_TYPE_MEMORY) {
			require_once(LS_DKCACHE_PATH.'Zend/Cache/Backend/Memcached.php');
			$aConfigMem=Config::Get('memcache');
			
			$oCahe = new Dklab_Cache_Backend_MemcachedMultiload($aConfigMem);
			$this->oBackendCache = new Dklab_Cache_Backend_TagEmuWrapper(new Dklab_Cache_Backend_Profiler($oCahe,array($this,'CalcStats')));
		} else {
			throw new Exception("Wrong type of caching: ".$this->sCacheType." (file, memory)");
		}
		/**
		 * Дабы не засорять место протухшим кешем, удаляем его в случайном порядке, например 1 из 50 раз
		 */
		if (rand(1,50)==33) {			
			$this->Clean(Zend_Cache::CLEANING_MODE_OLD);			
		}
	}


	/**
	 * Получить значение из кеша
	 *
	 * @param string $sName	
	 * @return unknown
	 */
	public function Get($sName) {
		if (!$this->bUseCache) {
			return false;
		}
		/**
		 * Т.к. название кеша может быть любым то предварительно хешируем имя кеша
		 */
		if (!is_array($sName)) {
			$sName=md5(Config::Get('sys.cache.prefix').$sName);
			$data=$this->oBackendCache->load($sName);
			if ($this->sCacheType==SYS_CACHE_TYPE_FILE and $data!==false) {
				return unserialize($data);
			} else {
				return $data;
			}
		} else {
			return $this->multiGet($sName);
		}
	}	
	/**
	 * псевдо поддержка мульти-запросов к кешу
	 *
	 * @param  array $aName
	 * @return bool|array
	 */
	public function multiGet($aName) {
		if (count($aName)==0) {
			return false;
		}
		if ($this->sCacheType==SYS_CACHE_TYPE_MEMORY) {
			$aKeys=array();
			$aKv=array();
			foreach ($aName as $sName) {
				$aKeys[]=md5(Config::Get('sys.cache.prefix').$sName);
				$aKv[md5(Config::Get('sys.cache.prefix').$sName)]=$sName;
			}
			$data=$this->oBackendCache->load($aKeys);
			if ($data and is_array($data)) {
				$aData=array();
				foreach ($data as $key => $value) {
					$aData[$aKv[$key]]=$value;					
				}
				if (count($aData)>0) {
					return $aData;
				}
			}
			return false;
		} else {
			$aData=array();
			foreach ($aName as $key => $sName) {
				if ((false !== ($data = $this->Get($sName)))) {
					$aData[$sName]=$data;
				}
			}
			if (count($aData)>0) {
				return $aData;
			}
			return false;
		}
	}
	/**
	 * Записать значение в кеш
	 *
	 * @param  mixed  $data
	 * @param  string $sName
	 * @param  array  $aTags
	 * @param  int    $iTimeLife
	 * @return bool
	 */
	public function Set($data,$sName,$aTags=array(),$iTimeLife=false) {		
		if (!$this->bUseCache) {
			return false;
		}
		/**
		 * Т.к. название кеша может быть любым то предварительно хешируем имя кеша
		 */
		$sName=md5(Config::Get('sys.cache.prefix').$sName);
		if ($this->sCacheType==SYS_CACHE_TYPE_FILE) {		
			$data=serialize($data);
		}
		return $this->oBackendCache->save($data,$sName,$aTags,$iTimeLife);
	}
	/**
	 * Удаляет значение из кеша по ключу(имени)
	 *
	 * @param unknown_type $sName
	 * @return bool
	 */
	public function Delete($sName) {
		if (!$this->bUseCache) {
			return false;
		}
		/**
		 * Т.к. название кеша может быть любым то предварительно хешируем имя кеша
		 */
		$sName=md5(Config::Get('sys.cache.prefix').$sName);
		return $this->oBackendCache->remove($sName);
	}
	/**
	 * Чистит кеши
	 *
	 * @param void $cMode
	 * @param array $aTags
	 * @return bool
	 */
	public function Clean($cMode = Zend_Cache::CLEANING_MODE_ALL, $aTags = array()) {
		if (!$this->bUseCache) {
			return false;
		}
		return $this->oBackendCache->clean($cMode,$aTags);
	}
	/**
	 * Статистика использования кеша
	 *
	 * @param unknown_type $iTime
	 * @param unknown_type $sMethod
	 */
	public function CalcStats($iTime,$sMethod) {
		$this->aStats['time']+=$iTime;
		$this->aStats['count']++;	
		if ($sMethod=='Dklab_Cache_Backend_Profiler::load') {
			$this->aStats['count_get']++;
		}
		if ($sMethod=='Dklab_Cache_Backend_Profiler::save') {
			$this->aStats['count_set']++;
		}		
	}

	public function GetStats() {
		return $this->aStats;
	}
}
?>