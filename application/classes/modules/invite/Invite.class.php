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
 * Модуль управления инвайтами
 *
 * @package application.modules.invite
 * @since 2.0
 */
class ModuleInvite extends ModuleORM
{
    /**
     * Тип реферального инвайта, когда пользователь приглашает по своему реферальному коду
     */
    const INVITE_TYPE_REFERRAL = 1;
    /**
     * Тип инвайта по сгенерированному коду, когда пользователь генерирует для приглашения отдельный код (доступен в закрытом режиме сайта)
     */
    const INVITE_TYPE_CODE = 2;

    /**
     * Генерирует новый код инвайта
     *
     * @param int $iUserId
     * @param string|null $sCode
     * @param int $iCountAllowUse
     * @param int|string|null $sDateExpired
     * @return bool|ModuleInvite_EntityCode
     */
    public function GenerateInvite($iUserId, $sCode = null, $iCountAllowUse = 1, $sDateExpired = null)
    {
        $iUserId = is_scalar($iUserId) ? (int)$iUserId : $iUserId->getId();
        $sDateExpired = is_int($sDateExpired) ? date('Y-m-d H:i:s', time() + $sDateExpired) : $sDateExpired;

        $oInviteCode = Engine::GetEntity('ModuleInvite_EntityCode');
        $oInviteCode->setUserId($iUserId);
        $oInviteCode->setCode(is_null($sCode) ? $this->GenerateRandomCode() : $sCode);
        $oInviteCode->setCountAllowUse($iCountAllowUse);
        $oInviteCode->setDateExpired($sDateExpired);
        $oInviteCode->setActive(1);
        if ($oInviteCode->Add()) {
            return $oInviteCode;
        }
        return false;
    }

    /**
     * Фиксирует факт использования кода инвайта
     *
     * @param string $sCode
     * @param int $iUserId
     * @return bool
     */
    public function UseCode($sCode, $iUserId)
    {
        $iUserId = is_scalar($iUserId) ? (int)$iUserId : $iUserId->getId();
        $iType = $this->GetInviteTypeByCode($sCode);

        $oUse = Engine::GetEntity('ModuleInvite_EntityUse');
        $oUse->setType($iType);
        $oUse->setToUserId($iUserId);

        if ($iType == self::INVITE_TYPE_CODE) {
            $oCode = $this->GetCodeByCode($sCode);
            $oCode->setCountUse($oCode->getCountUse() + 1);
            $oCode->Update();

            $oUse->setCodeId($oCode->getId());
            $oUse->setFromUserId($oCode->getUserId());
        } elseif ($iType == self::INVITE_TYPE_REFERRAL) {
            $oUser = $this->User_GetUserByReferralCode($sCode);
            $oUse->setFromUserId($oUser->getId());
        } else {
            return false;
        }
        return $oUse->Add();
    }

