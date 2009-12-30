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
 * Абстрактный класс мапера
 *
 */
abstract class Mapper extends Object {
	protected $oDb;
		
	/**
	 * Сохраняем коннект к БД
	 *
	 * @param DbSimple_Generic_Database $oDb
	 */
	public function __construct(DbSimple_Generic_Database $oDb) {
		$this->oDb = $oDb;
	}

}
?>