{**
 * Список с информацией
 *
 * @styles css/common.css
 *}

{if $aInfoList}
	<ul class="info-list">
		{foreach $aInfoList as $aInfoListItem}
			<li class="info-list-item">
				<div class="info-list-item-label {if $iInfoListLabelWidth}width-{$iInfoListLabelWidth}{/if}">{$aInfoListItem['label']}</div>
				<strong class="info-list-item-content">{$aInfoListItem['content']}</strong>
			</li>
		{/foreach}
	</ul>
{/if}