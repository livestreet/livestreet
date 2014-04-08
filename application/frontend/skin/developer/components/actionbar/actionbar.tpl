{**
 * Экшнбар
 *
 * @param array  $aItems  Массив с кнопками
 *
 * @styles css/common.css
 *}

{* Название компонента *}
{$_sComponentName = 'actionbar'}

{if $smarty.local.aItems}
	<ul class="{$_sComponentName} clearfix {mod name=$_sComponentName mods=$smarty.local.sMods} {$smarty.local.sClasses}" {$smarty.local.sAttributes}>
		{foreach $smarty.local.aItems as $aItem}
			{if $aItem['html']}
				{$aItem['html']}
			{else}
				{if $aItem['show']|default:true}
					{include './actionbar.item.tpl'}
				{/if}
			{/if}
		{/foreach}
	</ul>
{/if}