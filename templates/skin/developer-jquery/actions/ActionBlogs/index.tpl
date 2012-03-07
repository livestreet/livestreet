{include file='header.tpl' sMenuHeadItemSelect="blogs"}

<h2 class="page-header">{$aLang.blogs}</h2>

{include file='blog_list.tpl'}
{include file='paging.tpl' aPaging="$aPaging"}
{include file='footer.tpl'}