{include file='header.tpl'}


<form action="#" method="GET" class="tags-search" id="tag_search_form">
	<img src="{cfg name='path.static.skin'}/images/tagcloud.gif" class="tagcloud" alt="" />&nbsp;
	<input type="text" name="tag" value="{$sTag|escape:'html'}" class="tags-input" id="tag_search" >
</form>


{include file='topic_list.tpl'}
{include file='footer.tpl'}