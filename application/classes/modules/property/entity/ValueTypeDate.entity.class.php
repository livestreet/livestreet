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
class ModuleProperty_EntityValueTypeDate extends ModuleProperty_EntityValueType {

	protected $sFormatDateInput='dd.MM.yyyy';

	public function getValueForDisplay() {
		$oValue=$this->getValueObject();
		$oProperty=$oValue->getProperty();

		return $oValue->getValueDate() ? date($oProperty->getParam('format_out'),strtotime($oValue->getValueDate())) : '';
	}

	public function getValueForForm() {
		$oValue=$this->getValueObject();

		$sDate=$oValue->getValueDate();
		// TODO: нужен конвертор формата дат вида Y в yyyy для учета $this->sFormatDateInput
		return $sDate ? date('d.m.Y',strtotime($sDate)) : '';
	}

	public function getValueTimeH() {
		$sDate=$this->getValueObject()->getValueDate();
		return $sDate ? date('H',strtotime($sDate)) : '';
	}

	public function getValueTimeM() {
		$sDate=$this->getValueObject()->getValueDate();
		return $sDate ? date('i',strtotime($sDate)) : '';
	}

	public function validate() {
		/**
		 * Данные поступают ввиде массива array( 'date'=>'..', 'time' => array( 'h' => '..', 'm' => '..' ) )
		 */
		$aValue=$this->getValueForValidate();
		$this->setValueForValidateDate(isset($aValue['date']) ? $aValue['date'] : '');
		/**
		 * Сначала проверяем корректность даты
		 * В инпуте дата идет в формате d.m.Y
		 */
		$mRes=$this->validateStandart('date',array('format'=>$this->sFormatDateInput),'value_for_validate_date');
		if ($mRes===true) {
			/**
			 * Теперь проверяем на требование указывать дату
			 */
			$iTimeH=0;
			$iTimeM=0;
			$oValueObject=$this->getValueObject();
			$oProperty=$oValueObject->getProperty();
			if ($oProperty->getParam('use_time')) {
				$iTimeH=isset($aValue['time']['h']) ? $aValue['time']['h'] : 0;
				$iTimeM=isset($aValue['time']['m']) ? $aValue['time']['m'] : 0;
				if ($iTimeH<0 or $iTimeH>23) {
					$iTimeH=0;
				}
				if ($iTimeM<0 or $iTimeM>59) {
					$iTimeM=0;
				}
			}
			/**
			 * Формируем полную дату
			 */
			if ($this->getValueForValidateDate()) {
				$sTimeFull=strtotime($this->getValueForValidateDate())+60*$iTimeM+60*60*$iTimeH;

				/**
				 * Проверка на ограничение даты
				 */
				if ($oProperty->getValidateRuleOne('disallowFuture')) {
					if ($sTimeFull>time()) {
						return "{$oProperty->getTitle()}: дата не может быть в будущем";
					}
				}
				/**
				 * Проверка на ограничения только если это новая запись, либо старая с изменениями
				 */
				if ($oValueObject->_isNew() or strtotime($oValueObject->getValueDate())!=$sTimeFull ) {
					if ($oProperty->getValidateRuleOne('disallowPast')) {
						if ($sTimeFull<time()) {
							return "{$oProperty->getTitle()}: дата не может быть в прошлом";
						}
					}
				}
			} else {
				$sTimeFull=null;
			}
			/**
			 * Переопределяем результирующее значение
			 */
			$this->setValueForValidate($sTimeFull ? date('Y-m-d H:i:00',$sTimeFull) : null);
			return true;
		} else {
			return $mRes;
		}
	}

	public function setValue($mValue) {
		$this->resetAllValue();
		$oValue=$this->getValueObject();
		$oValue->setValueDate($mValue ? $mValue : null);
	}

	public function prepareValidateRulesRaw($aRulesRaw) {
		$aRules=array();
		$aRules['allowEmpty']=isset($aRulesRaw['allowEmpty']) ? false : true;
		$aRules['disallowFuture']=isset($aRulesRaw['disallowFuture']) ? true : false;
		$aRules['disallowPast']=isset($aRulesRaw['disallowPast']) ? true : false;

		return $aRules;
	}

	public function prepareParamsRaw($aParamsRaw) {
		$aParams=array();
		$aParams['use_time']=isset($aParamsRaw['use_time']) ? true : false;

		if (isset($aParamsRaw['format_out'])) {
			$aParams['format_out']=$aParamsRaw['format_out'];
		}

		return $aParams;
	}

	public function getParamsDefault() {
		return array(
			'format_out'=>'Y-m-d H:i:s',
			'use_time'=>true,
		);
	}
}