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
 * Сущность голосования в опросе
 *
 * @package application.modules.poll
 * @since 2.0
 */
class ModulePoll_EntityVote extends EntityORM
{

    protected $aValidateRules = array();

    protected $aRelations = array(
        'poll' => array(self::RELATION_TYPE_BELONGS_TO, 'ModulePoll_EntityPoll', 'poll_id'),
    );

    protected function beforeSave()
    {
        if ($bResult = parent::beforeSave()) {
            if ($this->_isNew()) {
                $this->setDateCreate(date("Y-m-d H:i:s"));
                $this->setIp(func_getIp());
            }
        }
        return $bResult;
    }

    protected function afterSave()
    {
        parent::afterSave();
        if ($this->_isNew()) {
            /**
             * Отмечаем факт голосования в опросе и вариантах
             */
            $oPoll = $this->getPoll();
            $aAnswerItems = $this->getAnswersObject();
            if ($aAnswerItems) {
                foreach ($aAnswerItems as $oAnswer) {
                    $oAnswer->setCountVote($oAnswer->getCountVote() + 1);
                    $oAnswer->Update();
                }
                $oPoll->setCountVote($oPoll->getCountVote() + 1);
            } else {
                $oPoll->setCountAbstain($oPoll->getCountAbstain() + 1);
            }
            $oPoll->Update(0);
        }
    }

    /**
     * Возвращает список вариантов, за которые голосовали
     *
     * @return array|mixed
     */
    public function getAnswers()
    {
        $aData = @unserialize($this->_getDataOne('answers'));
        if (!$aData) {
            $aData = array();
        }
        return $aData;
    }

    /**
     * Устанавливает список вариантов, за которые голосовали
     *
     * @param $aParams
     */
    public function setAnswers($aParams)
    {
        $this->_aData['answers'] = @serialize($aParams);
    }
}