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
 * Сущность жалобы о пользователе
 *
 * @package application.modules.user
 * @since 2.0
 */
class ModuleUser_EntityComplaint extends Entity
{
    /**
     * Определяем правила валидации
     *
     * @var array
     */
    protected $aValidateRules = array(
        array('target_user_id', 'target'),
        array('type', 'type'),
    );

    /**
     * Инициализация
     */
    public function Init()
    {
        parent::Init();
        $this->aValidateRules[] = array(
            'text',
            'string',
            'max' => Config::Get('module.user.complaint_text_max'),
            'min' => 1,
            'allowEmpty' => !Config::Get('module.user.complaint_text_required'),
            'label' => $this->Lang_Get('user_complaint_text_title')
        );
        if (Config::Get('module.user.complaint_captcha')) {
            $sCaptchaValidateType = func_camelize('captcha_' . Config::Get('sys.captcha.type'));
            $this->aValidateRules[] = array('captcha', $sCaptchaValidateType, 'name' => 'complaint_user');
        }
    }

    /**
     * Валидация пользователя
     *
     * @param string $sValue Значение
     * @param array $aParams Параметры
     * @return bool
     */
    public function ValidateTarget($sValue, $aParams)
    {
        if ($oUserTarget = $this->User_GetUserById($sValue) and $this->getUserId() != $oUserTarget->getId()) {
            return true;
        }
        return $this->Lang_Get('report.notices.target_error');
    }

    /**
     * Валидация типа жалобы
     *
     * @param string $sValue Значение
     * @param array $aParams Параметры
     * @return bool
     */
    public function ValidateType($sValue, $aParams)
    {
        $aTypes = (array)Config::Get('module.user.complaint_type');
        if (in_array($sValue, $aTypes)) {
            return true;
        }
        return $this->Lang_Get('report.notices.type_error');
    }


    public function getUser()
    {
        if (!$this->_getDataOne('user')) {
            $this->_aData['user'] = $this->User_GetUserById($this->getUserId());
        }
        return $this->_getDataOne('user');
    }

    public function getTargetUser()
    {
        if (!$this->_getDataOne('target_user')) {
            $this->_aData['target_user'] = $this->User_GetUserById($this->getTargetUserId());
        }
        return $this->_getDataOne('target_user');
    }

    public function getTypeTitle()
    {
        return $this->Lang_Get('user.report.types.' . $this->getType());
    }
}