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
 * Регистрация хука для вывода ссылки спонсора релиза
 *
 */
class HookSponsor extends Hook {
	public function RegisterHook() {
		$this->AddHook('template_copyright','SponsorLink',__CLASS__,-100);
	}

	public function SponsorLink() {
		/**
		 * Выводим на странице списка блогов и списка всех комментов
		 */
		if (Router::GetAction()=='blogs' or Router::GetAction()=='comments') {
			return 'Спонсор релиза LiveStreet - <a href="http://radiorealty.ru" target="_blank">Портал недвижимости</a>';
		}
		return '';
	}
}
?>