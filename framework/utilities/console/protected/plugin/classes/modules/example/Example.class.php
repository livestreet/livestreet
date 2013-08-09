<?php

class PluginExample_ModuleExample extends Module {

	protected $oMapper=null;
	/**
	 * Инициализация модуля. Это обязательный метод
	 */
	public function Init() {
		/**
		 * Создаем объект маппера PluginExample_ModuleExample_MapperExample
		 */
		$this->oMapper=Engine::GetMapper(__CLASS__);
	}
}