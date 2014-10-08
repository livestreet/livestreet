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
 * Поведение для подключения функционала дополнительных полей к модулям
 *
 * @package application.modules.property
 * @since 2.0
 */
class ModuleProperty_BehaviorModule extends Behavior
{
    /**
     * Список хуков
     *
     * @var array
     */
    protected $aHooks = array(
        'module_orm_GetItemsByFilter_after'  => array(
            'CallbackGetItemsByFilterAfter',
            1000
        ),
        'module_orm_GetItemsByFilter_before' => array(
            'CallbackGetItemsByFilterBefore',
            1000
        ),
        'module_orm_GetByFilter_before'      => array(
            'CallbackGetItemsByFilterBefore',
            1000
        ),
    );

    /**
     * Модифицирует фильтр в ORM запросе
     *
     * @param $aParams
     */
    public function CallbackGetItemsByFilterAfter($aParams)
    {
        $aEntities = $aParams['aEntities'];
        $aFilter = $aParams['aFilter'];
        $this->Property_RewriteGetItemsByFilter($aEntities, $aFilter);
    }

    /**
     * Модифицирует результат ORM запроса
     *
     * @param $aParams
     */
    public function CallbackGetItemsByFilterBefore($aParams)
    {
        $aFilter = $this->Property_RewriteFilter($aParams['aFilter'], $aParams['sEntityFull']);
        $aParams['aFilter'] = $aFilter;
    }
}