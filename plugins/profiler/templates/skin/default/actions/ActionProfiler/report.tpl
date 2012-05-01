{include file='header.tpl' noShowSystemMessage=false}

<script language="JavaScript" type="text/javascript">
	var DIR_PLUGIN_SKIN='{$aTemplateWebPathPlugin.profiler}';
</script>

<script type="text/javascript" src="{$aTemplateWebPathPlugin.profiler}js/profiler.js"></script>
<link rel="stylesheet" type="text/css" href="{$aTemplateWebPathPlugin.profiler}css/style.css" media="all" />



<h2>{$aLang.plugin.profiler.reports_title}</h2>

<form action="{router page='profiler'}" method="post" id="form_report_list">
	<input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" /> 
	
	<table class="table">
		<thead>
			<tr>
				<td width="20px"><input type="checkbox" name="" onclick="ls.tools.checkAll('form_reports_checkbox', this, true);"></td>
				<td></td>
				<td>{$aLang.plugin.profiler.table_date}</td>
				<td align="center">{$aLang.plugin.profiler.table_time_full}</td>
				<td align="center">{$aLang.plugin.profiler.table_count_id}</td>
			</tr>
		</thead>
		
		<tbody>
		{foreach from=$aReports item=oReport}
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

	<input type="submit" name="submit_report_delete" value="{$aLang.plugin.profiler.report_delete}" onclick="return (jQuery('.form_reports_checkbox').length==0)?false:confirm('{$aLang.plugin.profiler.report_delete_confirm}');">
</form>

				
{include file='paging.tpl' aPaging="$aPaging"}
{include file='footer.tpl'}