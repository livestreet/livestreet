<div class="block">	
	<div class="profiler-highlight">
		{$aLang.plugin.profiler.filter_highlight} ({$aLang.plugin.profiler.filter_seconds}):<br />
		<input type="text" name="profiler_filter_entries" id="profiler_filter_entries" onchange="ls.profiler.filterNode(this);" class="input-100" />
	</div>
</div>

<div class="block">	
	<h3>{$aLang.plugin.profiler.dbstat_title}</h3>

	<form action="{router page='profiler'}" method="POST" name="profiler_import_form">
		<p>{$aLang.plugin.profiler.dbstat_count}: <strong>{$aDatabaseStat.count}</strong><br />
		{$aLang.plugin.profiler.dbstat_max_date}: <strong>{$aDatabaseStat.max_date}</strong></p>
		
		<p><label for="profiler_date_import">{$aLang.plugin.profiler.import_label}:</label><br />
		<input type="text" id="profiler_date_import" name="profiler_date_import" value="{if $_aRequest.date_import}{$_aRequest.date_import}{else}{if $aDatabaseStat.max_date}{$aDatabaseStat.max_date}{else}{date_format date=$smarty.now format='Y-m-d \0\0\:\0\0\:\0\0'}{/if}{/if}" class="input-200" /><br />
		<span class="note">{$aLang.plugin.profiler.import_notice}</span></p>
								
		<input type="submit" name="submit_profiler_import" value="{$aLang.plugin.profiler.import_submit}" />					
	</form>
</div>

			
<div class="block">		
	<h3>{$aLang.plugin.profiler.filter_title}</h3>

	{literal}
		<script language="JavaScript" type="text/javascript">
		
		function eraseFilterForm() {
			jQuery("#profiler_filter_per_page, #profiler_filter_time, #profiler_filter_start, #profiler_filter_end").each(
				function(k,v){
					return jQuery(v).attr('value','');
				}
			);
			return false;
		}
		</script>
	{/literal}		
	
	
	<form action="{router page='profiler'}" method="GET" name="profiler_filter_form">							
		<p><label for="profiler_filter_start">{$aLang.plugin.profiler.filter_label_date}:</label><br />
		<input type="text" id="profiler_filter_start" name="start" value="{$_aRequest.start}" style="width: 43%" readonly="readonly"  class="date-picker"/> &mdash; 
		<input type="text" id="profiler_filter_end" name="end" value="{$_aRequest.end}" style="width: 43%" readonly="readonly"  class="date-picker"/><br />
		<span class="note">{$aLang.plugin.profiler.filter_notice_date}</span></p>
			
		<p><label for="profiler_filter_time">{$aLang.plugin.profiler.filter_label_time}:</label><br />
		<input type="text" id="profiler_filter_time" name="time" value="{$_aRequest.time}" class="input-100" /><br />
		<span class="note">{$aLang.plugin.profiler.filter_notice_time}</span></p>							
		
		<p><label for="profiler_filter_per_page">{$aLang.plugin.profiler.filter_label_per_page}:</label><br />
		<input type="text" id="profiler_filter_per_page" name="per_page" value="{if $_aRequest.per_page}{$_aRequest.per_page}{else}{cfg name='module.profiler.per_page'}{/if}" class="input-200" /></p>						
								
		<input type="submit" name="submit_profiler_filter" value="{$aLang.plugin.profiler.filter_submit}"/>													
	</form>
	
	<div class="bottom"><a href="#" onclick="return eraseFilterForm();">{$aLang.plugin.profiler.filter_erase_form}</a> | <a href="{router page='profiler'}">{$aLang.plugin.profiler.filter_erase}</a></div>					
</div>