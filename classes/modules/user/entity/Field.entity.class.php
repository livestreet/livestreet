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
 * Сущность пользовательского поля у пользователя
 *
 * @package modules.user
 * @since 1.0
 */
class ModuleUser_EntityField extends Entity {
	/**
	 * Возвращает ID поля
	 *
	 * @return int|null
	 */
	public function getId() {
		return $this->_getDataOne('id');
	}
	/**
	 * Возвращает имя поля(уникальное)
	 *
	 * @return string|null
	 */
	public function getName(){
		return $this->_getDataOne('name');
	}
	/**
	 * Возвращает тип поля
	 *
	 * @return string|null
	 */
	public function getType(){
		return $this->_getDataOne('type');
	}
	/**
	 * Возвращает заголовок/описание поля
	 *
	 * @return string|null
	 */
	public function getTitle(){
		return $this->_getDataOne('title');
	}
	/**
	 * Возвращает паттерн подстановки поля
	 *
	 * @return string|null
	 */
	public function getPattern(){
		return $this->_getDataOne('pattern');
	}
	/**
	 * Возвращает значение поля у пользователя
	 *
	 * @param bool $bEscapeValue	Экранировать значение
	 * @param bool $bTransformed	Применять паттерн или нет
	 * @return string
	 */
	public function getValue($bEscapeValue = false, $bTransformed = false){
		if (!isset($this->_aData['value']) || !$this->_aData['value']) return '';
		if ($bEscapeValue)  $this->_aData['value'] = htmlspecialchars($this->_aData['value']);

		if ($bTransformed) {
			if (!$this->_aData['pattern']) return $this->_aData['value'];
			$sReturn=str_replace('{*}', $this->_aData['value'], $this->_aData['pattern']);
			/**
			 * Грязный хак сайта в профиле (
			 * @todo Сделать валидацию полей в профиле
			 */
			if ($this->getName()=='www') {
				$sReturn=str_replace(array('http://http://','http://https://'),array('http://','https://'),$sReturn);
			}
			return $sReturn;
		} else {
			return (isset($this->_aData['value'])) ? $this->_aData['value'] : '';
		}
	}


	/**
	 * Устанавливает ID поля
	 *
	 * @param int $iId
	 */
	public function setId($iId) {
		$this->_aData['id']=$iId;
	}
	/**
	 * Устанавливает имя поля(уникальное)
	 *
	 * @param string $sName
	 */
	public function setName($sName) {
		$this->_aData['name']=$sName;
	}
	/**
	 * Устанавливает тип поля
	 *
	 * @param string $sName
	 */
	public function setType($sName) {
		$this->_aData['type']=$sName;
	}
	/**
	 * Устанавливает заголовок/описание поля
	 *
	 * @param string $sTitle
	 */
	public function setTitle($sTitle) {
		$this->_aData['title']=$sTitle;
	}
	/**
	 * Устанавливает паттерн подстановки поля
	 *
	 * @param string $sPattern
	 */
	public function setPattern($sPattern) {
		$this->_aData['pattern']=$sPattern;
	}
	/**
	 * Устанавливает значение поля у пользователя
	 *
	 * @param string $sValue
	 */
	public function setValue($sValue) {
		$this->_aData['value']=$sValue;
	}
}