{**
 * Подгрузка контента
 *
 * @param string  $sText
 * @param string  $sTarget
 * @param integer $iCount
 * @param integer $iTargetId
 * @param integer $iLastId
 * @param boolean $bAppend
 *
 * @styles assets/css/components/more.css
 * @scripts <common>/js/more.js
 *}

{* Название компонента *}
{$_sComponentName = 'more'}

<div class="{$_sComponentName} {mod name=$_sComponentName mods=$smarty.local.sMods} {$smarty.local.sClasses}"
	 data-more-append="{$smarty.local.bAppend|default:true}"
	 {if $smarty.local.sTarget}data-more-target="{$smarty.local.sTarget}"{/if}
	 {$smarty.local.sAttributes}>

	{* Текст *}
	{$smarty.local.sText|default:'Подгрузить еще'}

	{* Счетчик *}
	{if $smarty.local.iCount}
		(<span class="js-more-count">{$smarty.local.iCount}</span>)
	{/if}
</div>