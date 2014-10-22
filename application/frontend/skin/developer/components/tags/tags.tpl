{**
 * Список тегов
 *}

{$component = 'tags'}

{if $smarty.local.tags}
	<ul class="tag-list js-tags-topic-{$smarty.local.targetId}" data-type="{$smarty.local.targetType}" data-id="{$smarty.local.targetId}">
		<li class="tag-list-item tag-list-item-label">{$aLang.tags.tags}:</li>

		{strip}
			{block 'tags_list'}
				{foreach $smarty.local.tags as $tag}
					<li class="tag-list-item tag-list-item-tag">
						{if ! $tag@first}, {/if}<a rel="tag" href="{router page='tag'}{$tag|escape:'url'}/">{$tag|escape}</a>
					</li>
				{/foreach}
			{/block}
		{/strip}
	</ul>
{/if}