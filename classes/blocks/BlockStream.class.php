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
 * Обработка блока с комментариями
 *
 */
class BlockStream extends Block {
	public function Exec() {
		if ($aComments=$this->Comment_GetCommentsAllGroup(BLOCK_STREAM_COUNT_ROW)) {
			$this->Viewer_Assign('aComments',$aComments);
			$sTextResult=$this->Viewer_Fetch("block.stream_comment.tpl");
			$this->Viewer_Assign('sStreamComments',$sTextResult);
		}
	}
}
?>