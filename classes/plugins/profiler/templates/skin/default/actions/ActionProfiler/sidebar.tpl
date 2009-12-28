	<div class="profiler-highlight">{$aLang.profiler_filter_highlight}: <input type="text" name="profiler_filter_entries" id="profiler_filter_entries" onchange="lsProfiler.filterNode(this);" class="w50" /> {$aLang.profiler_filter_seconds}</div>
	<div class="block blogs">
				<div class="tl"><div class="tr"></div></div>
				<div class="cl"><div class="cr">
					
					<h1>{$aLang.profiler_dbstat_title}</h1>

					<form action="{router page='profiler'}" method="POST" name="profiler_import_form">
							<p>{$aLang.profiler_dbstat_count}: {$aDatabaseStat.count}<br />
							{$aLang.profiler_dbstat_max_date}: {$aDatabaseStat.max_date}</p>
							<p>	
								<label for="profiler_date_import">{$aLang.profiler_import_label}:</label><br />
								<input type="text" id="profiler_date_import" name="profiler_date_import" value="{if $_aRequest.date_import}{$_aRequest.date_import}{else}{if $aDatabaseStat.max_date}{$aDatabaseStat.max_date}{else}{date_format date=$smarty.now format='Y-m-d \0\0\:\0\0\:\0\0'}{/if}{/if}" class="w100p" /><br />
       							<span class="form_note">{$aLang.profiler_import_notice}</span>
							</p>
							<p class="buttons">								
								<input type="submit" name="submit_profiler_import" value="{$aLang.profiler_import_submit}"/>
							</p>					
					</form>
					<br/>
				</div></div>
				<div class="bl"><div class="br"></div></div>
			</div>

					
	<div class="block blogs">
				<div class="tl"><div class="tr"></div></div>
				<div class="cl"><div class="cr">
					
					<h1>{$aLang.profiler_filter_title}</h1>

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
							<input type="text" id="profiler_filter_start" name="start" value="{$_aRequest.start}" class="w100p" style="width: 44%" readonly="readonly" /> &mdash; 
							<input type="text" id="profiler_filter_end" name="end" value="{$_aRequest.end}" class="w100p" style="width: 44%" readonly="readonly" /><br />
       						<span class="form_note">{$aLang.profiler_filter_notice_date}</span>
							</p>
								
							<p><label for="profiler_filter_time">{$aLang.profiler_filter_label_time}:</label>
							<input type="text" id="profiler_filter_time" name="time" value="{$_aRequest.time}" class="w100" /><br />
       						<span class="form_note">{$aLang.profiler_filter_notice_time}</span>
							</p>							
							
							<p><label for="profiler_filter_per_page">{$aLang.profiler_filter_label_per_page}:</label>
							<input type="text" id="profiler_filter_per_page" name="per_page" value="{if $_aRequest.per_page}{$_aRequest.per_page}{else}{cfg name='module.profiler.per_page'}{/if}" class="w50" /><br />
       						<span class="form_note">{$aLang.profiler_filter_notice_per_page}</span>
							</p>						
							
							<p class="buttons">								
								<input type="submit" name="submit_profiler_filter" value="{$aLang.profiler_filter_submit}"/>
							</p>													
						</form>
					<div class="right"><a href="#" onclick="return eraseFilterForm();">{$aLang.profiler_filter_erase_form}</a> | <a href="{router page='profiler'}">{$aLang.profiler_filter_erase}</a></div>					
				</div></div>
				<div class="bl"><div class="br"></div></div>
			</div>