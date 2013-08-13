<?php

class PluginExample_ActionExample extends ActionPlugin {

    /**
     * Инициализация экшена
     */
    public function Init() {
        $this->SetDefaultEvent('index');
    }

    /**
     * Регистрируем евенты
     */
    protected function RegisterEvent() {
        $this->AddEvent('index','EventIndex');

    }

    protected function EventIndex() {

    }

    /**
     * Завершение работы экшена
     */
    public function EventShutdown() {
		/**
		 * Здесь можно прогрузить в шаблон какие-то общие переменные для всех евентов
		 */
    }
}