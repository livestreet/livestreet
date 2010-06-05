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
	 * Путь к шаблонам с учетом наличия соответствующего skin`a
	 *
	 * @var string
	 */
	protected $sTemplatePathPlugin=null;
	
	/**
	 * Конструктор
	 *
	 * @param Engine $oEngine
	 * @param string $sAction
	 */	
	public function __construct(Engine $oEngine, $sAction) {
		parent::__construct($oEngine, $sAction);
		$this->Viewer_Assign('sTemplatePathPlugin',rtrim($this->getTemplatePathPlugin(),'/'));
		$this->Viewer_Assign('sTemplateWebPathPlugin',Plugin::GetTemplateWebPath(get_class($this)));
	}
	
	/**
	 * Возвращает путь к шаблонам плагина
	 *
	 * @return string
	 */
	public function getTemplatePathPlugin() {	
		if(is_null($this->sTemplatePathPlugin)) {
			preg_match('/^Plugin([\w]+)_Action([\w]+)$/i',$this->GetActionClass(),$aMatches);
			/**
			 * Проверяем в списке шаблонов
			 */
			$aMatches[1]=strtolower($aMatches[1]);
			$aPaths=glob(Config::Get('path.root.server').'/plugins/'.$aMatches[1].'/templates/skin/*/actions/Action'.ucfirst($aMatches[2]),GLOB_ONLYDIR);			
			$sTemplateName=($aPaths and in_array(
				Config::Get('view.skin'),
				array_map(
					create_function(
						'$sPath',
						'preg_match("/skin\/([\w]+)\/actions/i",$sPath,$aMatches); return $aMatches[1];'
					),
					$aPaths
				)
			))
				? Config::Get('view.skin')
				: 'default';
			
			$sDir=Config::Get('path.root.server')."/plugins/{$aMatches[1]}/templates/skin/{$sTemplateName}/";
			$this->sTemplatePathPlugin = is_dir($sDir) ? $sDir : null;
		}
		
		return $this->sTemplatePathPlugin;
	}
	
	/**
	 * Установить значение пути к директории шаблонов плагина
	 *
	 * @param  string $sTemplatePath
	 * @return bool
	 */
	public function setTemplatePathPlugin($sTemplatePath) {
		if(!is_dir($sTemplatePath)) return false;
		$this->sTemplatePathPlugin = $sTemplatePath;
	}
	
	/**
	 * Устанавливает какой шаблон выводить
	 *
	 * @param string $sTemplate Путь до шаблона относительно каталога шаблонов экшена
	 */
	protected function SetTemplateAction($sTemplate) {
		if($sActionTemplate=preg_match('/^Plugin([\w]+)_Action([\w]+)$/i',$this->GetActionClass(),$aMatches)) {
		      $sTemplatePath = 'actions/Action'.ucfirst($aMatches[2]).'/'.$sTemplate.'.tpl';
		      $sActionTemplate = is_file($sPluginTemplatePath=$this->getTemplatePathPlugin().$sTemplatePath)
		      		? $sPluginTemplatePath
		      		: $sTemplatePath;
	    }
    	$this->sActionTemplate = $sActionTemplate;
	}
	
	/**
	 * Получить шаблон
	 * Если шаблон не определен то возвращаем дефолтный шаблон евента: action/{Action}.{event}.tpl
	 *
	 * @return unknown
	 */
	public function GetTemplate() {
		if (is_null($this->sActionTemplate)) {
		    $this->SetTemplateAction($this->sCurrentEvent);
		}

		return $this->sActionTemplate;
	}
}
?>