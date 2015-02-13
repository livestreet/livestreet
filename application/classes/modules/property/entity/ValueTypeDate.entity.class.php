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
 * Объект управления типом date
 *
 * @package application.modules.property
 * @since 2.0
 */
class ModuleProperty_EntityValueTypeDate extends ModuleProperty_EntityValueType
{

    protected $sFormatDateInput = 'dd.MM.yyyy';
    protected $sFormatDateTimeInput = 'dd.MM.yyyy HH:mm';

    public function getValueForDisplay()
    {
        $oValue = $this->getValueObject();
        $oProperty = $oValue->getProperty();

        return $oValue->getValueDate() ? date($oProperty->getParam('format_out'),
            strtotime($oValue->getValueDate())) : '';
    }

    public function isEmpty()
    {
        return $this->getValueObject()->getValueDate() ? false : true;
    }

    public function getValueForForm()
    {
        $oValue = $this->getValueObject();
        $oProperty = $oValue->getProperty();

        $sDate = $oValue->getValueDate();
        $iTime = strtotime($sDate);
        // TODO: нужен конвертор формата дат вида Y в yyyy для учета $this->sFormatDateInput
        return $sDate ? date('d.m.Y', $iTime) . ($oProperty->getParam('use_time') ? date(' H:i', $iTime) : '') : '';
    }

    public function validate()
    {
        /**
         * Данные поступают ввиде массива array( 'date'=>'..', 'time' => array( 'h' => '..', 'm' => '..' ) )
         */
        $aValue = $this->getValueForValidate();
        $oValueObject = $this->getValueObject();
        $oProperty = $oValueObject->getProperty();
        $this->setValueForValidateDate(isset($aValue['date']) ? $aValue['date'] : '');
        /**
         * Формируем формат для валидации даты
         * В инпуте дата идет в формате d.m.Y и плюс H:i если используется время
         */
        $sFormatValidate = $oProperty->getParam('use_time') ? $this->sFormatDateTimeInput : $this->sFormatDateInput;

        $mRes = $this->validateStandart('date', array('format' => $sFormatValidate), 'value_for_validate_date');
        if ($mRes === true) {
            /**
             * Формируем полную дату
             */
            if ($this->getValueForValidateDate()) {
                $sTimeFull = strtotime($this->getValueForValidateDate());
                /**
                 * Проверка на ограничение даты
                 */
                if ($oProperty->getValidateRuleOne('disallowFuture')) {
                    if ($sTimeFull > time()) {
                        return "{$oProperty->getTitle()}: дата не может быть в будущем";
                    }
                }
                /**
                 * Проверка на ограничения только если это новая запись, либо старая с изменениями
                 */
                if ($oValueObject->_isNew() or strtotime($oValueObject->getValueDate()) != $sTimeFull) {
                    if ($oProperty->getValidateRuleOne('disallowPast')) {
                        if ($sTimeFull < time()) {
                            return "{$oProperty->getTitle()}: дата не может быть в прошлом";
                        }
                    }
                }
            } else {
                $sTimeFull = null;
            }
            /**
             * Переопределяем результирующее значение
             */
            $this->setValueForValidate($sTimeFull ? date('Y-m-d H:i:00', $sTimeFull) : null);
            return true;
        } else {
            return $mRes;
        }
    }

    public function setValue($mValue)
    {
        $this->resetAllValue();
        $oValue = $this->getValueObject();
        $oValue->setValueDate($mValue ? $mValue : null);
    }

    public function prepareValidateRulesRaw($aRulesRaw)
    {
        $aRules = array();
        $aRules['allowEmpty'] = isset($aRulesRaw['allowEmpty']) ? false : true;
        $aRules['disallowFuture'] = isset($aRulesRaw['disallowFuture']) ? true : false;
        $aRules['disallowPast'] = isset($aRulesRaw['disallowPast']) ? true : false;

        return $aRules;
    }

    public function prepareParamsRaw($aParamsRaw)
    {
        $aParams = array();
        $aParams['use_time'] = isset($aParamsRaw['use_time']) ? true : false;

        if (isset($aParamsRaw['format_out'])) {
            $aParams['format_out'] = $aParamsRaw['format_out'];
        }

        return $aParams;
    }

    public function getParamsDefault()
    {
        return array(
            'format_out' => 'Y-m-d H:i',
            'use_time'   => true,
        );
    }
}