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
 * Используется для вывода списка опросов в форме редактирования объекта
 *
 * @package application.blocks
 * @since 2.0
 */
class BlockPollFormItems extends Block
{
    /**
     * Запуск обработки
     */
    public function Exec()
    {
        $this->SetTemplate('component@poll.manage.list');

        $sTargetType = $this->GetParam('target_type');
        $sTargetId = $this->GetParam('target_id');
        $sTargetTmp = $this->Session_GetCookie('poll_target_tmp_' . $sTargetType) ? $this->Session_GetCookie('poll_target_tmp_' . $sTargetType) : $this->GetParam('target_tmp');

        $aFilter = array('target_type' => $sTargetType, '#order' => array('id' => 'asc'));
        if ($sTargetId) {
            $sTargetTmp = null;
            if (!$this->Poll_CheckTarget($sTargetType, $sTargetId)) {
                return false;
            }
            $aFilter['target_id'] = $sTargetId;
        } else {
            $sTargetId = null;
            if (!$sTargetTmp or !$this->Poll_IsAllowTargetType($sTargetType)) {
                return false;
            }
            $aFilter['target_tmp'] = $sTargetTmp;
        }
        $aPollItems = $this->Poll_GetPollItemsByFilter($aFilter);

        $this->Viewer_Assign('aPollItems', $aPollItems);
    }
}