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
 * Стена
 *
 * @package blocks
 * @since 2.0
 */
class BlockWall extends Block {
	/**
	 * Запуск обработки
	 */
	public function Exec() {
		$wall = $this->Wall_GetWall( array( 'wall_user_id' => (int) $this->GetParam('user_id'), 'pid' => null ), array( 'id' => 'desc' ), 1, Config::Get( 'module.wall.per_page' ) );
		$posts = $wall['collection'];

		$this->Viewer_Assign('posts', $posts, true);
		$this->Viewer_Assign('count', $wall['count'], true);
		$this->Viewer_Assign('classes', $this->GetParam('classes'), true);
		$this->Viewer_Assign('attributes', $this->GetParam('attributes'), true);
		$this->Viewer_Assign('mods', $this->GetParam('mods'), true);

		if ( count($posts) ) {
			$this->Viewer_Assign('lastId', end($posts)->getId(), true);
		}

		$this->SetTemplate('components/wall/wall.tpl');
	}
}