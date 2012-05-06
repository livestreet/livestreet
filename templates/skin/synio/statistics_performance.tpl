{if $bIsShowStatsPerformance and $oUserCurrent and $oUserCurrent->isAdministrator()}
	<div class="stat-performance">
		{hook run='statistics_performance_begin'}
		<table>
			<tr>
				<td>
					<h4>MySql</h4>
					query: <strong>{$aStatsPerformance.sql.count}</strong><br />
					time: <strong>{$aStatsPerformance.sql.time}</strong>
				</td>
				<td>
					<h4>Cache</h4>
					query: <strong>{$aStatsPerformance.cache.count}</strong><br />
					&mdash; set: <strong>{$aStatsPerformance.cache.count_set}</strong><br />
					&mdash; get: <strong>{$aStatsPerformance.cache.count_get}</strong><br />
					time: <strong>{$aStatsPerformance.cache.time}</strong>
				</td>
				<td>
					<h4>PHP</h4>	
					time load modules: <strong>{$aStatsPerformance.engine.time_load_module}</strong><br />
					full time: <strong>{$iTimeFullPerformance}</strong>
				</td>
				{hook run='statistics_performance_item'}
			</tr>
		</table>
		{hook run='statistics_performance_end'}
	</div>
{/if}