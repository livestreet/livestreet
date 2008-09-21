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
 * Обработка статических страниц, здесь пока пусто :)
 *
 */
class ActionPage extends Action {
	protected $sUserLogin=null;
	
	public function Init() {		
		$this->SetDefaultEvent('about');
	}
	
	protected function RegisterEvent() {		
		$this->AddEvent('about','EventAbout');	
		$this->AddEvent('download','EventDownload');					
	}
		
	
	/**********************************************************************************
	 ************************ РЕАЛИЗАЦИЯ ЭКШЕНА ***************************************
	 **********************************************************************************
	 */
	
	/**
	 * Просто выводим шаблон
	 *
	 */
	protected function EventAbout() {
		$this->Viewer_AddHtmlTitle('О проекте');
	}
	
	/**
	 * Просто выводим шаблон
	 *
	 */
	protected function EventDownload() {
		$this->Viewer_AddHtmlTitle('Скачать движок');
	}		
}
?>