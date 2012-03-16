{include file='header.tpl' menu="profile"}

{foreach from=$aNotes item=oNote}
	{$oNote->getTargetUser()->getLogin()} &mdash; {$oNote->getText()}  &mdash; {$oNote->getDateAdd()} <br/>
{/foreach}

{include file='paging.tpl' aPaging="$aPaging"}

{include file='footer.tpl'}