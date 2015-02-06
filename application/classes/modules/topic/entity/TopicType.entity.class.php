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
 * Объект типа топика
 * TODO: при удалении типа топика необходимо удалять дополнительные поля
 *
 * @package application.modules.topic
 * @since 2.0
 */
class ModuleTopic_EntityTopicType extends Entity
{

    protected $aValidateRules = array(
        array('name, name_many', 'string', 'max' => 200, 'min' => 1, 'allowEmpty' => false),
        array('code', 'regexp', 'pattern' => "#^[a-z0-9_]{1,30}$#", 'allowEmpty' => false),
        array('code', 'code_unique'),
        array('params', 'check_params'),
        array('name', 'check_name'),
        array('name_many', 'check_name_many'),
    );

    public function ValidateCheckParams()
    {
        $aParamsResult = array();
        $aParams = $this->getParamsArray();

        $aParamsResult['allow_poll'] = (isset($aParams['allow_poll']) and $aParams['allow_poll']) ? true : false;
        $aParamsResult['allow_preview'] = (isset($aParams['allow_preview']) and $aParams['allow_preview']) ? true : false;
        $aParamsResult['allow_text'] = (isset($aParams['allow_text']) and $aParams['allow_text']) ? true : false;
        $aParamsResult['allow_tags'] = (isset($aParams['allow_tags']) and $aParams['allow_tags']) ? true : false;

        $this->setParams($aParamsResult);
        return true;
    }

    public function ValidateCheckName()
    {
        $this->setName(htmlspecialchars($this->getName()));
        return true;
    }

    public function ValidateCheckNameMany()
    {
        $this->setNameMany(htmlspecialchars($this->getNameMany()));
        return true;
    }

    public function ValidateCodeUnique()
    {
        if ($oType = $this->Topic_GetTopicTypeByCode($this->getCode())) {
            if ($oType->getId() != $this->getId()) {
                return 'Тип с таким кодом уже существует';
            }
        }
        return true;
    }

    /**
     * Возвращает список дополнительных параметров типа
     *
     * @return array|mixed
     */
    public function getParamsArray()
    {
        $aData = @unserialize($this->_getDataOne('params'));
        if (!$aData) {
            $aData = array();
        }
        return $aData;
    }

    /**
     * Устанавливает список дополнительных параметров типа
     *
     * @param array $aParams
     */
    public function setParams($aParams)
    {
        $this->_aData['params'] = @serialize($aParams);
    }

    /**
     * Возвращает конкретный параметр типа
     *
     * @param string $sName
     *
     * @return null
     */
    public function getParam($sName)
    {
        $aParams = $this->getParamsArray();
        return isset($aParams[$sName]) ? $aParams[$sName] : null;
    }

    public function getStateText()
    {
        if ($this->getState() == ModuleTopic::TOPIC_TYPE_STATE_ACTIVE) {
            return 'активен';
        }
        if ($this->getState() == ModuleTopic::TOPIC_TYPE_STATE_NOT_ACTIVE) {
            return 'не активен';
        }
        return 'неизвестный статус';
    }

    public function getUrlForAdd()
    {
        return Router::GetPath('content/add') . $this->getCode() . '/';
    }

    public function getPropertyTargetType()
    {
        return 'topic_' . $this->getCode();
    }
}