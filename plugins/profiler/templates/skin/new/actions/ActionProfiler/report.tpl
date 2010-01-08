{include file='header.tpl' noShowSystemMessage=false}
<script type="text/javascript" src="{cfg name='path.root.web'}/plugins/profiler/templates/skin/new/js/profiler.js"></script>

			<div class="topic people top-blogs talk-table">
				<h1>{$aLang.profiler_reports_title}</h1>
				<form action="{router page='profiler'}" method="post" id="form_report_list">
				<input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" /> 
				<table>
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
							<td><img src="{cfg name='path.static.skin'}/images/open.gif" alt="+" title="{$aLang.comment_collapse}/{$aLang.comment_expand}" class="folding" id="img_{$oReport.request_id}" /></td>
							<td>{date_format date=$oReport.request_date}</td>							
							<td align="center" class="time">{$oReport.time_full}</td>
							<td align="center">{$oReport.count_time_id}</td>
						</tr>
					{/foreach}
					</tbody>
				</table>
				<input type="submit" name="submit_report_delete" value="{$aLang.profiler_report_delete}" onclick="return ($$('.form_reports_checkbox').length==0)?false:confirm('{$aLang.profiler_report_delete_confirm}');">
				</form>
			</div>
{include file='paging.tpl' aPaging=`$aPaging`}
{include file='footer.tpl'}