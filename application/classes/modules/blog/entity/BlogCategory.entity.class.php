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
 * Сущность категории блога
 *
 * @package modules.blog
 * @since 1.1
 */
class ModuleBlog_EntityBlogCategory extends Entity {
	/**
	 * Определяем правила валидации
	 *
	 * @var array
	 */
	protected $aValidateRules=array(
		array('url','regexp','pattern'=>'/^[\w\-_]+$/i','allowEmpty'=>false),
		array('title','string','max'=>100,'min'=>1,'allowEmpty'=>false),
		array('sort','number','integerOnly'=>true),
		array('pid','parent_category'),
		array('sort','sort_check'),
	);
	/**
	 * Проверка родительской категории
	 *
	 * @param string $sValue	Валидируемое значение
	 * @param array $aParams	Параметры
	 * @return bool
	 */
	public function ValidateParentCategory($sValue,$aParams) {
		if ($this->getPid()) {
			if ($oCategory=$this->Blog_GetCategoryById($this->getPid())) {
				if ($oCategory->getId()==$this->getId()) {
					return 'Попытка вложить категорию в саму себя';
				}
				$this->setUrlFull($oCategory->getUrlFull().'/'.$this->getUrl());
			} else {
				return 'Неверная категория';
			}
		} else {
			$this->setPid(null);
			$this->setUrlFull($this->getUrl());
		}
		return true;
	}
	/**
	 * Установка дефолтной сортировки
	 *
	 * @param string $sValue	Валидируемое значение
	 * @param array $aParams	Параметры
	 * @return bool
	 */
	public function ValidateSortCheck($sValue,$aParams) {
		if (!$this->getSort()) {
			$this->setSort($this->Blog_GetCategoryMaxSortByPid($this->getPid())+1);
		}
		return true;
	}
	/**
	 * Возвращает полный URL категории
	 *
	 * @return string
	 */
	public function getUrlWeb() {
		return Router::GetPath('blogs').$this->getUrlFull().'/';
	}
}
?>