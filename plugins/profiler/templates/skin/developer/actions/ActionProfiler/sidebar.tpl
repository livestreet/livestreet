<div class="block">	
	<div class="profiler-highlight">
		{$aLang.profiler_filter_highlight} ({$aLang.profiler_filter_seconds}):<br />
		<input type="text" name="profiler_filter_entries" id="profiler_filter_entries" onchange="lsProfiler.filterNode(this);" class="input-100" />
	</div>
</div>

<div class="block">	
	<h3>{$aLang.profiler_dbstat_title}</h3>

	<form action="{router page='profiler'}" method="POST" name="profiler_import_form">
		<p>{$aLang.profiler_dbstat_count}: <strong>{$aDatabaseStat.count}</strong><br />
		{$aLang.profiler_dbstat_max_date}: <strong>{$aDatabaseStat.max_date}</strong></p>
		
		<p><label for="profiler_date_import">{$aLang.profiler_import_label}:</label><br />
		<input type="text" id="profiler_date_import" name="profiler_date_import" value="{if $_aRequest.date_import}{$_aRequest.date_import}{else}{if $aDatabaseStat.max_date}{$aDatabaseStat.max_date}{else}{date_format date=$smarty.now format='Y-m-d \0\0\:\0\0\:\0\0'}{/if}{/if}" class="input-200" /><br />
		<span class="note">{$aLang.profiler_import_notice}</span></p>
								
		<input type="submit" name="submit_profiler_import" value="{$aLang.profiler_import_submit}" />					
	</form>
</div>

			
<div class="block">		
	<h3>{$aLang.profiler_filter_title}</h3>

	{literal}
		<script language="JavaScript" type="text/javascript">
		document.addEvent('domready', function() {	
			new vlaDatePicker(
				$('profiler_filter_start'),
				{ 
					separator: '.', 
					leadingZero: true, 
					twoDigitYear: false,
					alignX: 'center', 
					alignY: 'top',
					offset: { y: 3 },
					filePath: DIR_WEB_ROOT+'/engine/lib/external/MooTools_1.2/plugs/vlaCal-v2.1/inc/', 
					prefillDate: false,
					startMonday: true
				} 
			);
			new vlaDatePicker(
				$('profiler_filter_end'),
				{ 
					separator: '.', 
					leadingZero: true, 
					twoDigitYear: false,
					alignX: 'center', 
					alignY: 'top',
					offset: { y: 3 },
					filePath: DIR_WEB_ROOT+'/engine/lib/external/MooTools_1.2/plugs/vlaCal-v2.1/inc/', 
					prefillDate: false,
					startMonday: true
				} 
			);	
		});

		function eraseFilterForm() {
			$$("#profiler_filter_per_page, #profiler_filter_time, #profiler_filter_start, #profiler_filter_end").each(
				function(item,index){
					return item.set('value','');
				}
			);
			return false;
		}
		</script>
	{/literal}		
	
	
	<form action="{router page='profiler'}" method="GET" name="profiler_filter_form">							
		<p><label for="profiler_filter_start">{$aLang.profiler_filter_label_date}:</label><br />
		<input type="text" id="profiler_filter_start" name="start" value="{$_aRequest.start}" style="width: 43%" readonly="readonly" /> &mdash; 
		<input type="text" id="profiler_filter_end" name="end" value="{$_aRequest.end}" style="width: 43%" readonly="readonly" /><br />
		<span class="note">{$aLang.profiler_filter_notice_date}</span></p>
			
		<p><label for="profiler_filter_time">{$aLang.profiler_filter_label_time}:</label><br />
		<input type="text" id="profiler_filter_time" name="time" value="{$_aRequest.time}" class="input-100" /><br />
		<span class="note">{$aLang.profiler_filter_notice_time}</span></p>							
		
		<p><label for="profiler_filter_per_page">{$aLang.profiler_filter_label_per_page}:</label><br />
		<input type="text" id="profiler_filter_per_page" name="per_page" value="{if $_aRequest.per_page}{$_aRequest.per_page}{else}{cfg name='module.profiler.per_page'}{/if}" class="input-200" /></p>						
								
		<input type="submit" name="submit_profiler_filter" value="{$aLang.profiler_filter_submit}"/>													
	</form>
	
	<div class="bottom"><a href="#" onclick="return eraseFilterForm();">{$aLang.profiler_filter_erase_form}</a> | <a href="{router page='profiler'}">{$aLang.profiler_filter_erase}</a></div>					
</div>