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
 * Обрабатывает блок облака тегов городов юзеров
 *
 */
class BlockTagsCity extends Block {
	public function Exec() {
		
		$aStat=$this->User_GetStatUsers();
		
		$aCityList=$aStat['count_city'];				
		/**
		 * Расчитываем логарифмическое облако тегов
		 */
		if ($aCityList and count($aCityList)>0) {
			$iMinSize=1; // минимальный размер шрифта
			$iMaxSize=10; // максимальный размер шрифта
			$iSizeRange=$iMaxSize-$iMinSize;
			
			$iMin=10000;
			$iMax=0;
			foreach ($aCityList as $aCity) {
				if ($iMax<$aCity['count']) {
					$iMax=$aCity['count'];
				}
				if ($iMin>$aCity['count']) {
					$iMin=$aCity['count'];
				}
			}			
			
			$iMinCount=log($iMin+1);
			$iMaxCount=log($iMax+1);
			$iCountRange=$iMaxCount-$iMinCount;
			if ($iCountRange==0) {
				$iCountRange=1;
			}
			foreach ($aCityList as $key => $aCity) {
				$iTagSize=$iMinSize+(log($aCity['count']+1)-$iMinCount)*($iSizeRange/$iCountRange);
				$aCityList[$key]['size']=round($iTagSize);				
			}
			/**
		 	* Устанавливаем шаблон вывода
		 	*/
			$this->Viewer_Assign("aCityList",$aCityList);
		}
	}
}
?>