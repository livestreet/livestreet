{**
 * Информация о производительности движка
 *}

{if $bIsShowStatsPerformance && $oUserCurrent && $oUserCurrent->isAdministrator()}
    {$stats = $smarty.local.stats}

    <div class="alert alert--info performance">
        {hook run='statistics_performance_begin'}

        <table>
            <tr>
                <td>
                    <h4>MySql</h4>
                    query: <strong>{$stats.sql.count}</strong><br />
                    time: <strong>{$stats.sql.time}</strong>
                </td>
                <td>
                    <h4>Cache</h4>
                    query: <strong>{$stats.cache.count}</strong><br />
                    &mdash; set: <strong>{$stats.cache.count_set}</strong><br />
                    &mdash; get: <strong>{$stats.cache.count_get}</strong><br />
                    time: <strong>{$stats.cache.time}</strong>
                </td>
                <td>
                    <h4>PHP</h4>
                    time load modules: <strong>{$stats.engine.time_load_module}</strong><br />
                    full time: <strong>{$smarty.local.timeFullPerformance}</strong>
                </td>
                <td>
                    <h4>Memory</h4>
                    memory usage: <strong>{memory_get_usage(true) / 1024 / 1024} Mb</strong><br />
                    memory peak usage: <strong>{memory_get_peak_usage(true) / 1024 / 1024} Mb</strong>
                </td>

                {hook run='statistics_performance_item'}
            </tr>
        </table>

        {hook run='statistics_performance_end'}
    </div>
{/if}