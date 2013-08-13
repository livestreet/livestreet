{**
 * Базовый шаблоны формы поиска
 *
 * bNoSubmitButton   Не показывать кнопку Искать
 *
 * @styles css/forms.css
 *}

{block name='search_before'}{/block}

<form action="{block name='search_action'}{/block}" 
	  method="{block name='search_method'}get{/block}" 
	  class="search-form search-form-type-{block name='search_type'}default{/block} {block name='search_classes'}{/block}"
	  {block name='search_attributes'}{/block}>
	{block name='search_begin'}{/block}

	{block name='search_input'}
		<div class="search-form-search">
			<input type="text"
				   class="search-form-input width-full {block name='search_input_classes'}{/block}" 
				   name="{block name='search_input_name'}q{/block}" 
				   value="{block name='search_input_value'}{$aReq.q|escape:'html'}{/block}" 
				   autocomplete="off" 
				   placeholder="{block name='search_input_placeholder'}{$aLang.search}{/block}"
				   {block name='search_input_attributes'}{/block}>

			{if ! $bNoSubmitButton}
				<input type="submit" 
					   value="{block name='search_submit_value'}{/block}" 
					   title="{block name='search_submit_title'}{$aLang.search_submit}{/block}" 
					   class="search-form-submit icon-search" 
					   {block name='search_submit_attributes'}{/block}>
			{/if}
		</div>
	{/block}

	{block name='search_input_after'}{/block}

	{if $aPrefixes}
		<div class="search-form-alphabet">
			<ul>
				<li class="active"><a href="#" class="link-dotted" data-letter="">{$aLang.user_search_filter_all}</a></li>

				{foreach $aPrefixes as $sPrefixUser}
					<li><a href="#" class="link-dotted" data-letter="{$sPrefixUser}">{$sPrefixUser}</a></li>
				{/foreach}
			</ul>
		</div>
	{/if}

	{block name='search_end'}{/block}
</form>

{block name='search_after'}{/block}