<?php
use Behat\MinkExtension\Context\MinkContext;

require_once("BaseFeatureContext.php");

/**
 * LiveStreet custom feature context
 */
class FeatureContext extends MinkContext
{
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
        $this->useContext('base', new BaseFeatureContext($parameters));
    }

    public function getMinkContext()
    {
        return $this->getMainContext();
    }
}
