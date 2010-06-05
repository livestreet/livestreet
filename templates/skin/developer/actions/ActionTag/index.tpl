{include file='header.tpl' menu="blog"}


{literal}
<script>
function submitTags(sTag) {		
	window.location=DIR_WEB_ROOT+'/tag/'+sTag+'/';
	return false;
}
</script>
{/literal}


<form action="" method="GET" onsubmit="return submitTags(this.tag.value);">
	<input type="text" name="tag" value="{$sTag|escape:'html'}" />
</form>
<br />

{include file='topic_list.tpl'}
{include file='footer.tpl'}