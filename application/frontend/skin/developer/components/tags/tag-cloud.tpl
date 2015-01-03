{**
 * Облако тегов
 *
 * @param array  $tags   Массив с тегами
 * @param string $url    Код для получения ссылки тега
 * @param string $text   Код для получения названия тега
 * @param string $active Текст активного тега
 *
 * @styles css/common.css
 *}

{if $smarty.local.tags}
	<ul class="tag-cloud word-wrap">
		{foreach $smarty.local.tags as $tag}
			<li class="tag-cloud-item {if $tag->getText() && $smarty.local.active == $tag->getText()}active{/if}">
				<a class="tag-size-{$tag->getSize()}" href="{eval var=$smarty.local.url}" title="{$tag->getCount()}">
					{if $smarty.local.text}
						{eval var=$smarty.local.text}
					{else}
						{$tag->getText()|escape}
					{/if}
				</a>
			</li>
		{/foreach}
	</ul>
{else}
	{component 'alert' text=$aLang.common.empty mods='empty'}
{/if}