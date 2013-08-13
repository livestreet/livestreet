<?php

// Для эмуляции работы, т.к используется в конфиге
$_SERVER['HTTP_HOST']='localhost';

require_once("./../../config/loader.php");
require_once(dirname(__FILE__).'/lsc.php');


LSC::Start();
