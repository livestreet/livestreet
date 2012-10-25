<?php

require_once("config/loader.php");
require_once("engine/classes/Engine.class.php");

/**
 * Class for load fixtures
 */
class LoadFixtures
{

    /**
     * Main objects references array
     *
     * @var array $aReferences
     */
    private $aReferences = array();

    /**
    * @var Engine
    */
    private $oEngine;

    /**
     * The directory of the fixtures for tests
     *
     * @var string $sDirFixtures
     */
    private $sDirFixtures;

    public function __construct() {
        if (file_exists(Config::Get('path.root.server') . '/config/config.test.php')) {
            Config::LoadFromFile(Config::Get('path.root.server') . '/config/config.test.php', false);
        }

        $this->oEngine = Engine::getInstance();
        $this->oEngine->Init();
        $this->sDirFixtures = realpath((dirname(__FILE__)) . "/fixtures/");
    }

    public function load() {
        $this->loadFixtures();
    }

    /**
     * Recreate database from SQL dumps
     *
     * @return bool
     */
     public function purgeDB() {
        $sDbname = Config::Get('db.params.dbname');

        if (mysql_select_db($sDbname)) {
            mysql_query("DROP DATABASE $sDbname");
            echo "DROP DATABASE $sDbname \n";
        }

        if (mysql_query("CREATE DATABASE $sDbname") === false) {
            return mysql_error();
        }
        echo "CREATE DATABASE $sDbname \n";

        if (mysql_select_db($sDbname) === false) {
            return mysql_error();
        }

        echo "SELECTED DATABASE $sDbname \n";
        // Load dump from install_base.sql
        $result = $this->oEngine->Database_ExportSQL(dirname(__FILE__) . '/fixtures/sql/install_base.sql');

        if (!$result['result']) {
            return $result['errors'];
        }
        // Load dump from geo_base.sql
        $result = $this->oEngine->Database_ExportSQL(dirname(__FILE__) . '/fixtures/sql/geo_base.sql');

        if (!$result['result']) {
            return $result['errors'];
        }

        echo "ExportSQL DATABASE $sDbname \n";
        echo "ExportSQL DATABASE $sDbname -> geo_base \n";

        return true;
    }

    /**
     * Function of loading fixtures from tests/fixtures/
     *
     * @var array $aFiles
     * @var array $iOrder
     * @return void
     */
    private function loadFixtures() {
        $aFiles = glob("{$this->sDirFixtures}/*Fixtures.php");
        $aFixtures = array();
        foreach ($aFiles as $sFilePath) {
            require_once "{$sFilePath}";
            $iOrder = BlogFixtures::getOrder();

            preg_match("/([a-zA-Z]+Fixtures).php$/", $sFilePath, $matches);
            $sClassName = $matches[1];
            $iOrder = forward_static_call(array($sClassName, 'getOrder'));
            if (!array_key_exists($iOrder, $aFixtures)) {
                $aFixtures[$iOrder] = $sClassName;
            } else {
                //@todo разруливание одинаковых ордеров
                throw new Exception("Duplicated order number $iOrder in $sClassName");
            }
        }
        ksort($aFixtures);

        if (count($aFixtures)) {
            foreach ($aFixtures as $iOrder => $sClassName) {
                // @todo референсы дублируются в каждом объекте фиксту + в этом объекте
                $oFixtures = new $sClassName($this->oEngine, $this->aReferences);
                $oFixtures->load();
                $aFixtureReference = $oFixtures->getReferences();
                $this->aReferences = array_merge($this->aReferences, $aFixtureReference);
            }
        }
    }

    /**
     * Function of loading plugin fixtures
     *
     * @param string $plugin
     * @return void
     */
    public function loadPluginFixtures($plugin){
        $sPath = Config::Get('path.root.server').'/plugins/' . $plugin . '/tests/fixtures';
        if (!is_dir($sPath)) {
            throw new InvalidArgumentException('Plugin not found by LS directory: ' . $sPath, 10);
        }

        $this->sDirFixtures = $sPath;
        $this->loadFixtures();
    }

}

