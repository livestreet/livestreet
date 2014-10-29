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
 * Обрабатывает блок облака тегов городов юзеров
 *
 * @package application.blocks
 * @since 1.0
 */
class BlockUsersCities extends Block
{
    /**
     * Запуск обработки
     */
    public function Exec()
    {
        /**
         * Получаем города
         */
        $aCities = $this->Geo_GetGroupCitiesByTargetType('user', 20);
        /**
         * Формируем облако тегов
         */
        $this->Tools_MakeCloud($aCities);
        /**
         * Выводим в шаблон
         */
        $this->Viewer_Assign("cities", $aCities, true);
        $this->SetTemplate('components/user/blocks/block.users-cities.tpl');
    }
}