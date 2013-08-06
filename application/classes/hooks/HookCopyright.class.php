<?php
/*-------------------------------------------------------
*
*   LiveStreet Engine Social Networking
*   Copyright © 2008 Mzhelskiy Maxim
*
*--------------------------------------------------------
*
*   Official site: www.livestreet.ru
*   Contact e-mail: rus.engine@gmail.com
*
*   GNU General Public License, version 2:
*   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*
---------------------------------------------------------
*/

/**
 * Регистрация хука для вывода ссылки копирайта
 *
 * @package hooks
 * @since 1.0
 */
class HookCopyright extends Hook {
	/**
	 * Регистрируем хуки
	 */
	public function RegisterHook() {
		$this->AddHook('template_copyright','CopyrightLink',__CLASS__,-100);
	}
	/**
	 * Обработка хука копирайта
	 *
	 * @return string
	 */
	public function CopyrightLink() {
		/**
		 * Выводим везде, кроме страницы списка блогов и списка всех комментов
		 */
		return '&copy; Powered by <a href="http://livestreetcms.org">LiveStreet CMS</a>';
	}
}
?>