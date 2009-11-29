{include file='header.tpl' menu="blog"}

{literal}
<script>
function submitTags(sTag) {		
	window.location=DIR_WEB_ROOT+'/tag/'+sTag+'/';
	return false;
}
</script>
{/literal}

	&nbsp;&nbsp;
	<form action="" method="GET" onsubmit="return submitTags(this.tag.value);">
		<img src="{cfg name='path.static.skin'}/images/tagcloud.gif" border="0" style="margin-left: 13px;">&nbsp;
		<input type="text" name="tag" value="{$sTag|escape:'html'}" class="tags-input" >
	</form>

<br>

{include file='topic_list.tpl'}


{include file='footer.tpl'}