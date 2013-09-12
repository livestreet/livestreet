<?php
/**
 * Экшен обработки облака тегов
 *
 * @package actions
 * @since 1.0
 */
class ActionTags extends Action {

    public $sDefaultEvent = 'EventTags';
    
	/**
	 * Инициализация
	 *
	 */
	public function Init() {
	}
	/**
	 * Регистрация евентов
	 */
	protected function RegisterEvent() {
		$this->AddEventPreg('/^.+$/i','/^.*$/i','EventTags');
	}


	/**********************************************************************************
	 ************************ РЕАЛИЗАЦИЯ ЭКШЕНА ***************************************
	 **********************************************************************************
	 */

	/**
	 * Отображение топиков
	 *
	 */
	protected function EventTags() {
		/**
		 * Получаем список тегов
		 */
		$aTags=$this->oEngine->Topic_GetOpenTopicTags(Config::Get('block.tags.tags_count'));
		/**
		 * Расчитываем логарифмическое облако тегов
		 */
		if ($aTags) {
			$this->Tools_MakeCloud($aTags);
			/**
			 * Устанавливаем шаблон вывода
			 */
			$this->Viewer_Assign("aTags",$aTags);
		}
		/**
		 * Устанавливаем шаблон вывода
		 */
		$this->SetTemplateAction('index');
	}
	/**
	 * Выполняется при завершении работы экшена
	 *
	 */
	public function EventShutdown() {
	}
}
?>