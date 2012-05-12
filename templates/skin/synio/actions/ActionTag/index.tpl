{include file='header.tpl'}


<form action="" method="GET" class="js-tag-search-form search-tags">
	<input type="text" name="tag" placeholder="{$aLang.block_tags_search}" value="{$sTag|escape:'html'}" class="input-text input-width-full autocomplete-tags js-tag-search" />
</form>


{include file='topic_list.tpl'}
{include file='footer.tpl'}