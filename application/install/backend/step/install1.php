<?php

class InstallStepInstall1 extends InstallStep {

	public function show() {
		/**
		 * Проверяем требования
		 */
		$aRequirements=array();
		if(!version_compare(PHP_VERSION, '5.3.2', '>=')) {
			$aRequirements[]=array(
				'name'=>'php_version',
				'current'=>PHP_VERSION
			);
		}
		if(!in_array(strtolower(@ini_get('safe_mode')), array('0','off',''))) {
			$aRequirements[]=array(
				'name'=>'safe_mode',
				'current'=>InstallCore::getLang('yes')
			);
		}
		if(!@preg_match('//u', '')) {
			$aRequirements[]=array(
				'name'=>'utf8',
				'current'=>InstallCore::getLang('no')
			);
		}
		if (!@extension_loaded('mbstring')){
			$aRequirements[]=array(
				'name'=>'mbstring',
				'current'=>InstallCore::getLang('no')
			);
		}
		if(!in_array(strtolower(@ini_get('mbstring.func_overload')), array('0','4','no overload'))) {
			$aRequirements[]=array(
				'name'=>'mbstring_func_overload',
				'current'=>InstallCore::getLang('yes')
			);
		}
		if (!@extension_loaded('SimpleXML')){
			$aRequirements[]=array(
				'name'=>'xml',
				'current'=>InstallCore::getLang('no')
			);
		}
		/**
		 * Права на запись файлов
		 */
		$sAppDir=dirname(INSTALL_DIR);
		$sDir=dirname($sAppDir).DIRECTORY_SEPARATOR.'uploads';
		if (!is_dir($sDir) or !is_writable($sDir)) {
			$aRequirements[]=array(
				'name'=>'dir_uploads',
				'current'=>$sDir
			);
		}
		$sDir=$sAppDir.DIRECTORY_SEPARATOR.'plugins';
		if (!is_dir($sDir) or !is_writable($sDir)) {
			$aRequirements[]=array(
				'name'=>'dir_plugins',
				'current'=>$sDir
			);
		}
		$sDir=$sAppDir.DIRECTORY_SEPARATOR.'tmp';
		if (!is_dir($sDir) or !is_writable($sDir)) {
			$aRequirements[]=array(
				'name'=>'dir_tmp',
				'current'=>$sDir
			);
		}
		$sDir=$sAppDir.DIRECTORY_SEPARATOR.'logs';
		if (!is_dir($sDir) or !is_writable($sDir)) {
			$aRequirements[]=array(
				'name'=>'dir_logs',
				'current'=>$sDir
			);
		}
		$sFile=$sAppDir.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config.local.php';
		if (!is_file($sFile) or !is_writable($sFile)) {
			$aRequirements[]=array(
				'name'=>'file_config_local',
				'current'=>$sFile
			);
		}

		if (count($aRequirements)) {
			InstallCore::setNextStepDisable();
		}

		$this->assign('requirements',$aRequirements);
	}

}