<?php

class InstallStepUpdateDb extends InstallStepInstallDb {

	protected function getTemplateName() {
		/**
		 * Показываем шаблон настроек БД
		 */
		return 'steps/installDb.tpl.php';
	}

	public function show() {

	}

	/**
	 * Обработка отправки формы
	 *
	 * @return bool
	 */
	public function process() {
		if (!$aRes=$this->processDbCheck()) {
			return $aRes;
		}
		list($oDb,$sEngineDB)=$aRes;
		/**
		 * Запускаем импорт дампов, сначала GEO DB
		 */
		return true;
	}
}