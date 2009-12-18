{if $oReport}
	<div class="topic people top-blogs talk-table">
		<table>
			{foreach from=$oReport->getAllEntries() item=oEntry}
			<tr>
				<td width="8%">{$oEntry->getId()}</td>
				<td width="18%">{$oEntry->getName()}</td>
				<td width="15%">{$oEntry->getTimeFull()}</td>
				<td>{$oEntry->getComment()}</td>
			</tr>
			{/foreach}
		</table>
	</div>
{else}
	 {$aLang.error}
{/if}