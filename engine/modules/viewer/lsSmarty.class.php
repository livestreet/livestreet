<?php
class lsSmarty extends Smarty {
	function _smarty_include($params) {
    	if (isset($params['smarty_include_tpl_file'])) {
    		$params['smarty_include_tpl_file']=Engine::getInstance()->Plugin_GetDelegate('template',$params['smarty_include_tpl_file']);
    	}
    	parent::_smarty_include($params);
    }	
}