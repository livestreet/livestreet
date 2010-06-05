{include file='header.tpl' sMenuHeadItemSelect="blogs"}

<h2>{$aLang.blogs}</h2>

{include file='blog_list.tpl'}
{include file='pagination.tpl' aPaging="$aPaging"}
{include file='footer.tpl'}