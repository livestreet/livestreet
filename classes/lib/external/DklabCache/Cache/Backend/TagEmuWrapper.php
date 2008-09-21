<?php
/**
 * Dklab_Cache_Backend_TagEmuWrapper: tag wrapper for any Zend_Cache backend.
 * 
 * Implements tags. Tags are emulated via keys: unfortunately this 
 * increases the data read cost (the more tags are assigned to a key,
 * the more read cost becomes).
 *
 * $Id: MetaForm.php 238 2008-03-17 21:07:17Z dk $
 */
require_once "Zend/Cache/Backend/Interface.php";
 
class Dklab_Cache_Backend_TagEmuWrapper implements Zend_Cache_Backend_Interface 
{
    const VERSION = "01";
    
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
        return $this->_loadOrTest($id, $doNotTestCacheValidity, false);
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
        if (is_array($combined[0])) {
            foreach ($combined[0] as $tag => $savedTagVersion) {
                $actualTagVersion = $this->_backend->load($this->_mangleTag($tag));
                if ($actualTagVersion !== $savedTagVersion) {
                    return false;
                }
            }
        }
        return $returnTrueIfValid? true : $combined[1];
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
