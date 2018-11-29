<?php /* Smarty version Smarty-3.1.13, created on 2018-11-24 09:24:31
         compiled from "/var/www/ls.new/framework/frontend/components/performance/performance.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2453739865bf8ee9fd567f7-72385600%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a6130abbf1c7e7c45b9c232eb6e96dc4545caf1c' => 
    array (
      0 => '/var/www/ls.new/framework/frontend/components/performance/performance.tpl',
      1 => 1543037260,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2453739865bf8ee9fd567f7-72385600',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'bIsShowStatsPerformance' => 0,
    'stats' => 0,
    'timeFullPerformance' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5bf8ee9fda3242_69912903',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5bf8ee9fda3242_69912903')) {function content_5bf8ee9fda3242_69912903($_smarty_tpl) {?><?php if (!is_callable('smarty_function_component_define_params')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component_define_params.php';
if (!is_callable('smarty_function_hook')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.hook.php';
?>

<?php echo smarty_function_component_define_params(array('params'=>array('stats','timeFullPerformance')),$_smarty_tpl);?>


<?php if ($_smarty_tpl->tpl_vars['bIsShowStatsPerformance']->value){?>
    <div class="ls-alert ls-alert--info ls-performance">
        <?php echo smarty_function_hook(array('run'=>'statistics_performance_begin'),$_smarty_tpl);?>


        <table>
            <tr>
                <td>
                    <h4>MySql</h4>
                    query: <strong><?php echo $_smarty_tpl->tpl_vars['stats']->value['sql']['count'];?>
</strong><br />
                    time: <strong><?php echo $_smarty_tpl->tpl_vars['stats']->value['sql']['time'];?>
</strong>
                </td>
                <td>
                    <h4>Cache</h4>
                    query: <strong><?php echo $_smarty_tpl->tpl_vars['stats']->value['cache']['count'];?>
</strong><br />
                    &mdash; set: <strong><?php echo $_smarty_tpl->tpl_vars['stats']->value['cache']['count_set'];?>
</strong><br />
                    &mdash; get: <strong><?php echo $_smarty_tpl->tpl_vars['stats']->value['cache']['count_get'];?>
</strong><br />
                    time: <strong><?php echo $_smarty_tpl->tpl_vars['stats']->value['cache']['time'];?>
</strong>
                </td>
                <td>
                    <h4>PHP</h4>
                    time load modules: <strong><?php echo $_smarty_tpl->tpl_vars['stats']->value['engine']['time_load_module'];?>
</strong><br />
                    full time: <strong><?php echo $_smarty_tpl->tpl_vars['timeFullPerformance']->value;?>
</strong>
                </td>
                <td>
                    <h4>Memory</h4>
                    memory usage: <strong><?php echo memory_get_usage(true)/1024/1024;?>
 Mb</strong><br />
                    memory peak usage: <strong><?php echo memory_get_peak_usage(true)/1024/1024;?>
 Mb</strong>
                </td>

                <?php echo smarty_function_hook(array('run'=>'statistics_performance_item'),$_smarty_tpl);?>

            </tr>
        </table>

        <?php echo smarty_function_hook(array('run'=>'statistics_performance_end'),$_smarty_tpl);?>

    </div>
<?php }?><?php }} ?>