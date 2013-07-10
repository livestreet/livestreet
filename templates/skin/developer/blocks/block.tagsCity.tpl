{**
 * Список городов в которых проживают пользователи
 *
 * @styles css/blocks.css
 *}

{extends file='blocks/block.aside.base.tpl'}

{block name='block_title'}{$aLang.block_city_tags}{/block}

{block name='block_content'}
	{if $aCityList && count($aCityList) > 0}
		<ul class="tag-cloud word-wrap">
			{foreach $aCityList as $oCity}
				<li><a class="tag-size-{$oCity->getSize()}" href="{router page='people'}city/{$oCity->getId()}/">{$oCity->getName()|escape:'html'}</a></li>
			{/foreach}					
		</ul>
	{else}
		No cities {* Language *}
	{/if}
{/block}