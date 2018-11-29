<?php /* Smarty version Smarty-3.1.13, created on 2018-11-25 11:43:09
         compiled from "/var/www/ls.new/application/frontend/components/property/input/list.tpl" */ ?>
<?php /*%%SmartyHeaderCode:8918994005bfa609dbc3292-58743852%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '339b72a3c6404c8cb7762ac26afa19c4f2a4d81e' => 
    array (
      0 => '/var/www/ls.new/application/frontend/components/property/input/list.tpl',
      1 => 1543035802,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '8918994005bfa609dbc3292-58743852',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'properties' => 0,
    'property' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5bfa609dc83261_98736410',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5bfa609dc83261_98736410')) {function content_5bfa609dc83261_98736410($_smarty_tpl) {?><?php if (!is_callable('smarty_function_component_define_params')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component_define_params.php';
if (!is_callable('smarty_function_component')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component.php';
?>

<?php echo smarty_function_component_define_params(array('params'=>array('properties')),$_smarty_tpl);?>


<?php  $_smarty_tpl->tpl_vars['property'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['property']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['properties']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['property']->key => $_smarty_tpl->tpl_vars['property']->value){
$_smarty_tpl->tpl_vars['property']->_loop = true;
?>
    <?php echo smarty_function_component(array('_default_short'=>'property','template'=>'input.item','property'=>$_smarty_tpl->tpl_vars['property']->value),$_smarty_tpl);?>

<?php } ?><?php }} ?>