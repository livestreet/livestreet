<?php 

$config = array();

$config['head']['default']['js'] = Config::Get('head.default.js');
$config['head']['default']['js'][] = '___path.static.skin___/js/___view.skin___.js';

return $config;

?>