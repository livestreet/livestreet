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

            $result = mysql_query("SELECT concat('TRUNCATE TABLE ', TABLE_NAME)
                                   FROM INFORMATION_SCHEMA.TABLES
                                   WHERE TABLE_SCHEMA  = '" . $sDbname . "'");

            mysql_query('SET FOREIGN_KEY_CHECKS = 0');
            echo "TRUNCATE TABLE FROM TEST BASE";
            while ($row = mysql_fetch_row($result)) {
                if (!mysql_query($row[0])) {
                    // exception
                    throw new Exception("TRUNCATE TABLE FROM TEST BASE - Exception");
                }
            }
            mysql_query('SET FOREIGN_KEY_CHECKS = 1');

            mysql_free_result($result);
        } else {

            if (mysql_query("CREATE DATABASE IF NOT EXISTS $sDbname") === false) {
                // exception
                throw new Exception("DB \"$sDbname\" is not Created");
                echo "CREATE DATABASE $sDbname \n";
                return mysql_error();
            } else {

                mysql_select_db($sDbname);

                // Load dump from install_base.sql
                $result = $this->oEngine->Database_ExportSQL(dirname(__FILE__) . '/fixtures/sql/install_base.sql');

                if (!$result['result']) {
                    // exception
                    throw new Exception("DB is not exported with file install_base.sql");
                    return $result['errors'];
                }
                echo "ExportSQL DATABASE $sDbname -> install_base.sql \n";
                // Load dump from geo_base.sql
                $result = $this->oEngine->Database_ExportSQL(dirname(__FILE__) . '/fixtures/sql/geo_base.sql');

                if (!$result['result']) {
                    // exception
                    throw new Exception("DB is not exported with file geo_base.sql");
                    return $result['errors'];
                }
                echo "ExportSQL DATABASE $sDbname -> geo_base \n";

            }
        }
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
            $aFixtures[$iOrder][] = $sClassName;
        }
        ksort($aFixtures);

        if (count($aFixtures)) {
            foreach ($aFixtures as $iOrder => $aClassNames) {
                foreach ($aClassNames as $sClassName) {
                    // @todo референсы дублируются в каждом объекте фиксту + в этом объекте
                    $oFixtures = new $sClassName($this->oEngine, $this->aReferences);
                    $oFixtures->load();
                    $aFixtureReference = $oFixtures->getReferences();
                    $this->aReferences = array_merge($this->aReferences, $aFixtureReference);
                }
            }
        }
    }

    /**
     * Function of loading plugin fixtures
     *
     * @param string $plugin
     * @return void
     */
    public function loadPluginFixtures($plugin) {
        $sPath = Config::Get('path.root.server') . '/plugins/' . $plugin . '/tests/fixtures';
        if (!is_dir($sPath)) {
            throw new InvalidArgumentException('Plugin not found by LS directory: ' . $sPath, 10);
        }

        $this->sDirFixtures = $sPath;
        $this->loadFixtures();
    }

    /**
     * Function of activate plugin
     *
     * @param string $plugin
     */
    public function activationPlugin($plugin){
        $this->oEngine->ModulePlugin_Toggle($plugin,'Activate');
    }

}

