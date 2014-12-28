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
 * Модуль опросов
 *
 * @package application.modules.poll
 * @since 2.0
 */
class ModulePoll extends ModuleORM
{

    /**
     * Объект текущего пользователя
     *
     * @var ModuleUser_EntityUser|null
     */
    protected $oUserCurrent;

    protected $aTargetTypes = array(
        'topic' => array(),
    );

    /**
     * Инициализация
     *
     */
    public function Init()
    {
        parent::Init();
        $this->oUserCurrent = $this->User_GetUserCurrent();
    }

    /**
     * Возвращает список типов объектов
     *
     * @return array
     */
    public function GetTargetTypes()
    {
        return $this->aTargetTypes;
    }

    /**
     * Добавляет в разрешенные новый тип
     *
     * @param string $sTargetType Тип
     * @param array $aParams Параметры
     * @return bool
     */
    public function AddTargetType($sTargetType, $aParams = array())
    {
        if (!array_key_exists($sTargetType, $this->aTargetTypes)) {
            $this->aTargetTypes[$sTargetType] = $aParams;
            return true;
        }
        return false;
    }

    /**
     * Проверяет разрешен ли данный тип
     *
     * @param string $sTargetType Тип
     * @return bool
     */
    public function IsAllowTargetType($sTargetType)
    {
        return in_array($sTargetType, array_keys($this->aTargetTypes));
    }

    /**
     * Возвращает парметры нужного типа
     *
     * @param string $sTargetType
     *
     * @return mixed
     */
    public function GetTargetTypeParams($sTargetType)
    {
        if ($this->IsAllowTargetType($sTargetType)) {
            return $this->aTargetTypes[$sTargetType];
        }
    }

    /**
     * Проверка объекта target - владелец медиа
     *
     * @param string $sTargetType Тип
     * @param int $iTargetId ID владельца
     * @return bool
     */
    public function CheckTarget($sTargetType, $iTargetId)
    {
        if (!$this->IsAllowTargetType($sTargetType)) {
            return false;
        }
        $sMethod = 'CheckTarget' . func_camelize($sTargetType);
        if (method_exists($this, $sMethod)) {
            return $this->$sMethod($iTargetId);
        }
        return false;
    }

    /**
     * Заменяет временный идентификатор на необходимый ID объекта
     *
     * @param string $sTargetType
     * @param string $sTargetId
     * @param null|string $sTargetTmp Если не задан, то берется их куки "poll_target_tmp_{$sTargetType}"
     */
    public function ReplaceTargetTmpById($sTargetType, $sTargetId, $sTargetTmp = null)
    {
        $sCookieKey = 'poll_target_tmp_' . $sTargetType;
        if (is_null($sTargetTmp) and $this->Session_GetCookie($sCookieKey)) {
            $sTargetTmp = $this->Session_GetCookie($sCookieKey);
            $this->Session_DropCookie($sCookieKey);
        }
        if (is_string($sTargetTmp)) {
            $aPollItems = $this->Poll_GetPollItemsByTargetTmpAndTargetType($sTargetTmp, $sTargetType);
            foreach ($aPollItems as $oPoll) {
                $oPoll->setTargetTmp(null);
                $oPoll->setTargetId($sTargetId);
                $oPoll->Update();
            }
        }
    }

    /**
     * Возвращает список опросов для объекта
     *
     * @param string $sTargetType
     * @param string $sTargetId
     *
     * @return mixed
     */
    public function GetPollItemsByTarget($sTargetType, $sTargetId)
    {
        $aFilter = array(
            'target_type' => $sTargetType,
            'target_id'   => $sTargetId,
            '#with'       => array('answers')
        );
        if ($this->oUserCurrent) {
            $aFilter['#with']['vote_current'] = array(
                'user_id'        => $this->oUserCurrent->getId(),
                '#value-default' => false
            );
        } else {
            $aFilter['#with']['vote_current'] = array('#value-set' => false);
        }
        $aPollItems = $this->Poll_GetPollItemsByFilter($aFilter);
        return $aPollItems;
    }


    /**
     * Проверка владельца с типом "topic"
     * Название метода формируется автоматически
     *
     * @param int $iTargetId ID владельца
     * @return bool
     */
    public function CheckTargetTopic($iTargetId)
    {
        if ($oTopic = $this->Topic_GetTopicById($iTargetId)) {
            if (!$oTopicType = $this->Topic_GetTopicType($oTopic->getType()) or !$oTopicType->getParam('allow_poll')) {
                return false;
            }
            /**
             * Проверяем права на редактирование топика
             */
            if ($this->ACL_IsAllowEditTopic($oTopic, $this->oUserCurrent)) {
                return true;
            }
        }
        return false;
    }
}