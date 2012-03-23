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
 * Обрабатывает блок облака тегов городов юзеров
 *
 */
class BlockTagsCity extends Block {
	public function Exec() {
		$aCities=$this->Geo_GetGroupCitiesByTargetType('user',20);
		$this->Tools_MakeCloud($aCities);

		$this->Viewer_Assign("aCityList",$aCities);
	}
}
?>