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
		<img src="{$DIR_STATIC_SKIN}/images/tagcloud.gif" border="0">&nbsp;
		<input type="text" name="tag" value="{$sTag|escape:'html'}" class="tags-input" >
	</form>

<br>

{include file='topic_list.tpl'}


{include file='footer.tpl'}