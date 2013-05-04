{**
 * Список стран в которых проживают пользователи
 *
 * @styles css/blocks.css
 *}

{extends file='blocks/block.aside.base.tpl'}

{block name='options'}
	{assign var='noNav' value=true}
	{assign var='noFooter' value=true}
{/block}

{block name='title'}{$aLang.block_country_tags}{/block}

{block name='content'}
	{if $aCountryList && count($aCountryList) > 0}
		<ul class="tag-cloud word-wrap">
			{foreach from=$aCountryList item=oCountry}
				<li><a class="tag-size-{$oCountry->getSize()}" href="{router page='people'}country/{$oCountry->getId()}/">{$oCountry->getName()|escape:'html'}</a></li>
			{/foreach}					
		</ul>
	{/if}
{/block}