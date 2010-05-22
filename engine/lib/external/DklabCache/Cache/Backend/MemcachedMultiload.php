<?php
/**
 * Dklab_Cache_Backend_MemcachedMultiload: memcached native backend 
 * with multi-get query support (for faster tag-based operations).
 *
 * Note that class uses a dirty hack to fetch the real memcache handle from
 * a private property of Zend_Cache_Backend_Memcached. No chanse that ZF 
 * developers will do it protected in a next release...
 *
 * $Id$
 */
require_once LS_DKCACHE_PATH."Zend/Cache/Backend/Memcached.php";
 
class Dklab_Cache_Backend_MemcachedMultiload extends Zend_Cache_Backend_Memcached
{
    private $_handle;
    
    
    /**
     * Constructor.
     * 
     * @see Zend_Cache_Backend_Memcached::__construct()
     */
    public function __construct($options = array())
    {
        parent::__construct($options);
        $this->_handle = self::_getPrivateProp($this, "_memcache"); 
    }
    
    
    /**
     * Returns native handle.
     * 
     * @return Memcache   Native PHP memcache handle.
     */
    protected function _getHandle()
    {
        return $this->_handle;
    }
    
    
    /**
     * Loads an array of items from the memcached.
     * Extends Zend_Cache_Backend_Memcached with support of multi-get feature.
     * 
     * @param array $ids                    A list of IDs to be loaded.
     * @param bool $doNotTestCacheValidity  See parent method.
     * @return array                        An array of values for each ID.
     */
    public function multiLoad($ids, $doNotTestCacheValidity = false)
    {
        if (!is_array($ids)) {
            Zend_Cache::throwException('multiLoad() expects parameter 1 to be array, ' . gettype($ids) . ' given');
        }
        if ($doNotTestCacheValidity) {
            $this->_log("Zend_Cache_Backend_Memcached::load() : \$doNotTestCacheValidity=true is unsupported by the Memcached backend");
        }
        $tmp = $this->_getHandle()->get($ids);
        if (!is_array($tmp)) {
        	$tmp=array($tmp);
        }
        foreach ($tmp as $k => $v) {
            if (is_array($v)) {
                $tmp[$k] = $v[0];
            }
        }
        return $tmp;
    }
    
    
    /**
     * Reads a private or protected property from the object.
     * Unfortunately we have to use this hack, because Zend_Cache_Backend_Memcached
     * does not declare $_memcache handle as protected.
     * 
     * In PHP private properties are named with \x00 in the name.
     * 
     * @param object $obj   Object to read a property from.
     * @param string $name  Name of a protected or private property.
     * @return mixed        Property value or exception if property is not found.
     */
    private static function _getPrivateProp($obj, $name)
    {
        $arraized = (array)$obj;
        foreach ($arraized as $k => $v) {
            if (substr($k, -strlen($name)) === $name) {
                return $v;
            }
        }
        throw new Exception(
            "Cannot find $name property in Zend_Cache_Backend_Memcached; properties are: " 
            . array_map('addslashes', array_keys($arraized))
        );
    }
}
