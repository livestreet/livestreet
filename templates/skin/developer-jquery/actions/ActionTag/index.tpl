{include file='header.tpl'}


<form action="" method="GET" id="tag_search_form">
	<input type="text" name="tag" id="tag_search" value="{$sTag|escape:'html'}" />
</form>


{include file='topic_list.tpl'}
{include file='footer.tpl'}