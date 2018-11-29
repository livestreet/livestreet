<?php /* Smarty version Smarty-3.1.13, created on 2018-11-29 11:18:08
         compiled from "/var/www/ls.new/application/plugins/admin/frontend/components/p-graph/graph.tpl" */ ?>
<?php /*%%SmartyHeaderCode:18514405245bffa0c0c51e35-45352677%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd5679774e3f3b9510ff90226ffcb90cd29e1dadc' => 
    array (
      0 => '/var/www/ls.new/application/plugins/admin/frontend/components/p-graph/graph.tpl',
      1 => 1543039194,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '18514405245bffa0c0c51e35-45352677',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'component' => 0,
    'mods' => 0,
    'classes' => 0,
    'attributes' => 0,
    'title' => 0,
    'url' => 0,
    'showFilterType' => 0,
    'sGraphType' => 0,
    'sCurrentGraphType' => 0,
    'aLang' => 0,
    'sTimeInterval' => 0,
    'sCurrentGraphPeriod' => 0,
    'showFilterPeriod' => 0,
    '_aRequest' => 0,
    'sValueSuffix' => 0,
    'data' => 0,
    'item' => 0,
    'iPointsStepForLabels' => 0,
    'name' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5bffa0c0d4ad53_01874137',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5bffa0c0d4ad53_01874137')) {function content_5bffa0c0d4ad53_01874137($_smarty_tpl) {?><?php if (!is_callable('smarty_function_component_define_params')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component_define_params.php';
if (!is_callable('smarty_function_cmods')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.cmods.php';
if (!is_callable('smarty_function_cattr')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.cattr.php';
if (!is_callable('smarty_function_request_filter')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.request_filter.php';
if (!is_callable('smarty_function_component')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component.php';
?>

<?php $_smarty_tpl->tpl_vars['component'] = new Smarty_variable('p-graph', null, 0);?>
<?php echo smarty_function_component_define_params(array('params'=>array('url','data','name','title','showFilterPeriod','showFilterType')),$_smarty_tpl);?>


<div class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
 <?php echo smarty_function_cmods(array('name'=>$_smarty_tpl->tpl_vars['component']->value,'mods'=>$_smarty_tpl->tpl_vars['mods']->value),$_smarty_tpl);?>
 <?php echo $_smarty_tpl->tpl_vars['classes']->value;?>
" <?php echo smarty_function_cattr(array('list'=>$_smarty_tpl->tpl_vars['attributes']->value),$_smarty_tpl);?>
>
    <h2 class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-title"><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</h2>

    <div class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-body">
        <form class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-filter" action="<?php echo $_smarty_tpl->tpl_vars['url']->value;?>
" method="get">
            
            <?php if ($_smarty_tpl->tpl_vars['showFilterType']->value){?>
                <select name="filter[graph_type]" class="width-150">
                    <?php  $_smarty_tpl->tpl_vars['sGraphType'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['sGraphType']->_loop = false;
 $_from = array(PluginAdmin_ModuleStats::DATA_TYPE_REGISTRATIONS,PluginAdmin_ModuleStats::DATA_TYPE_TOPICS,PluginAdmin_ModuleStats::DATA_TYPE_COMMENTS,PluginAdmin_ModuleStats::DATA_TYPE_VOTINGS); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['sGraphType']->key => $_smarty_tpl->tpl_vars['sGraphType']->value){
$_smarty_tpl->tpl_vars['sGraphType']->_loop = true;
?>
                        <option value="<?php echo $_smarty_tpl->tpl_vars['sGraphType']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['sCurrentGraphType']->value==$_smarty_tpl->tpl_vars['sGraphType']->value){?>selected="selected"<?php }?>>
                            <?php echo $_smarty_tpl->tpl_vars['aLang']->value['plugin']['admin']['graph']['graph_type'][$_smarty_tpl->tpl_vars['sGraphType']->value];?>

                        </option>
                    <?php } ?>
                </select>

                &nbsp;
            <?php }?>

            
            <select name="filter[graph_period]" class="width-100">
                <?php  $_smarty_tpl->tpl_vars['sTimeInterval'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['sTimeInterval']->_loop = false;
 $_from = array(PluginAdmin_ModuleStats::TIME_INTERVAL_TODAY,PluginAdmin_ModuleStats::TIME_INTERVAL_YESTERDAY,PluginAdmin_ModuleStats::TIME_INTERVAL_WEEK,PluginAdmin_ModuleStats::TIME_INTERVAL_MONTH); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['sTimeInterval']->key => $_smarty_tpl->tpl_vars['sTimeInterval']->value){
$_smarty_tpl->tpl_vars['sTimeInterval']->_loop = true;
?>
                    <option value="<?php echo $_smarty_tpl->tpl_vars['sTimeInterval']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['sCurrentGraphPeriod']->value==$_smarty_tpl->tpl_vars['sTimeInterval']->value){?>selected="selected"<?php }?>>
                        <?php echo $_smarty_tpl->tpl_vars['aLang']->value['plugin']['admin']['graph']['period_bar'][$_smarty_tpl->tpl_vars['sTimeInterval']->value];?>

                    </option>
                <?php } ?>
            </select>

            &nbsp;&nbsp;&nbsp;

            
            <?php if ($_smarty_tpl->tpl_vars['showFilterPeriod']->value){?>
                <input type="text" name="filter[date_start]" value="<?php echo $_smarty_tpl->tpl_vars['_aRequest']->value['filter']['date_start'];?>
" class="input-text width-100 date-picker-php" placeholder="<?php echo $_smarty_tpl->tpl_vars['aLang']->value['plugin']['admin']['from'];?>
" />
                &nbsp;&ndash;&nbsp;
                <input type="text" name="filter[date_finish]" value="<?php echo $_smarty_tpl->tpl_vars['_aRequest']->value['filter']['date_finish'];?>
" class="input-text width-100 date-picker-php" placeholder="<?php echo $_smarty_tpl->tpl_vars['aLang']->value['plugin']['admin']['to'];?>
" />
                
                <?php if ($_smarty_tpl->tpl_vars['_aRequest']->value['filter']['date_start']){?>
                    <a href="<?php echo $_smarty_tpl->tpl_vars['url']->value;?>
<?php echo smarty_function_request_filter(array('name'=>array('date_start','date_finish'),'value'=>array(null,null)),$_smarty_tpl);?>
">
                        <i class="icon-remove"></i>
                    </a>
                <?php }?>

                &nbsp;&nbsp;
            <?php }?>

            <?php echo smarty_function_component(array('_default_short'=>'admin:button','text'=>$_smarty_tpl->tpl_vars['aLang']->value['plugin']['admin']['show'],'mods'=>'primary'),$_smarty_tpl);?>

        </form>
        <div id="admin_graph_container"></div>
    </div>
</div>

<script>
    jQuery(document).ready(function($) {
        Highcharts.setOptions({
            lang: {
                resetZoom: '<?php echo $_smarty_tpl->tpl_vars['aLang']->value['plugin']['admin']['reset_zoom'];?>
',
                resetZoomTitle: '<?php echo $_smarty_tpl->tpl_vars['aLang']->value['plugin']['admin']['reset_zoom_tip'];?>
'
            }
        });
        $('#admin_graph_container').highcharts({
            chart: {
                type: 'areaspline',
                height: 200,
                spacingBottom: 0,
                spacingLeft: 0,
                spacingRight: 0,
                spacingTop: 10,
                zoomType: 'x'
            },
            title: {
                text: ''
            },
            yAxis: {
                title: {
                    text: ''
                },
                gridLineColor: '#f1f1f1',
                gridLineWidth: 1,
                allowDecimals: false
            },
            tooltip: {
                animation: false,
                shadow: false,
                borderWidth: 0,
                shared: true,
                valueSuffix: ' <?php echo $_smarty_tpl->tpl_vars['sValueSuffix']->value;?>
'
            },
            credits: {
                enabled: false
            },
            legend: {
                enabled: false
            },
            plotOptions: {
                areaspline: {
                    fillOpacity: 0.5
                }
            },
            xAxis: {
                categories: [
                    <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['data']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['item']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['item']->iteration=0;
 $_smarty_tpl->tpl_vars['item']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['item']->iteration++;
 $_smarty_tpl->tpl_vars['item']->index++;
 $_smarty_tpl->tpl_vars['item']->last = $_smarty_tpl->tpl_vars['item']->iteration === $_smarty_tpl->tpl_vars['item']->total;
?>
                        '<?php echo $_smarty_tpl->tpl_vars['item']->value['date'];?>
'<?php if (!$_smarty_tpl->tpl_vars['item']->last){?>,<?php }?>
                    <?php } ?>
                ],
                labels: {
                    
                    staggerLines: 1,
                    
                    step: <?php echo $_smarty_tpl->tpl_vars['iPointsStepForLabels']->value;?>

                }
            },
            series: [{
                name: '<?php echo $_smarty_tpl->tpl_vars['name']->value;?>
',
                color: '#8FCFEA',
                data: [
                    <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['data']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['item']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['item']->iteration=0;
 $_smarty_tpl->tpl_vars['item']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['item']->iteration++;
 $_smarty_tpl->tpl_vars['item']->index++;
 $_smarty_tpl->tpl_vars['item']->last = $_smarty_tpl->tpl_vars['item']->iteration === $_smarty_tpl->tpl_vars['item']->total;
?>
                        [<?php echo $_smarty_tpl->tpl_vars['item']->index;?>
, <?php echo $_smarty_tpl->tpl_vars['item']->value['count'];?>
]<?php if (!$_smarty_tpl->tpl_vars['item']->last){?>,<?php }?>
                    <?php } ?>
                ]
            }]
        });
    });
</script><?php }} ?>