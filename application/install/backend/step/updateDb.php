<?php

class InstallStepUpdateDb extends InstallStep {

	protected function getTemplateName() {
		/**
		 * Показываем шаблон настроек БД
		 */
		return 'steps/installDb.tpl.php';
	}

	/**
	 * Получаем данные для загрузки на форму
	 * Возможные источники: реквест, конфиг, дефолтные значения
	 *
	 * @param      $sName
	 * @param null $mDefault
	 * @param bool $bUseHtmlspecialchars
	 *
	 * @return mixed|null|string
	 */
	public function getValue($sName,$mDefault=null,$bUseHtmlspecialchars=true) {
		$mResult=null;
		$sNameRequest=str_replace('.','_',$sName);
		if (isset($_REQUEST[$sNameRequest])) {
			$mResult=$_REQUEST[$sNameRequest];
		} else {
			$mResult=InstallConfig::get($sName,$mDefault);
		}
		return $bUseHtmlspecialchars ? htmlspecialchars($mResult) : $mResult;
	}

	public function show() {

	}

	public function process() {
		return true;
	}
}