{**
 * Список тегов
 *
 * @styles css/common.css
 *
 * TODO: Сделать универсальным
 *}

<ul class="tag-list tag-list-topic js-tags-topic-{$oTopic->getId()}" data-type="{$sTagsFavouriteType}" data-id="{$iTagsFavouriteId}">
	<li class="tag-list-item tag-list-item-label">{$aLang.topic_tags}:</li>

	{strip}
		{foreach $aTags as $sTag}
			<li class="tag-list-item tag-list-item-tag">
				{if ! $sTag@first}, {/if}<a rel="tag" href="{router page='tag'}{$sTag|escape:'url'}/">{$sTag|escape}</a>
			</li>
		{foreachelse}
			{* TODO: Remove *}
			<li class="tag-list-item tag-list-item-empty">{$aLang.topic_tags_empty}</li>
		{/foreach}

		{* Персональные теги *}
		{if $oUserCurrent && $bTagsUseFavourite}
			{foreach $aTagsFavourite as $sTag}
				<li class="tag-list-item tag-list-item-tag tag-list-item-tag-personal js-tag-list-item-tag-personal">
					, <a href="{$oUserCurrent->getUserWebPath()}favourites/topics/tag/{$sTag|escape:'url'}/" 
					     rel="tag" 
					     class="">{$sTag|escape}</a>
				</li>
			{/foreach}

			<li class="tag-list-item tag-list-item-edit js-favourite-tag-edit" data-type="{$sTagsFavouriteType}" data-id="{$iTagsFavouriteId}" {if ! $oFavourite}style="display:none;"{/if}>
				<a href="#" class="link-dotted">{$aLang.favourite_form_tags_button_show}</a>
			</li>
		{/if}
	{/strip}
</ul>