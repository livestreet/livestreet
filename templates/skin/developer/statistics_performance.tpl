{if $bIsShowStatsPerformance and $oUserCurrent and $oUserCurrent->isAdministrator()}
<fieldset>
<legend>Statistics performance</legend>
<table>
	<tr align="top">
		<td align="top">
		<ul>
	<li>
	<b>MySql</b> <br>
	&nbsp;&nbsp;&nbsp;query: {$aStatsPerformance.sql.count}<br>
	&nbsp;&nbsp;&nbsp;time: {$aStatsPerformance.sql.time}<br><br><br>
	</li>
	</ul>
		</td>
		<td>
		<ul>
	<li>
	<b>Cache</b> <br>
	&nbsp;&nbsp;&nbsp;query: {$aStatsPerformance.cache.count}<br>
	&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; set: {$aStatsPerformance.cache.count_set}<br>
	&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; get: {$aStatsPerformance.cache.count_get}<br>
	&nbsp;&nbsp;&nbsp;time: {$aStatsPerformance.cache.time}
	</li>
	</ul>
		</td>
		<td align="top">
		<ul>
	<li>
	<b>PHP</b> <br>	
	&nbsp;&nbsp;&nbsp;time load modules:{$aStatsPerformance.engine.time_load_module}<br>
	&nbsp;&nbsp;&nbsp;full time:{$iTimeFullPerformance}<br><br><br>
	</li>
	</ul>
		</td>
	</tr>
</table>
</fieldset>
{/if}