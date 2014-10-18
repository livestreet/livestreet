{**
 * Список с информацией
 *
 * @styles css/common.css
 *}

{* Название компонента *}
{$component = 'info-list'}

{if $aInfoList}
	<div class="{$component} {mod name=$component mods=$mods} {$smarty.local.classes}" {$smarty.local.attributes}>
		{* Заголовок *}
		{if $sTitle}
			<h2 class="{$component}-title">{$sTitle}</h2>
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