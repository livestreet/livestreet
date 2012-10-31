<?php

require_once("BaseFeatureContext.php");

/**
 * LiveStreet custom feature context
 */
class FeatureContext extends BaseFeatureContext
{
    /**
     * @Then /^I wait "([^"]*)"$/
     */
    public function iWait($time_wait)
    {
        $this->getSession()->wait($time_wait);
    }
}
