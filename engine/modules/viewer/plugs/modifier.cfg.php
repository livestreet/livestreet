<?php

function smarty_modifier_cfg($key, $instance = Config::DEFAULT_CONFIG_INSTANCE) {
	return Config::Get($key, $instance);
}

?>