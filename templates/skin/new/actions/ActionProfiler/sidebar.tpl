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

					Функционал в разработке
					
				</div></div>
				<div class="bl"><div class="br"></div></div>
			</div>