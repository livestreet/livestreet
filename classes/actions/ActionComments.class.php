<?
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
 * Класс обработки УРЛа вида /comments/
 *
 */
class ActionComments extends Action {	
	
	public function Init() {			
	}
	
	protected function RegisterEvent() {									
	}
		
	
	/**********************************************************************************
	 ************************ РЕАЛИЗАЦИЯ ЭКШЕНА ***************************************
	 **********************************************************************************
	 */	
	
	/**
	 * Для любого евента(тобишь любого УРЛа после /comments/) выводим комментарии
	 *
	 */
	protected function EventNotFound() {	
		/**
		 * Передан ли номер страницы
		 */
		if (preg_match("/^page(\d+)$/i",$this->sCurrentEvent,$aMatch)) {			
			$iPage=$aMatch[1];
		} else {
			$iPage=1;
		}		
		/**
		 * Получаем список комментов
		 */
		$iCount=0;			
		$aResult=$this->Comment_GetCommentsAll($iCount,$iPage,BLOG_COMMENT_PER_PAGE);		
		$aComments=$aResult['collection'];	
		/**
		 * Формируем постраничность
		 */		
		$aPaging=$this->Viewer_MakePaging($aResult['count'],$iPage,BLOG_COMMENT_PER_PAGE,4,DIR_WEB_ROOT.'/comments');	
		/**
		 * Загружаем переменные в шаблон
		 */					
		$this->Viewer_Assign('aPaging',$aPaging);					
		$this->Viewer_Assign("aComments",$aComments);
		$this->Viewer_AddHtmlTitle($this->Lang_Get('comments'));
		/**
		 * Устанавливаем шаблон вывода
		 */
		$this->SetTemplateAction('index');				
	}
}
?>