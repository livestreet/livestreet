{**
 * Навигация
 *}

{* Название компонента *}
{$_sComponentName = 'nav'}

{* Уникальное имя меню *}
{$_sName = ( $smarty.local.sName ) ? $smarty.local.sName : rand(0, 9999999)}

{* Получаем пункты установленные плагинами *}
{hook run="{$_sComponentName}_{$_sName}" assign='aItemsHook' aItems=$smarty.local.aItems array=true}

{$_aItems = ( $aItemsHook ) ? $aItemsHook : $smarty.local.aItems}

{* Считаем кол-во не активных пунктов *}
{$_iDisabledItemsCounter = 0}

{foreach $_aItems as $aItem}
	{$_iDisabledItemsCounter = $_iDisabledItemsCounter + ( ! $aItem['is_enabled']|default:true && $aItem['name'] != '-' )}
{/foreach}

{* Отображем меню только если есть активные пункты *}
{if count($_aItems) - $_iDisabledItemsCounter}
	<ul class="{$_sComponentName} {mod name=$_sComponentName mods=$smarty.local.sMods} {$smarty.local.sClasses}" {$smarty.local.sAttributes}>
		{foreach $_aItems as $aItem}
			{$_bIsEnabled = $aItem['is_enabled']}
			{$_bIsDropdown = isset($aItem['menu'])}

			{if $_bIsEnabled|default:true}
				{if $aItem['name'] != '-'}
					{* Пункт меню *}
					<li class="{$_sComponentName}-item
							   {if $smarty.local.sActiveItem && $smarty.local.sActiveItem == $aItem['name']}active{/if}
							   {if isset($aItem['count'])}{$_sComponentName}-item--has-counter{/if}
							   {if $_bIsDropdown}{$_sComponentName}-item--has-children{/if}
							   {$aItem['classes']}"
						{if isset($aItem['title'])}title="{$aItem['title']}"{/if}
						{$aItem['attributes']}
						{foreach $aItem['data'] as $data}
							data-{$data@key}={$data@value}
						{/foreach}>

						{* Ссылка *}
						<a href="{if $aItem['url']}{$aItem['url']}{else}#{/if}">
							{$aItem['text']}

							{* Счетчик *}
							{if isset($aItem['count']) && ( $smarty.local.bShowZeroCounter || ( ! $smarty.local.bShowZeroCounter && $aItem['count'] > 0 ) )}
								<span class="badge">{$aItem['count']}</span>
							{/if}
						</a>

						{* Подменю *}
						{if $_bIsDropdown}
							{include './nav.tpl'
									 sName          = $aItem['name']
									 sActiveItem    = $smarty.local.sActiveItem
									 sClasses       = "nav--stacked nav--dropdown {$aItem['classes']}"
									 sAttributes    = $aItem['attributes']
									 aItems         = $aItem['menu']}
						{/if}
					</li>
				{else}
					{* Разделитель *}
					<li class="{$_sComponentName}-separator"></li>
				{/if}
			{/if}
		{/foreach}
	</ul>
{/if}