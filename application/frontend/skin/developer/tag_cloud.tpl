{**
 * Облако тегов
 *
 * @param array  $aTags       Массив с тегами
 * @param string $sTagsUrl    Код для получения ссылки тега
 * @param string $sTagsText   Код для получения названия тега
 * @param string $sTagsActive Текст активного комментария
 *
 * @styles css/common.css
 *}

{if $aTags}
	<ul class="tag-cloud word-wrap">
		{foreach $aTags as $oTag}
			<li class="tag-cloud-item {if $sTagsActive == $oTag->getText()}active{/if}">
				<a class="tag-size-{$oTag->getSize()}" href="{eval var=$sTagsUrl}" title="{$oTag->getCount()}">
					{if $sTagsText}
						{eval var=$sTagsText}
					{else}
						{$oTag->getText()|escape}
					{/if}
				</a>
			</li>
		{/foreach}
	</ul>
{else}
	{include file='alert.tpl' mAlerts=$aLang.block_tags_empty sAlertStyle='empty'}
{/if}