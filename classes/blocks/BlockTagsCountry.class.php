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
 * Обрабатывает блок облака тегов стран юзеров
 *
 */
class BlockTagsCountry extends Block {
	public function Exec() {
		
		$aStat=$this->User_GetStatUsers();
		
		$aCountryList=$aStat['count_country'];				
		/**
		 * Расчитываем логарифмическое облако тегов
		 */
		if ($aCountryList and count($aCountryList)>0) {
			$iMinSize=1; // минимальный размер шрифта
			$iMaxSize=10; // максимальный размер шрифта
			$iSizeRange=$iMaxSize-$iMinSize;
			
			$iMin=10000;
			$iMax=0;
			foreach ($aCountryList as $aCountry) {
				if ($iMax<$aCountry['count']) {
					$iMax=$aCountry['count'];
				}
				if ($iMin>$aCountry['count']) {
					$iMin=$aCountry['count'];
				}
			}			
			
			$iMinCount=log($iMin+1);
			$iMaxCount=log($iMax+1);
			$iCountRange=$iMaxCount-$iMinCount;
			if ($iCountRange==0) {
				$iCountRange=1;
			}
			foreach ($aCountryList as $key => $aCountry) {
				$iTagSize=$iMinSize+(log($aCountry['count']+1)-$iMinCount)*($iSizeRange/$iCountRange);
				$aCountryList[$key]['size']=round($iTagSize);				
			}
			/**
		 	* Устанавливаем шаблон вывода
		 	*/
			$this->Viewer_Assign("aCountryList",$aCountryList);
		}
	}
}
?>