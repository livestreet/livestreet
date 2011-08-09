{include file='header.tpl'}


<form action="" method="GET" onsubmit="return submitTags(this.tag.value);" class="tags-search">
	<img src="{cfg name='path.static.skin'}/images/tagcloud.gif" border="0" style="margin-left: 13px;">&nbsp;
	<input type="text" name="tag" value="{$sTag|escape:'html'}" class="tags-input" >
</form>


{include file='topic_list.tpl'}
{include file='footer.tpl'}