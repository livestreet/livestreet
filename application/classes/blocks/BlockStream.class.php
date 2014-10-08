<?php
/*
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
 * Обработка блока с комментариями (прямой эфир)
 *
 * @package application.blocks
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