<?php
/**
 * Dklab_Cache_Backend_TagEmuWrapper: tag wrapper for any Zend_Cache backend.
 * 
 * Implements tags. Tags are emulated via keys: unfortunately this 
 * increases the data read cost (the more tags are assigned to a key,
 * the more read cost becomes).
 *
 * $Id$
 */
require_once LS_DKCACHE_PATH."Zend/Cache/Backend/Interface.php";
 
class Dklab_Cache_Backend_TagEmuWrapper implements Zend_Cache_Backend_Interface 
{
    const VERSION = "1.50";
    
    private $_backend = null;
    
    
    public function __construct(Zend_Cache_Backend_Interface $backend)
    {
        $this->_backend = $backend;
    }
    
    
    public function setDirectives($directives)
    {
        return $this->_backend->setDirectives($directives);
    }
    
    
    public function load($id, $doNotTestCacheValidity = false)
    {
        return $this->_loadOrTestMulti($id, $doNotTestCacheValidity, false);
    }
    
   
    public function save($data, $id, $tags = array(), $specificLifetime = false)
    {
        // Save/update tags as usual infinite keys with value of tag version.
        // If the tag already exists, do not rewrite it. 
        $tagsWithVersion = array();
        if (is_array($tags)) {
            foreach ($tags as $tag) {
                $mangledTag = $this->_mangleTag($tag);
                $tagVersion = $this->_backend->load($mangledTag);
                if ($tagVersion === false) {
                    $tagVersion = $this->_generateNewTagVersion();
                    $this->_backend->save($tagVersion, $mangledTag, array(), null);
                }
                $tagsWithVersion[$tag] = $tagVersion;
            }
        }
        // Data is saved in form of: array(tagsWithVersionArray, anyData).
        $combined = array($tagsWithVersion, $data);
        $serialized = serialize($combined);
        return $this->_backend->save($serialized, $id, array(), $specificLifetime);
    }
    

    public function clean($mode = Zend_Cache::CLEANING_MODE_ALL, $tags = array())
    {
        if ($mode == Zend_Cache::CLEANING_MODE_MATCHING_TAG) {
            if (is_array($tags)) {
                foreach ($tags as $tag) {
                    $this->_backend->remove($this->_mangleTag($tag));
                }
            }
        } else {
            return $this->_backend->clean($mode, $tags);
        }
    }

    
    public function test($id)
    {
        return $this->_loadOrTest($id, false, true);
    }
    
    
    public function remove($id)
    {
        return $this->_backend->remove($id);
    }
    
    
    /**
     * Mangles the name to deny intersection of tag keys & data keys.
     * Mangled tag names are NOT saved in memcache $combined[0] value,
     * mangling is always performed on-demand (to same some space).
     * 
     * @param string $tag    Tag name to mangle.
     * @return string        Mangled tag name.
     */
    private function _mangleTag($tag)
    {
        return __CLASS__ . "_" . self::VERSION . "_" . $tag;
    }


    /**
     * The same as _mangleTag(), but mangles a list of tags.
     * 
     * @see self::_mangleTag
     * @param array $tags   Tags to mangle.
     * @return array        List of mangled tags.
     */
    private function _mangleTags($tags)
    {
        foreach ($tags as $i => $tag) {
            $tags[$i] = $this->_mangleTag($tag);
        }
        return $tags;
    }
    

    /**
     * Common method called from load() and test().
     * 
     * @param string $id
     * @param bool $doNotTestCacheValidity
     * @param bool $returnTrueIfValid   If true, returns not the value contained 
     *                                  in the slot, but "true".
     * @return mixed
     */
    private function _loadOrTest($id, $doNotTestCacheValidity = false, $returnTrueIfValid = false)
    {
        // Data is saved in form of: array(tagsWithVersionArray, anyData).
        $serialized = $this->_backend->load($id, $doNotTestCacheValidity);
        if ($serialized === false) {
            return false;
        }
        $combined = unserialize($serialized);
        if (!is_array($combined)) {
            return false;
        } 
        // Test if all tags has the same version as when the slot was created
        // (i.e. still not removed and then recreated).
        if (is_array($combined[0]) && $combined[0]) {
            if (method_exists($this->_backend, 'multiLoad')) {
                // If we have multiLoad(), optimize queries into one.
                $allMangledTagValues = $this->_backend->multiLoad($this->_mangleTags(array_keys($combined[0])));
                foreach ($combined[0] as $tag => $savedTagVersion) {
                    $actualTagVersion = @$allMangledTagValues[$this->_mangleTag($tag)];
                    if ($actualTagVersion !== $savedTagVersion) {
                        return false;
                    }
                }
            } else {
                // Check all tags versions AND STOP IF WE FOUND AN INCONSISTENT ONE.
                // Note that this optimization works fine only if $this->_backend is
                // references to Dklab_Cache_Backend, but NOT via Dklab_Cache_Backend
                // wrappers, because such wrappers emulate multiLoad() via multiple
                // load() calls.
                foreach ($combined[0] as $tag => $savedTagVersion) {
                    $actualTagVersion = $this->_backend->load($this->_mangleTag($tag));
                    if ($actualTagVersion !== $savedTagVersion) {
                        return false;
                    }
                }
            }
        }
        return $returnTrueIfValid? true : $combined[1];
    }

    private function _loadOrTestMulti($id, $doNotTestCacheValidity = false, $returnTrueIfValid = false)
    {    	
    	if (!is_array($id) or !method_exists($this->_backend, 'multiLoad')) {
    		return $this->_loadOrTest($id,$doNotTestCacheValidity,$returnTrueIfValid);
    	}
    	    	
    	$aDataMulti=$this->_backend->load($id, $doNotTestCacheValidity);    	
    	if ($aDataMulti === false) {
            return false;
        }
        $aDataReturn=array();
        foreach ($aDataMulti as $sKey => $serialized) {
        	if ($serialized === false) {
            	continue;
        	}
        	$combined = unserialize($serialized);
        	if (!is_array($combined)) {
            	continue;
        	}
        	if (is_array($combined[0]) && $combined[0]) {
        		$allMangledTagValues = $this->_backend->multiLoad($this->_mangleTags(array_keys($combined[0])));
        		foreach ($combined[0] as $tag => $savedTagVersion) {
        			$actualTagVersion = @$allMangledTagValues[$this->_mangleTag($tag)];
        			if ($actualTagVersion !== $savedTagVersion) {
        				continue 2;
        			}
        		}
        	}
        	$aDataReturn[$sKey]=$returnTrueIfValid ? true : $combined[1];
        }
        if (count($aDataReturn)>0) {
        	return $aDataReturn;
        }
    	return false;    	
    }

    /**
     * Generates a new unique identifier for tag version.
     * 
     * @return string Globally (hopefully) unique identifier.
     */
    private function _generateNewTagVersion()
    {
        static $counter = 0;
        $counter++;
        return md5(microtime() . getmypid() . uniqid('') . $counter); 
    }
}
