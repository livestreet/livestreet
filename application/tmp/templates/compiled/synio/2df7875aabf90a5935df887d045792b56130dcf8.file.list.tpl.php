<?php /* Smarty version Smarty-3.1.13, created on 2018-11-24 09:00:15
         compiled from "/var/www/ls.new/application/plugins/admin/frontend/components/p-plugin/list.tpl" */ ?>
<?php /*%%SmartyHeaderCode:4759072475bf8e8f000c1a3-14873401%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2df7875aabf90a5935df887d045792b56130dcf8' => 
    array (
      0 => '/var/www/ls.new/application/plugins/admin/frontend/components/p-plugin/list.tpl',
      1 => 1543039194,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '4759072475bf8e8f000c1a3-14873401',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'plugins' => 0,
    'plugin' => 0,
    'updates' => 0,
    'type' => 0,
    'aLang' => 0,
    'pagination' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5bf8e8f003e8a7_86761514',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5bf8e8f003e8a7_86761514')) {function content_5bf8e8f003e8a7_86761514($_smarty_tpl) {?><?php if (!is_callable('smarty_function_component_define_params')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component_define_params.php';
if (!is_callable('smarty_function_component')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component.php';
?>

<?php echo smarty_function_component_define_params(array('params'=>array('plugins','pagination','updates','type')),$_smarty_tpl);?>


<?php if ($_smarty_tpl->tpl_vars['plugins']->value){?>
    <div class="ls-plugin-list">
        <?php  $_smarty_tpl->tpl_vars['plugin'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['plugin']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['plugins']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['plugin']->key => $_smarty_tpl->tpl_vars['plugin']->value){
$_smarty_tpl->tpl_vars['plugin']->_loop = true;
?>
            <?php echo smarty_function_component(array('_default_short'=>'admin:p-plugin','plugin'=>$_smarty_tpl->tpl_vars['plugin']->value,'updates'=>$_smarty_tpl->tpl_vars['updates']->value),$_smarty_tpl);?>

        <?php } ?>
    </div>
<?php }else{ ?>
    <?php echo smarty_function_component(array('_default_short'=>'admin:blankslate','text'=>$_smarty_tpl->tpl_vars['aLang']->value['plugin']['admin']['plugins']['no_plugins'][$_smarty_tpl->tpl_vars['type']->value]),$_smarty_tpl);?>

<?php }?>

<?php if ($_smarty_tpl->tpl_vars['pagination']->value){?>
    <?php echo smarty_function_component(array('_default_short'=>'admin:pagination','total'=>+$_smarty_tpl->tpl_vars['pagination']->value['iCountPage'],'current'=>+$_smarty_tpl->tpl_vars['pagination']->value['iCurrentPage'],'url'=>((string)$_smarty_tpl->tpl_vars['pagination']->value['sBaseUrl'])."/page__page__/".((string)$_smarty_tpl->tpl_vars['pagination']->value['sGetParams'])),$_smarty_tpl);?>

<?php }?><?php }} ?>