    /**
     * Проверяет корректность кода инвайта с учетом его типа
     *
     * @param string $sCode
     * @param int $iType Тип инвайта, смотри self::INVITE_TYPE_*
     * @return bool
     */
    public function CheckCode($sCode, $iType = self::INVITE_TYPE_CODE)
    {
        if ($iType == self::INVITE_TYPE_CODE) {
            if ($oCode = $this->GetCodeByCode($sCode)) {
                if ($oCode->getActive()
                    and $oCode->getCountUse() < $oCode->getCountAllowUse()
                    and (!$oCode->getDateExpired() or strtotime($oCode->getDateExpired()) < time())
                ) {
                    return true;
                }
            }
        } elseif ($iType == self::INVITE_TYPE_REFERRAL) {
            if ($oUser = $this->User_GetUserByReferralCode($sCode)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Возвращает тип инвайта по его коду
     *
     * @param string $sCode
     * @return bool|int
     */
    public function GetInviteTypeByCode($sCode)
    {
        /**
         * Приоритет отдаем сгенерированному коду
         */
        if ($this->CheckCode($sCode, self::INVITE_TYPE_CODE)) {
            return self::INVITE_TYPE_CODE;
        }
        if ($this->CheckCode($sCode, self::INVITE_TYPE_REFERRAL)) {
            return self::INVITE_TYPE_REFERRAL;
        }
        return false;
    }

    /**
     * Возвращает персональный реферальный код пользователя
     *
     * @param ModuleUser_EntityUser $oUser
     * @return string|null
     */
    public function GetReferralCode($oUser)
    {
        if (is_scalar($oUser)) {
            $oUser = $this->User_GetUserById($oUser);
        }
        if (is_object($oUser)) {
            return $oUser->getReferralCode();
        }
        return null;
    }

    /**
     * Возвращает полную ссылку с реферальным кодом
     *
     * @param ModuleUser_EntityUser $oUser
     * @param string|null $sCode
     * @return null|string
     */
    public function GetReferralLink($oUser, $sCode = null)
    {
        if ($sCode or $sCode = $this->GetReferralCode($oUser)) {
            return Router::GetPath('auth/referral') . urlencode($sCode) . '/';
        }
        return null;
    }

    /**
     * Генерирует случайный код
     *
     * @return string
     */
    protected function GenerateRandomCode()
    {
        return func_generator(10);
    }

    /**
     * Возвращает количество доступных инвайтов для пользователя в данный момент
     *
     * @param ModuleUser_EntityUser $oUser
     * @return int
     */
    public function GetCountInviteAvailable($oUser)
    {
        if (is_scalar($oUser)) {
            $oUser = $this->User_GetUserById($oUser);
        }
        /**
         * Период в днях, за который выдаем инвайты
         */
        $sDay = 7;
        /**
         * Количество выданных инвайтов за эти дни
         */
        $iCountUsed = $this->GetCountFromCodeByFilter(array(
            'user_id'       => $oUser->getId(),
            'date_create >' => date("Y-m-d 00:00:00", mktime(0, 0, 0, date("m"), date("d") - $sDay, date("Y")))
        ));
        /**
         * Доступное число инвайтов период = рейтингу пользователя
         */
        $iCountAllAvailable = round($oUser->getRating());
        $iCountAllAvailable = $iCountAllAvailable < 0 ? 0 : $iCountAllAvailable;
        $iCountAvailable = $iCountAllAvailable - $iCountUsed;
        $iCountAvailable = $iCountAvailable < 0 ? 0 : $iCountAvailable;
        return $iCountAvailable;
    }

    /**
     * Возвращает количество приглашенных пользователей (число использованных инвайтов)
     *
     * @param int $iUserId
     * @return int
     */
    public function GetCountInviteUsed($iUserId)
    {
        $iUserId = is_scalar($iUserId) ? (int)$iUserId : $iUserId->getId();

        return $this->GetCountFromUseByFilter(array('from_user_id' => $iUserId));
    }

    /**
     * Возвращает пользователя, который пригласил текущего
     *
     * @param $iUserId
     * @return ModuleUser_EntityUser|null
     */
    public function GetUserInviteFrom($iUserId)
    {
        if ($oUse = $this->GetUseByToUserId($iUserId) and $iUserFrom = $oUse->getFromUserId()) {
            return $this->User_GetUserById($iUserFrom);
        }
        return null;
    }

    /**
     * Возвращает список приглашенных пользователей
     *
     * @param int $iUserId
     * @return array
     */
    public function GetUsersInvite($iUserId)
    {
        if ($aUseItems = $this->GetUseItemsByFilter(array('from_user_id' => $iUserId, '#index-from' => 'to_user_id', '#limit' => 100))) {
            return $this->User_GetUsersAdditionalData(array_keys($aUseItems));
        }
        return array();
    }

    /**
     * Отправляет инвайт
     *
     * @param ModuleUser_EntityUser $oUserFrom Пароль пользователя, который отправляет инвайт
     * @param string $sMailTo Емайл на который отправляем инвайт
     * @param string $sRefCode Код приглашения
     */
    public function SendNotifyInvite(ModuleUser_EntityUser $oUserFrom, $sMailTo, $sRefCode)
    {
        $this->Notify_Send(
            $sMailTo,
            'invite.tpl',
            $this->Lang_Get('emails.invite.subject'),
            array(
                'sMailTo'   => $sMailTo,
                'oUserFrom' => $oUserFrom,
                'sRefCode'  => $sRefCode,
                'sRefLink'  => $this->GetReferralLink($oUserFrom, $sRefCode),
            )
        );
    }
}