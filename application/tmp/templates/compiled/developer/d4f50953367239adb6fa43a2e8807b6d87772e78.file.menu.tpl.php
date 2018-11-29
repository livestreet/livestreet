<?php /* Smarty version Smarty-3.1.13, created on 2018-11-24 09:24:31
         compiled from "/var/www/ls.new/application/frontend/skin/developer/components/menu/menu.tpl" */ ?>
<?php /*%%SmartyHeaderCode:3962356005bf8ee9fa17454-82848429%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd4f50953367239adb6fa43a2e8807b6d87772e78' => 
    array (
      0 => '/var/www/ls.new/application/frontend/skin/developer/components/menu/menu.tpl',
      1 => 1543035802,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3962356005bf8ee9fa17454-82848429',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'template' => 0,
    'params' => 0,
    'activeItem' => 0,
    'mods' => 0,
    'classes' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5bf8ee9fa319d7_18349898',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5bf8ee9fa319d7_18349898')) {function content_5bf8ee9fa319d7_18349898($_smarty_tpl) {?><?php if (!is_callable('smarty_function_component_define_params')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component_define_params.php';
if (!is_callable('smarty_function_component')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component.php';
?>

<?php echo smarty_function_component_define_params(array('params'=>array('activeItem','mods','classes','template')),$_smarty_tpl);?>
 

<?php echo smarty_function_component(array('_default_short'=>"menu.".((string)$_smarty_tpl->tpl_vars['template']->value),'params'=>$_smarty_tpl->tpl_vars['params']->value,'activeItem'=>$_smarty_tpl->tpl_vars['activeItem']->value,'mods'=>$_smarty_tpl->tpl_vars['mods']->value,'classes'=>$_smarty_tpl->tpl_vars['classes']->value),$_smarty_tpl);?>

<?php }} ?>