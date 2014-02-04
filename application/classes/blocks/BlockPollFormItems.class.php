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
 * Используется для вывода списка опросов в форме редактирования объекта
 *
 * @package blocks
 * @since 1.0
 */
class BlockPollFormItems extends Block {
	/**
	 * Запуск обработки
	 */
	public function Exec() {
		$sTargetType=$this->GetParam('target_type');
		$sTargetId=$this->GetParam('target_id');
		$sTargetTmp=empty($_COOKIE['poll_target_tmp_'.$sTargetType]) ? $this->GetParam('target_tmp') : $_COOKIE['poll_target_tmp_'.$sTargetType];

		$aFilter=array('target_type'=>$sTargetType,'#order'=>array('id'=>'asc'));
		if ($sTargetId) {
			$sTargetTmp=null;
			if (!$this->Poll_CheckTarget($sTargetType,$sTargetId)) {
				return false;
			}
			$aFilter['target_id']=$sTargetId;
		} else {
			$sTargetId=null;
			if (!$sTargetTmp or !$this->Poll_IsAllowTargetType($sTargetType)) {
				return false;
			}
			$aFilter['target_tmp']=$sTargetTmp;
		}
		$aPollItems=$this->Poll_GetPollItemsByFilter($aFilter);

		$this->Viewer_Assign('aPollItems',$aPollItems);
	}
}