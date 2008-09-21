<?php
/**
 * Dklab_Cache_Backend_Profiler: wrapper for backend statistics counting.
 *
 * Calls $incrementor each time the code invokes a backend call.
 * This class may be used in debugging purposes.
 *
 * $Id: MetaForm.php 238 2008-03-17 21:07:17Z dk $
 */
require_once "Zend/Cache/Backend/Interface.php";
 
class Dklab_Cache_Backend_Profiler implements Zend_Cache_Backend_Interface 
{
    private $_backend = null;
    private $_incrementor = null;
    
    
    public function __construct(Zend_Cache_Backend_Interface $backend, $incrementor)
    {
        $this->_backend = $backend;
        $this->_incrementor = $incrementor;
    }
    
    
    public function setDirectives($directives)
    {
        return $this->_backend->setDirectives($directives);
    }
    
    
    public function load($id, $doNotTestCacheValidity = false)
    {
        $t0 = microtime(true);
        $result = $this->_backend->load($id, $doNotTestCacheValidity);
        call_user_func($this->_incrementor, microtime(true) - $t0, __METHOD__);
        return $result;        
    }
    
    
    public function test($id)
    {
        $t0 = microtime(true);
        $result = $this->_backend->test($id);
        call_user_func($this->_incrementor, microtime(true) - $t0, __METHOD__);
        return $result;        
    }
    
    
    public function save($data, $id, $tags = array(), $specificLifetime = false)
    {
        $t0 = microtime(true);
        $result = $this->_backend->save($data, $id, $tags, $specificLifetime);
        call_user_func($this->_incrementor, microtime(true) - $t0, __METHOD__);
        return $result;        
    }
    
    
    public function remove($id)
    {
        $t0 = microtime(true);
        $result = $this->_backend->remove($id);
        call_user_func($this->_incrementor, microtime(true) - $t0, __METHOD__);
        return $result;        
    }
    
    
    public function clean($mode = Zend_Cache::CLEANING_MODE_ALL, $tags = array())
    {
        $t0 = microtime(true);
        $result = $this->_backend->clean($mode, $tags);
        call_user_func($this->_incrementor, microtime(true) - $t0, __METHOD__);
        return $result;        
    }
}
