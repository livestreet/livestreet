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

class ModuleWall_MapperWall extends Mapper {
	
	public function AddWall($oWall) {
		$sql = "INSERT INTO ".Config::Get('db.table.wall')." SET ?a ";
		if ($iId=$this->oDb->query($sql,$oWall->_getData())) {
			return $iId;
		}		
		return false;
	}
	
			
	public function UpdateWall($oWall) {
		$sql = "UPDATE ".Config::Get('db.table.wall')."
			SET 
			 	count_reply = ?d,
			 	last_reply = ?
			WHERE id = ?d
		";			
		return $this->oDb->query($sql,$oWall->getCountReply(),
								 $oWall->getLastReply(),
								 $oWall->getId());
	}

	public function DeleteWallById($iId) {
		$sql = "DELETE FROM ".Config::Get('db.table.wall')." WHERE id = ?d ";
		return $this->oDb->query($sql,$iId);
	}

	public function DeleteWallsByPid($iPid) {
		$sql = "DELETE FROM ".Config::Get('db.table.wall')." WHERE pid = ?d ";
		return $this->oDb->query($sql,$iPid);
	}

	public function GetWall($aFilter,$aOrder,&$iCount,$iCurrPage,$iPerPage) {
		$aOrderAllow=array('id','date_add');
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


		$sql = "SELECT
					id
				FROM
					".Config::Get('db.table.wall')."
				WHERE
					1 = 1
					{ AND pid = ?d }
					{ AND pid IS NULL AND 1 = ?d }
					{ AND wall_user_id = ?d }
					{ AND user_id = ?d }
					{ AND ip = ? }
					{ AND id = ?d }
					{ AND id < ?d }
					{ AND id > ?d }
				ORDER by {$sOrder}
				LIMIT ?d, ?d ;
					";
		$aResult=array();
		if ($aRows=$this->oDb->selectPage($iCount,$sql,
										  (isset($aFilter['pid']) and !is_null($aFilter['pid'])) ? $aFilter['pid'] : DBSIMPLE_SKIP,
										  (array_key_exists('pid',$aFilter) and is_null($aFilter['pid'])) ? 1 : DBSIMPLE_SKIP,
										  isset($aFilter['wall_user_id']) ? $aFilter['wall_user_id'] : DBSIMPLE_SKIP,
										  isset($aFilter['user_id']) ? $aFilter['user_id'] : DBSIMPLE_SKIP,
										  isset($aFilter['ip']) ? $aFilter['ip'] : DBSIMPLE_SKIP,
										  isset($aFilter['id']) ? $aFilter['id'] : DBSIMPLE_SKIP,
										  isset($aFilter['id_less']) ? $aFilter['id_less'] : DBSIMPLE_SKIP,
										  isset($aFilter['id_more']) ? $aFilter['id_more'] : DBSIMPLE_SKIP,
										  ($iCurrPage-1)*$iPerPage, $iPerPage
		)) {
			foreach ($aRows as $aRow) {
				$aResult[]=$aRow['id'];
			}
		}
		return $aResult;
	}


	public function GetCountWall($aFilter) {
		$sql = "SELECT
					count(*) as c
				FROM
					".Config::Get('db.table.wall')."
				WHERE
					1 = 1
					{ AND pid = ?d }
					{ AND pid IS NULL AND 1 = ?d }
					{ AND wall_user_id = ?d }
					{ AND ip = ? }
					{ AND id = ?d }
					{ AND id < ?d }
					{ AND id > ?d };
					";
		if ($aRow=$this->oDb->selectRow($sql,
										  (isset($aFilter['pid']) and !is_null($aFilter['pid'])) ? $aFilter['pid'] : DBSIMPLE_SKIP,
										  (array_key_exists('pid',$aFilter) and is_null($aFilter['pid'])) ? 1 : DBSIMPLE_SKIP,
										  isset($aFilter['wall_user_id']) ? $aFilter['wall_user_id'] : DBSIMPLE_SKIP,
										  isset($aFilter['ip']) ? $aFilter['ip'] : DBSIMPLE_SKIP,
										  isset($aFilter['id']) ? $aFilter['id'] : DBSIMPLE_SKIP,
										  isset($aFilter['id_less']) ? $aFilter['id_less'] : DBSIMPLE_SKIP,
										  isset($aFilter['id_more']) ? $aFilter['id_more'] : DBSIMPLE_SKIP
		)) {
			return $aRow['c'];
		}
		return 0;
	}

	public function GetWallsByArrayId($aArrayId) {
		if (!is_array($aArrayId) or count($aArrayId)==0) {
			return array();
		}

		$sql = "SELECT
					*
				FROM
					".Config::Get('db.table.wall')."
				WHERE
					id IN(?a)
				ORDER BY FIELD(id,?a) ";
		$aResult=array();
		if ($aRows=$this->oDb->select($sql,$aArrayId,$aArrayId)) {
			foreach ($aRows as $aRow) {
				$aResult[]=Engine::GetEntity('Wall',$aRow);
			}
		}
		return $aResult;
	}
}
?>