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

require_once('Action.class.php');
/**
 * Абстрактный класс экшена
 *
 */
abstract class ActionPlugin extends Action {
	/**
	 * @var string
	 */
	const SKIN_NAME_KEY = '[skin_name]';
	
	/**
	 * Устанавливает какой шаблон выводить
	 *
	 * @param string $sTemplate Путь до шаблона относительно общего каталога шаблонов
	 */
	protected function SetTemplate($sTemplate) {
		$this->sActionTemplate=$sTemplate;
		if(preg_match('/^Plugin([\w]+)_Action([\w]+)$/i',$this->GetActionClass(),$aMatches)) {
			$this->sActionTemplate=$this->Viewer_ReplacePluginSkinName($this->sActionTemplate,$aMatches[1]);
		}
	}
	
	/**
	 * Устанавливает какой шаблон выводить
	 *
	 * @param string $sTemplate Путь до шаблона относительно каталога шаблонов экшена
	 */
	protected function SetTemplateAction($sTemplate) {
		$this->sActionTemplate==preg_match('/^Plugin([\w]+)_Action([\w]+)$/i',$this->GetActionClass(),$aMatches)
			? strtolower($aMatches[1]).'/templates/skin/'.self::SKIN_NAME_KEY.'/actions/Action'.ucfirst($aMatches[2]).'/'.$sTemplate.'.tpl'
			: null;

		if(preg_match('/^Plugin([\w]+)_Action([\w]+)$/i',$this->GetActionClass(),$aMatches)) {
			$this->sActionTemplate=$this->Viewer_ReplacePluginSkinName($this->sActionTemplate,$aMatches[1]);
		}
	}
	
	/**
	 * Получить шаблон
	 * Если шаблон не определен то возвращаем дефолтный шаблон евента: action/{Action}.{event}.tpl
	 *
	 * @return unknown
	 */
	public function GetTemplate() {
		if (is_null($this->sActionTemplate)) {
			$this->sActionTemplate=preg_match('/^Plugin([\w]+)_Action([\w]+)$/i',$this->GetActionClass(),$aMatches)
				? strtolower($aMatches[1]).'/templates/skin/'.self::SKIN_NAME_KEY.'/actions/Action'.ucfirst($aMatches[2]).'/'.$this->sCurrentEvent.'.tpl'
				: null;
		}
		
		if(preg_match('/^Plugin([\w]+)_Action([\w]+)$/i',$this->GetActionClass(),$aMatches)) {
			$this->sActionTemplate=$this->Viewer_ReplacePluginSkinName($this->sActionTemplate,$aMatches[1]);
		}
		return $this->sActionTemplate;
	}
}
?>