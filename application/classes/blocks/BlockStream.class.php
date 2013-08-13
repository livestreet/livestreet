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
 * Обработка блока с комментариями (прямой эфир)
 *
 * @package blocks
 * @since 1.0
 */
class BlockStream extends Block {
	/**
	 * Запуск обработки
	 */
	public function Exec() {
		/**
		 * Получаем комментарии
		 */
		if ($aComments=$this->Comment_GetCommentsOnline('topic',Config::Get('block.stream.row'))) {
			$oViewer=$this->Viewer_GetLocalViewer();
			$oViewer->Assign('aComments',$aComments);
			/**
			 * Формируем результат в виде шаблона и возвращаем
			 */
			$sTextResult=$oViewer->Fetch("blocks/block.stream_comment.tpl");
			$this->Viewer_Assign('sStreamComments',$sTextResult);
		}
	}
}
?>