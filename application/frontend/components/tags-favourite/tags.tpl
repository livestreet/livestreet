{**
 * Список тегов
 *}

{extends 'Component@tags.tags'}

{block 'tags_list' append}
	{* Персональные теги *}
	{if $oUserCurrent}
		{strip}
			{foreach $smarty.local.tagsFavourite as $tag}
				<li class="{$component}-item {$component}-item-tag {$component}-item-tag-personal js-tag-list-item-tag-personal">
					, <a href="{$oUserCurrent->getUserWebPath()}favourites/topics/tag/{$tag|escape:'url'}/"
					     rel="tag"
					     class="">{$tag|escape}</a>
				</li>
			{/foreach}

			{* Кнопка "Изменить теги" *}
			<li class="{$component}-item {$component}-item-edit js-favourite-tag-edit" data-type="{$smarty.local.targetType}" data-id="{$smarty.local.targetId}" {if $smarty.local.isEditable}style="display:none;"{/if}>
				<a href="#" class="link-dotted">{lang 'favourite_tags.edit'}</a>
			</li>
		{/strip}
	{/if}
{/block}