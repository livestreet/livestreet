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
 * Экшен обработки УРЛа вида /property/
 *
 * @package application.actions
 * @since 2.0
 */
class ActionProperty extends Action
{
    /**
     * Текущий пользователь
     *
     * @var ModuleUser_EntityUser|null
     */
    protected $oUserCurrent = null;

    /**
     * Инициализация
     */
    public function Init()
    {
        /**
         * Достаём текущего пользователя
         */
        $this->oUserCurrent = $this->User_GetUserCurrent();
    }

    /**
     * Регистрируем евенты
     */
    protected function RegisterEvent()
    {
        $this->AddEventPreg('/^download$/i', '/^[\w]{10,32}$/i', '/^$/i', 'EventDownloadFile');
    }


    /**********************************************************************************
     ************************ РЕАЛИЗАЦИЯ ЭКШЕНА ***************************************
     **********************************************************************************
     */

    /**
     * Загрузка файла
     */
    protected function EventDownloadFile()
    {
        $sKey = $this->GetParam(0);
        /**
         * Выполняем проверки
         */
        if (!$oValue = $this->Property_GetValueByValueVarchar($sKey)) {
            return parent::EventNotFound();
        }
        if (!$oProperty = $oValue->getProperty()) {
            return parent::EventNotFound();
        }
        if ($oProperty->getType() != ModuleProperty::PROPERTY_TYPE_FILE) {
            return parent::EventNotFound();
        }
        if (!$oTargetRel = $this->Property_GetTargetByType($oValue->getTargetType())) {
            return parent::EventNotFound();
        }
        if ($oTargetRel->getState() != ModuleProperty::TARGET_STATE_ACTIVE) {
            return parent::EventNotFound();
        }

        $bAllowDownload = false;
        if (!$this->oUserCurrent) {
            if ($oProperty->getParam('access_only_auth')) {
                return Router::Action('error', '403');
            } else {
                $bAllowDownload = true;
            }
        }
        if (!$bAllowDownload) {
            /**
             * Проверяем доступ пользователя к объекту, которому принадлежит свойство
             */
            if ($this->Property_CheckAllowTargetObject($oValue->getTargetType(), $oValue->getTargetId(),
                array('user' => $this->oUserCurrent))
            ) {
                $bAllowDownload = true;
            }
        }
        if ($bAllowDownload) {
            /**
             * Увеличиваем количество загрузок
             */
            $aStats = $oValue->getDataOne('stats');
            $aStats['count_download'] = (isset($aStats['count_download']) ? $aStats['count_download'] : 0) + 1;
            $oValue->setDataOne('stats', $aStats);
            $oValue->Update();
            $oValueType = $oValue->getValueTypeObject();
            if (!$oValueType->DownloadFile()) {
                return parent::EventNotFound();
            }
        } else {
            return Router::Action('error', '403');
        }

        $this->SetTemplate(false);
    }
}