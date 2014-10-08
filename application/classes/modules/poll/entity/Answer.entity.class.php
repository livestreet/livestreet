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
 * Сущность ответа в опросе
 *
 * @package application.modules.poll
 * @since 2.0
 */
class ModulePoll_EntityAnswer extends EntityORM
{

    protected $aValidateRules = array(
        array('title', 'string', 'allowEmpty' => false, 'min' => 1, 'max' => 250),
        array('title', 'check_title'),
    );

    protected $aRelations = array(
        'poll' => array(self::RELATION_TYPE_BELONGS_TO, 'ModulePoll_EntityPoll', 'poll_id'),
    );

    protected function beforeSave()
    {
        if ($bResult = parent::beforeSave()) {
            if ($this->_isNew()) {
                $this->setDateCreate(date("Y-m-d H:i:s"));
            }
        }
        return $bResult;
    }

    public function ValidateCheckTitle()
    {
        $this->setTitle(htmlspecialchars($this->getTitle()));
        return true;
    }

}