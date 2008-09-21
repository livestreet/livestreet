<DIV class=tagsblock>
{foreach from=$aTags item=oTag}
	<a href="{$DIR_WEB_ROOT}/tag/{$oTag->getText()|escape:'html'}/" style="font-size: {$oTag->getSize()}px;">{$oTag->getText()|escape:'html'}</a>&nbsp;
{/foreach}
</DIV>