{**
 * Список тегов
 *}

{extends 'component@tags.tags'}

{block 'tags_options' append}
	{$attributes = array_merge( $attributes|default:[], [
		'data-param-target_id' => $smarty.local.targetId
	])}
{/block}

{block 'tags_list' append}
	{* Персональные теги *}
	{if $oUserCurrent}
		{foreach $smarty.local.tagsFavourite as $tag}
			{component 'tags' template='item'
				text=$tag
				url="{$oUserCurrent->getUserWebPath()}favourites/topics/tag/{$tag|escape:'url'}/"
				classes="js-tags-personal-tag"
				mods="personal"}
		{/foreach}

		{* Кнопка "Изменить теги" *}
		<li class="ls-tags-item ls-tags-personal-edit js-tags-personal-edit" {if $smarty.local.isEditable}style="display:none;"{/if}>
			<a href="#" class="link-dotted">
				{component 'icon' icon='edit'} 
				{lang 'favourite_tags.edit'}
			</a>
		</li>
	{/if}
{/block}