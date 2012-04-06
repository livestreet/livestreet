{include file='header.tpl'}


<form action="" method="GET" class="js-tag-search-form">
	<input type="text" name="tag" value="{$sTag|escape:'html'}" class="autocomplete-tags js-tag-search" />
</form>


{include file='topic_list.tpl'}
{include file='footer.tpl'}