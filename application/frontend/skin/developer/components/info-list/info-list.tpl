{**
 * Список с информацией
 *
 * @styles css/common.css
 *}

{* Название компонента *}
{$_sComponentName = 'info-list'}

{if $aInfoList}
	<div class="{$_sComponentName} {mod name=$_sComponentName mods=$sMods} {$smarty.local.sClasses}" {$smarty.local.sAttributes}>
		{* Заголовок *}
		{if $sTitle}
			<h2 class="{$_sComponentName}-title">{$sTitle}</h2>
		{/if}

		{* Список *}
		<ul class="info-list">
			{foreach $aInfoList as $aInfoListItem}
				<li class="info-list-item">
					<div class="info-list-item-label {if $iInfoListLabelWidth}width-{$iInfoListLabelWidth}{/if}">{$aInfoListItem['label']}</div>
					<strong class="info-list-item-content">{$aInfoListItem['content']}</strong>
				</li>
			{/foreach}
		</ul>
	</div>
{/if}