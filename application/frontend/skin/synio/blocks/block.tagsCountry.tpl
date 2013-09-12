{**
 * Список стран в которых проживают пользователи
 *
 * @styles css/blocks.css
 *}

{extends file='blocks/block.aside.base.tpl'}

{block name='block_title'}{$aLang.block_country_tags}{/block}

{block name='block_content'}
	{if $aCountryList && count($aCountryList) > 0}
		<ul class="tag-cloud word-wrap">
			{foreach $aCountryList as $oCountry}
				<li><a class="tag-size-{$oCountry->getSize()}" href="{router page='people'}country/{$oCountry->getId()}/">{$oCountry->getName()|escape:'html'}</a></li>
			{/foreach}					
		</ul>
	{/if}
{/block}