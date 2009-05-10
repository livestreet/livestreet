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

require_once(DIR_SERVER_ROOT.'/classes/lib/external/DklabCache/config.php');
require_once('Zend/Cache.php');
require_once('Cache/Backend/TagEmuWrapper.php');
require_once('Cache/Backend/Profiler.php');

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
class LsCache extends Module {
	
	protected $oBackendCache=null;
	protected $bUseCache=SYS_CACHE_USE;
	/**
	 * Тип кеширования, прописан в глобльном конфиге config.php
	 *
	 * @var string
	 */
	protected $sCacheType=SYS_CACHE_TYPE;
	
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
		if (!$this->bUseCache) {
			return false;
		}
		if ($this->sCacheType==SYS_CACHE_TYPE_FILE) {
			require_once('Zend/Cache/Backend/File.php');
			$oCahe = new Zend_Cache_Backend_File(
				array(
					'cache_dir' => SYS_CACHE_DIR,
					'file_name_prefix'	=> SYS_CACHE_PREFIX,
					'read_control_type' => 'crc32',
					'read_control' => true,
					'file_locking' => true,
				)
			);
			$this->oBackendCache = new Dklab_Cache_Backend_Profiler($oCahe,array($this,'CalcStats'));
		} elseif ($this->sCacheType==SYS_CACHE_TYPE_MEMORY) {
			require_once('Zend/Cache/Backend/Memcached.php');
			$aConfigMem=include(DIR_SERVER_ROOT."/config/config.memcache.php");
			$oCahe = new Zend_Cache_Backend_Memcached($aConfigMem);
			$this->oBackendCache = new Dklab_Cache_Backend_TagEmuWrapper(new Dklab_Cache_Backend_Profiler($oCahe,array($this,'CalcStats')));
		} else {
			throw new Exception($this->Lang_Get('system_error_cache_type').": ".$this->sCacheType." (file, memory)");
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
		$sName=md5(SYS_CACHE_PREFIX.$sName);	
		if ($this->sCacheType==SYS_CACHE_TYPE_FILE) {	
			return unserialize($this->oBackendCache->load($sName));
		} else {
			return $this->oBackendCache->load($sName);
		}
	}	
	/**
	 * Записать значение в кеш
	 *
	 * @param unknown_type $data
	 * @param string $sName
	 * @param array $aTags
	 * @param int $iTimeLife
	 * @return bool
	 */
	public function Set($data,$sName,$aTags=array(),$iTimeLife=false) {
		if (!$this->bUseCache) {
			return false;
		}
		/**
		 * Т.к. название кеша может быть любым то предварительно хешируем имя кеша
		 */
		$sName=md5(SYS_CACHE_PREFIX.$sName);
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
		$sName=md5(SYS_CACHE_PREFIX.$sName);
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
		//dump($sMethod);			
	}

	public function GetStats() {
		return $this->aStats;
	}

}
?>