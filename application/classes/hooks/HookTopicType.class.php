<?php
/**
 * LiveStreet CMS
 * Copyright © 2013 OOO "ЛС-СОФТ"
 *
 * ------------------------------------------------------
 *
 * Official site: www.livestreetcms.com
 * Contact e-mail: office@livestreetcms.com
 *
 * GNU General Public License, version 2:
 * http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * ------------------------------------------------------
 *
 * @link http://www.livestreetcms.com
 * @copyright 2013 OOO "ЛС-СОФТ"
 * @author Maxim Mzhelskiy <rus.engine@gmail.com>
 *
 */

/**
 * Хук для интеграции типов топиков с дополнительными полями
 */
class HookTopicType extends Hook {

	public function RegisterHook() {
		$this->AddHook('lang_init_start','InitStart');
	}

	public function InitStart() {
		/**
		 * Получаем список типов топиков
		 */
		$aTopicTypeItems=$this->Topic_GetTopicTypeItems();
		foreach($aTopicTypeItems as $oType) {
			/**
			 * Запускаем механизм свойств(дополнительныз полей) для каждого вида топика
			 */
			$this->Property_AddTargetType('topic_'.$oType->getCode(),array('entity'=>'ModuleTopic_EntityTopic','name'=>'Топик - '.$oType->getName()));
		}
	}
}