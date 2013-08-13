{extends file='layouts/layout.base.tpl'}

{block name='layout_options'}
	{$noShowSystemMessage = false}
{/block}

{block name='layout_page_title'}{$aLang.plugin.profiler.reports_title}{/block}

{block name='layout_head_end'}
	<script>
		var DIR_PLUGIN_SKIN = '{$aTemplateWebPathPlugin.profiler}';
	</script>

	<script type="text/javascript" src="{$aTemplateWebPathPlugin.profiler}js/profiler.js"></script>
	<link rel="stylesheet" type="text/css" href="{$aTemplateWebPathPlugin.profiler}css/style.css" media="all" />
{/block}

{block name='layout_content'}
	<form action="{router page='profiler'}" method="post" id="form_report_list">
		<input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" /> 
		
		{if $aReports}
			<table class="table">
				<thead>
					<tr>
						<th width="20px"><input type="checkbox" name="" onclick="ls.tools.checkAll('form_reports_checkbox', this, true);"></th>
						<th></th>
						<th>{$aLang.plugin.profiler.table_date}</th>
						<th class="ta-c">{$aLang.plugin.profiler.table_time_full}</th>
						<th class="ta-c">{$aLang.plugin.profiler.table_count_id}</th>
					</tr>
				</thead>
				
				<tbody>
					{foreach $aReports as $oReport}
						<tr>
							<td><input type="checkbox" name="report_del[{$oReport.request_id}]" class="form_reports_checkbox"></td>
							<td align="center"><img src="{$aTemplateWebPathPlugin.profiler}images/open.gif" alt="+" title="{$aLang.comment_collapse}/{$aLang.comment_expand}" class="folding" id="img_{$oReport.request_id}" /></td>
							<td>{date_format date=$oReport.request_date}</td>							
							<td align="center" class="time">{$oReport.time_full}</td>
							<td align="center">{$oReport.count_time_id}</td>
						</tr>
					{/foreach}
				</tbody>
			</table>

			<input type="submit" 
				   name="submit_report_delete" 
				   value="{$aLang.plugin.profiler.report_delete}" 
				   class="button"
				   onclick="return (jQuery('.form_reports_checkbox').length==0)?false:confirm('{$aLang.plugin.profiler.report_delete_confirm}');">
		{else}
			{$aLang.plugin.profiler.no_reports}
		{/if}
	</form>

	{include file='pagination.tpl' aPaging="$aPaging"}
{/block}