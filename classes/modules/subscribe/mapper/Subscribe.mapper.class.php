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
 * Объект маппера для работы с БД
 *
 * @package modules.subscribe
 * @since 1.0
 */
class ModuleSubscribe_MapperSubscribe extends Mapper {
	/**
	 * Добавляет подписку в БД
	 *
	 * @param ModuleSubscribe_EntitySubscribe $oSubscribe	Объект подписки
	 * @return int|bool
	 */
	public function AddSubscribe($oSubscribe) {
		$sql = "INSERT INTO ".Config::Get('db.table.subscribe')." SET ?a ";
		if ($iId=$this->oDb->query($sql,$oSubscribe->_getData())) {
			return $iId;
		}
		return false;
	}
	/**
	 * Получение подписки по типы и емайлу
	 *
	 * @param string $sType	Тип
	 * @param string $sMail	Емайл
	 * @return ModuleSubscribe_EntitySubscribe|null
	 */
	public function GetSubscribeByTypeAndMail($sType,$sMail) {
		$sql = "SELECT * FROM ".Config::Get('db.table.subscribe')." WHERE target_type = ? and mail = ?";
		if ($aRow=$this->oDb->selectRow($sql,$sType,$sMail)) {
			return Engine::GetEntity('Subscribe',$aRow);
		}
		return null;
	}
	/**
	 * Обновление подписки
	 *
	 * @param ModuleSubscribe_EntitySubscribe $oSubscribe	Объект подписки
	 * @return int
	 */
	public function UpdateSubscribe($oSubscribe) {
		$sql = "UPDATE ".Config::Get('db.table.subscribe')." 
			SET 
			 	status = ?, 
			 	date_remove = ?
			WHERE id = ?d
		";
		return $this->oDb->query($sql,$oSubscribe->getStatus(),
								 $oSubscribe->getDateRemove(),
								 $oSubscribe->getId());
	}
	/**
	 * Возвращает список подписок по фильтру
	 *
	 * @param array $aFilter	Фильтр
	 * @param array $aOrder	Сортировка
	 * @param int $iCount	Возвращает общее количество элементов
	 * @param int $iCurrPage	Номер страницы
	 * @param int $iPerPage	Количество элементов на страницу
	 * @return array
	 */
	public function GetSubscribes($aFilter,$aOrder,&$iCount,$iCurrPage,$iPerPage) {
		$aOrderAllow=array('id','date_add','status');
		$sOrder='';
		foreach ($aOrder as $key=>$value) {
			if (!in_array($key,$aOrderAllow)) {
				unset($aOrder[$key]);
			} elseif (in_array($value,array('asc','desc'))) {
				$sOrder.=" {$key} {$value},";
			}
		}
		$sOrder=trim($sOrder,',');
		if ($sOrder=='') {
			$sOrder=' id desc ';
		}

		if (isset($aFilter['exclude_mail']) and !is_array($aFilter['exclude_mail'])) {
			$aFilter['exclude_mail']=array($aFilter['exclude_mail']);
		}

		$sql = "SELECT
					*
				FROM
					".Config::Get('db.table.subscribe')."
				WHERE
					1 = 1
					{ AND target_type = ? }
					{ AND target_id = ?d }
					{ AND mail = ? }
					{ AND mail not IN (?a) }
					{ AND `key` = ? }
					{ AND status = ?d }
				ORDER by {$sOrder}
				LIMIT ?d, ?d ;
					";
		$aResult=array();
		if ($aRows=$this->oDb->selectPage($iCount,$sql,
										  isset($aFilter['target_type']) ? $aFilter['target_type'] : DBSIMPLE_SKIP,
										  isset($aFilter['target_id']) ? $aFilter['target_id'] : DBSIMPLE_SKIP,
										  isset($aFilter['mail']) ? $aFilter['mail'] : DBSIMPLE_SKIP,
										  (isset($aFilter['exclude_mail']) and count($aFilter['exclude_mail']) ) ? $aFilter['exclude_mail'] : DBSIMPLE_SKIP,
										  isset($aFilter['key']) ? $aFilter['key'] : DBSIMPLE_SKIP,
										  isset($aFilter['status']) ? $aFilter['status'] : DBSIMPLE_SKIP,
										  ($iCurrPage-1)*$iPerPage, $iPerPage
		)) {
			foreach ($aRows as $aRow) {
				$aResult[]=Engine::GetEntity('Subscribe',$aRow);
			}
		}
		return $aResult;
	}
}
?>