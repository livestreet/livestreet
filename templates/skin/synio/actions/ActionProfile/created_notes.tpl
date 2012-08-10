{assign var="sidebarPosition" value='left'}
{include file='header.tpl' menu='people'}



{include file='actions/ActionProfile/profile_top.tpl'}
{include file='menu.profile_created.tpl'}


{if $aNotes}
	<table class="table table-profile-notes" cellspacing="0">
		{foreach from=$aNotes item=oNote}
			<tr>
				<td class="cell-username"><a href="{$oNote->getTargetUser()->getUserWebPath()}">{$oNote->getTargetUser()->getLogin()}</a></td>
				<td class="cell-note">{$oNote->getText()}</td>
				<td class="cell-date">{date_format date=$oNote->getDateAdd() format="j F Y"}</td>
			</tr>
		{/foreach}
	</table>
{else}
	<div class="notice-empty">{$aLang.user_note_list_empty}</div>
{/if}


{include file='paging.tpl' aPaging=$aPaging}



{include file='footer.tpl'}