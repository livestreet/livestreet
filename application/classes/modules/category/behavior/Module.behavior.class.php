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
 * Поведение, которое необходимо добавлять к ORM модулю сущности у которой добавляются категории
 *
 * @package application.modules.category
 * @since 2.0
 */
class ModuleCategory_BehaviorModule extends Behavior
{
    /**
     * Дефолтные параметры
     *
     * @var array
     */
    protected $aParams = array(
        'target_type' => '',
    );
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
        $this->Category_RewriteGetItemsByFilter($aEntities, $aFilter, $this->getParam('target_type'));
    }

    /**
     * Модифицирует результат ORM запроса
     *
     * @param $aParams
     */
    public function CallbackGetItemsByFilterBefore($aParams)
    {
        $aFilter = $this->Category_RewriteFilter($aParams['aFilter'], $aParams['sEntityFull'],
            $this->getParam('target_type'));
        $aParams['aFilter'] = $aFilter;
    }

    /**
     * Возвращает дерево категорий
     *
     * @return mixed
     */
    public function GetCategoriesTree()
    {
        return $this->Category_GetCategoriesTreeByTargetType($this->getParam('target_type'));
    }

    /**
     * Возвращает список ID объектов (элементов), которые принадлежат категории
     *
     * @param      $oCategory
     * @param      $iPage
     * @param      $iPerPage
     * @param bool $bIncludeChild
     *
     * @return mixed
     */
    public function GetTargetIdsByCategory($oCategory, $iPage, $iPerPage, $bIncludeChild = false)
    {
        return $this->Category_GetTargetIdsByCategory($oCategory, $this->getParam('target_type'), $iPage, $iPerPage,
            $bIncludeChild);
    }
}