{include file='header.tpl' noShowSystemMessage=false}

<script language="JavaScript" type="text/javascript">
	var DIR_PLUGIN_SKIN='{cfg name='path.root.web'}/plugins/profiler/templates/skin/developer';
</script>

<script type="text/javascript" src="{$sTemplateWebPathPlugin}js/profiler.js"></script>
<link rel="stylesheet" type="text/css" href="{$sTemplateWebPathPlugin}css/style.css" media="all" />



<h2>{$aLang.profiler_reports_title}</h2>

<form action="{router page='profiler'}" method="post" id="form_report_list">
	<input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" /> 
	
	<table class="table">
		<thead>
			<tr>
				<td width="20px"><input type="checkbox" name="" onclick="checkAllReport(this);"></td>
				<td></td>
				<td>{$aLang.profiler_table_date}</td>
				<td align="center">{$aLang.profiler_table_time_full}</td>
				<td align="center">{$aLang.profiler_table_count_id}</td>
			</tr>
		</thead>
		
		<tbody>
		{foreach from=$aReports item=oReport}
			<tr>
				<td><input type="checkbox" name="report_del[{$oReport.request_id}]" class="form_reports_checkbox"></td>
				<td align="center"><img src="{$sTemplateWebPathPlugin}images/open.gif" alt="+" title="{$aLang.comment_collapse}/{$aLang.comment_expand}" class="folding" id="img_{$oReport.request_id}" /></td>
				<td>{date_format date=$oReport.request_date}</td>							
				<td align="center" class="time">{$oReport.time_full}</td>
				<td align="center">{$oReport.count_time_id}</td>
			</tr>
		{/foreach}
		</tbody>
	</table>

	<input type="submit" name="submit_report_delete" value="{$aLang.profiler_report_delete}" onclick="return ($$('.form_reports_checkbox').length==0)?false:confirm('{$aLang.profiler_report_delete_confirm}');">
</form>

				
{include file='pagination.tpl' aPaging="$aPaging"}
{include file='footer.tpl'}