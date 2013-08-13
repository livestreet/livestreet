<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Cache
 * @subpackage Zend_Cache_Backend
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */


/**
 * @see Zend_Cache_Backend_Interface
 */
require_once LS_DKCACHE_PATH.'Zend/Cache/Backend/Interface.php';

/**
 * @see Zend_Cache_Backend
 */
require_once LS_DKCACHE_PATH.'Zend/Cache/Backend.php';


/**
 * @package    Zend_Cache
 * @subpackage Zend_Cache_Backend
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Cache_Backend_Memcached extends Zend_Cache_Backend implements Zend_Cache_Backend_Interface
{
    /**
     * Default Host IP Address or DNS
     */
    const DEFAULT_HOST       = '127.0.0.1';

    /**
     * Default port
     */
    const DEFAULT_PORT       = 11211;

    /**
     * Persistent
     */
    const DEFAULT_PERSISTENT = true;

    /**
     * Available options
     *
     * =====> (array) servers :
     * an array of memcached server ; each memcached server is described by an associative array :
     * 'host' => (string) : the name of the memcached server
     * 'port' => (int) : the port of the memcached server
     * 'persistent' => (bool) : use or not persistent connections to this memcached server
     *
     * =====> (boolean) compression :
     * true if you want to use on-the-fly compression
     *
     * @var array available options
     */
    protected $_options = array(
        'servers' => array(array(
            'host' => Zend_Cache_Backend_Memcached::DEFAULT_HOST,
            'port' => Zend_Cache_Backend_Memcached::DEFAULT_PORT,
            'persistent' => Zend_Cache_Backend_Memcached::DEFAULT_PERSISTENT
        )),
        'compression' => false
    );

    /**
     * Memcache object
     *
     * @var mixed memcache object
     */
    private $_memcache = null;

    /**
     * Constructor
     *
     * @param array $options associative array of options
     * @throws Zend_Cache_Exception
     * @return void
     */
    public function __construct($options = array())
    {
        if (!extension_loaded('memcache')) {
            Zend_Cache::throwException('The memcache extension must be loaded for using this backend !');
        }
        parent::__construct($options);
        if (isset($this->_options['servers'])) {
            $value= $this->_options['servers'];
            if (isset($value['host'])) {
                // in this case, $value seems to be a simple associative array (one server only)
                $value = array(0 => $value); // let's transform it into a classical array of associative arrays
            }
            $this->setOption('servers', $value);
        }
        $this->_memcache = new Memcache;
        foreach ($this->_options['servers'] as $server) {
            if (!array_key_exists('persistent', $server)) {
                $server['persistent'] = Zend_Cache_Backend_Memcached::DEFAULT_PERSISTENT;
            }
            if (!array_key_exists('port', $server)) {
                $server['port'] = Zend_Cache_Backend_Memcached::DEFAULT_PORT;
            }
            $this->_memcache->addServer($server['host'], $server['port'], $server['persistent']);
        }
    }

    /**
     * Test if a cache is available for the given id and (if yes) return it (false else)
     *
     * @param  string  $id                     Cache id
     * @param  boolean $doNotTestCacheValidity If set to true, the cache validity won't be tested
     * @return string|false cached datas
     */
    public function load($id, $doNotTestCacheValidity = false)
    {
        // WARNING : $doNotTestCacheValidity is not supported !!!
        if ($doNotTestCacheValidity) {
            $this->_log("Zend_Cache_Backend_Memcached::load() : \$doNotTestCacheValidity=true is unsupported by the Memcached backend");
        }
        
        $tmp = $this->_memcache->get($id);
        if (is_array($id)) {
        	if (is_array($tmp)) {
        		$aDataReturn=array();
        		foreach ($tmp as $key => $data) {
        			if (is_array($data)) {
            			$aDataReturn[$key]=$data[0];
        			}
        		}
        		if (count($aDataReturn)>0) {
        			return $aDataReturn;
        		}
        	}        	
        } else {
        	if (is_array($tmp)) {
            	return $tmp[0];
        	}	
        }        
        return false;
    }

    /**
     * Test if a cache is available or not (for the given id)
     *
     * @param  string $id Cache id
     * @return mixed|false (a cache is not available) or "last modified" timestamp (int) of the available cache record
     */
    public function test($id)
    {
        $tmp = $this->_memcache->get($id);
        if (is_array($tmp)) {
            return $tmp[1];
        }
        return false;
    }

    /**
     * Save some string datas into a cache record
     *
     * Note : $data is always "string" (serialization is done by the
     * core not by the backend)
     *
     * @param  string $data             Datas to cache
     * @param  string $id               Cache id
     * @param  array  $tags             Array of strings, the cache record will be tagged by each string entry
     * @param  int    $specificLifetime If != false, set a specific lifetime for this cache record (null => infinite lifetime)
     * @return boolean True if no problem
     */
    public function save($data, $id, $tags = array(), $specificLifetime = false)
    {
        $lifetime = $this->getLifetime($specificLifetime);
        if ($this->_options['compression']) {
            $flag = MEMCACHE_COMPRESSED;
        } else {
            $flag = 0;
        }
        $result = $this->_memcache->set($id, array($data, time()), $flag, $lifetime);
        if (count($tags) > 0) {
            $this->_log("Zend_Cache_Backend_Memcached::save() : tags are unsupported by the Memcached backend");
        }
        return $result;
    }

    /**
     * Remove a cache record
     *
     * @param  string $id Cache id
     * @return boolean True if no problem
     */
    public function remove($id)
    {
        return $this->_memcache->delete($id,0);
    }

    /**
     * Clean some cache records
     *
     * Available modes are :
     * 'all' (default)  => remove all cache entries ($tags is not used)
     * 'old'            => remove too old cache entries ($tags is not used)
     * 'matchingTag'    => remove cache entries matching all given tags
     *                     ($tags can be an array of strings or a single string)
     * 'notMatchingTag' => remove cache entries not matching one of the given tags
     *                     ($tags can be an array of strings or a single string)
     *
     * @param  string $mode Clean mode
     * @param  array  $tags Array of tags
     * @return boolean True if no problem
     */
    public function clean($mode = Zend_Cache::CLEANING_MODE_ALL, $tags = array())
    {
        if ($mode==Zend_Cache::CLEANING_MODE_ALL) {
            return $this->_memcache->flush();
        }
        if ($mode==Zend_Cache::CLEANING_MODE_OLD) {
            $this->_log("Zend_Cache_Backend_Memcached::clean() : CLEANING_MODE_OLD is unsupported by the Memcached backend");
        }
        if ($mode==Zend_Cache::CLEANING_MODE_MATCHING_TAG) {
            $this->_log("Zend_Cache_Backend_Memcached::clean() : tags are unsupported by the Memcached backend");
        }
        if ($mode==Zend_Cache::CLEANING_MODE_NOT_MATCHING_TAG) {
            $this->_log("Zend_Cache_Backend_Memcached::clean() : tags are unsupported by the Memcached backend");
        }
    }

    /**
     * Return true if the automatic cleaning is available for the backend
     *
     * @return boolean
     */
    public function isAutomaticCleaningAvailable()
    {
        return false;
    }

}
