<?php

/**
 * Abstract class for LiveStreet fixtures
 */
abstract class AbstractFixtures
{

    /**
     * @var Engine
     */
    protected $oEngine;

    /**
     * Objects references
     *
     * @var array
     */
    private $aReferences = array();

    /**
     * @param Engine $oEngine
     * @param array $aReferences
     * @return void
     */
    public function __construct(Engine $oEngine, $aReferences)
    {
        $this->oEngine = $oEngine;
        $this->aReferences = $aReferences;
    }

    /**
     * Add reference
     *
     * @param string $name
     * @param array $data
     * @return void
     */
    public function addReference($name, $data)
    {
        $this->aReferences[$name] = $data;
    }

    /**
     * Get reference by key
     *
     * @param string $key
     * @throws Exception if reference is not exist
     * @return array aReferences
     * @return void
     */
    public function getReference($key)
    {
        if (isset($this->aReferences[$key])) {
            return $this->aReferences[$key];
        }

        throw new Exception("Fixture reference \"$key\" is not exist");
    }

    /**
     * Get all references
     *
     * @return array aReferences
     */
    public function getReferences()
    {
        return $this->aReferences;
    }

    /**
     * Creating entities and saving them to DB
     *
     * @return void
     */
    abstract public function load();

    /**
     * Get order number for fixture
     *
     * @return int
     */
    public static function getOrder() {
        return 0;
    }
}

