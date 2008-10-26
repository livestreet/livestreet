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
class ActionBlogs extends Action {	
	
	public function Init() {			
	}
	
	protected function RegisterEvent() {	
		$this->AddEventPreg('/^(page(\d+))?$/i','EventShowBlogs');								
	}
		
	
	/**********************************************************************************
	 ************************ РЕАЛИЗАЦИЯ ЭКШЕНА ***************************************
	 **********************************************************************************
	 */	
	
	
	protected function EventShowBlogs() {		
		/**
		 * Передан ли номер страницы
		 */			
		$iPage=	preg_match("/^\d+$/i",$this->GetEventMatch(2)) ? $this->GetEventMatch(2) : 1;
		/**
		 * Получаем список блогов
		 */
		$aResult=$this->Blog_GetBlogsRating($iPage,BLOG_BLOGS_PER_PAGE);	
		$aBlogs=$aResult['collection'];				
		/**
		 * Формируем постраничность
		 */		
		$aPaging=$this->Viewer_MakePaging($aResult['count'],$iPage,BLOG_BLOGS_PER_PAGE,4,DIR_WEB_ROOT.'/blogs');	
		/**
		 * Загружаем переменные в шаблон
		 */					
		$this->Viewer_Assign('aPaging',$aPaging);					
		$this->Viewer_Assign("aBlogs",$aBlogs);
		$this->Viewer_AddHtmlTitle('Список блогов');
		/**
		 * Устанавливаем шаблон вывода
		 */
		$this->SetTemplateAction('index');				
	}
}
?>