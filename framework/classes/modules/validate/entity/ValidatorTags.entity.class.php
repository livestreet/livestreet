<?php
/*-------------------------------------------------------
*
*   LiveStreet Engine Social Networking
*   Copyright © 2008 Mzhelskiy Maxim
*
*--------------------------------------------------------
*
*   Official site: www.livestreet.ru
*   Contact e-mail: rus.engine@gmail.com
*
*   GNU General Public License, version 2:
*   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*
---------------------------------------------------------
*/

/**
 * Валидатор тегов - строка с перечислением тегов
 *
 * @package engine.modules.validate
 * @since 1.0
 */
class ModuleValidate_EntityValidatorTags extends ModuleValidate_EntityValidator {
	/**
	 * Максимальня длина тега
	 *
	 * @var int
	 */
	public $max=50;
	/**
	 * Минимальня длина тега
	 *
	 * @var int
	 */
	public $min=2;
	/**
	 * Минимальное количество тегов
	 *
	 * @var int
	 */
	public $count=15;
	/**
	 * Разделитель тегов
	 *
	 * @var string
	 */
	public $sep=',';
	/**
	 * Допускать или нет пустое значение
	 *
	 * @var bool
	 */
	public $allowEmpty=false;

	/**
	 * Запуск валидации
	 *
	 * @param mixed $sValue	Данные для валидации
	 *
	 * @return bool|string
	 */
	public function validate($sValue) {
		if (is_array($sValue)) {
			return $this->getMessage($this->Lang_Get('validate_tags_empty',null,false),'msg',array('min'=>$this->min,'max'=>$this->max));
		}
		if($this->allowEmpty && $this->isEmpty($sValue)) {
			return true;
		}

		$aTags=explode($this->sep,trim($sValue,"\r\n\t\0\x0B ."));
		$aTagsNew=array();
		$aTagsNewLow=array();
		foreach ($aTags as $sTag) {
			$sTag=trim($sTag,"\r\n\t\0\x0B .");
			$iLength=mb_strlen($sTag, 'UTF-8');
			if ($iLength>=$this->min and $iLength<=$this->max and !in_array(mb_strtolower($sTag,'UTF-8'),$aTagsNewLow)) {
				$aTagsNew[]=$sTag;
				$aTagsNewLow[]=mb_strtolower($sTag,'UTF-8');
			}
		}
		$iCount=count($aTagsNew);
		if ($iCount>$this->count) {
			return $this->getMessage($this->Lang_Get('validate_tags_count_more',null,false),'msg',array('count'=>$this->count));
		} elseif (!$iCount) {
			return $this->getMessage($this->Lang_Get('validate_tags_empty',null,false),'msg',array('min'=>$this->min,'max'=>$this->max));
		}
		/**
		 * Если проверка от сущности, то возвращаем обновленное значение
		 */
		if ($this->oEntityCurrent) {
			$this->setValueOfCurrentEntity($this->sFieldCurrent,join($this->sep,$aTagsNew));
		}
		return true;
	}
}
?>