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
 * Абстрактный класс экшена плагина.
 * От этого класса необходимо наследовать экшены плагина, эот позволит корректно определять текущий шаблон плагина для рендеринга экшена
 *
 * @package engine
 * @since 1.0
 */
abstract class ActionPlugin extends Action {
	/**
	 * Полный серверный путь до текущего шаблона плагина
	 *
	 * @var string|null
	 */
	protected $sTemplatePathPlugin=null;

	/**
	 * Возвращает путь к текущему шаблону плагина
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
			$aPaths=glob(Config::Get('path.application.plugins.server').'/'.$aMatches[1].'/templates/skin/*/actions/Action'.ucfirst($aMatches[2]),GLOB_ONLYDIR);
			$sTemplateName=($aPaths and in_array(
				Config::Get('view.skin'),
				array_map(
					create_function(
						'$sPath',
						'preg_match("/skin\/([\w\-]+)\/actions/i",$sPath,$aMatches); return $aMatches[1];'
					),
					$aPaths
				)
			))
				? Config::Get('view.skin')
				: 'default';

			$sDir=Config::Get('path.application.plugins.server')."/{$aMatches[1]}/templates/skin/{$sTemplateName}/";
			$this->sTemplatePathPlugin = is_dir($sDir) ? $sDir : null;
		}

		return $this->sTemplatePathPlugin;
	}

	/**
	 * Установить значение пути к директории шаблона плагина
	 *
	 * @param  string $sTemplatePath	Полный серверный путь до каталога с шаблоном
	 * @return bool
	 */
	public function setTemplatePathPlugin($sTemplatePath) {
		if(!is_dir($sTemplatePath)) return false;
		$this->sTemplatePathPlugin = $sTemplatePath;
	}

}
?>