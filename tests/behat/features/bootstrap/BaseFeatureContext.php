<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\MinkExtension\Context\MinkContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

$sDirRoot = dirname(realpath((dirname(__FILE__)) . "/../../../"));
set_include_path(get_include_path().PATH_SEPARATOR.$sDirRoot);

require_once("tests/LoadFixtures.php");

/**
 * LiveStreet custom feature context
 */
class BaseFeatureContext extends MinkContext
{

    protected static $fixturesLoader = null;

    /**
     * Get fixtures loader
     * @return LoadFixtures
     */
    protected static function getFixturesLoader()
    {
        if (is_null(self::$fixturesLoader)) {
            self::$fixturesLoader = new LoadFixtures();
        }

        return self::$fixturesLoader;
    }

    /**
     * Purge DB and load fixtures before running each test
     *
     * @BeforeScenario
     */
    public static function prepare($event){
        $fixturesLoader = self::getFixturesLoader();
        $fixturesLoader->purgeDB();
        $fixturesLoader->load();
    }

    /**
     * Loading fixture for plugin
     *
     * @Given /^I load fixtures for plugin "([^"]*)"$/
     */
    public function loadFixturesForPlugin($plugin)
    {
        $fixturesLoader = $this->getFixturesLoader();
        $fixturesLoader->loadPluginFixtures($plugin);
    }


    /**
     * @Given /^I am activated plugin "([^"]*)"$/
     */
    public function ActivatedPlugin($plugin)
    {
        $pluginActivation =  new LoadFixtures();
        $pluginActivation->activationPlugin($plugin);
    }

    /**
     * @Then /^I wait "([^"]*)"$/
     */
    public function iWait($time_wait)
    {
        $this->getSession()->wait($time_wait);
    }

}
