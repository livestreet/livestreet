<?php /* Smarty version Smarty-3.1.13, created on 2018-11-25 11:43:22
         compiled from "/var/www/ls.new/framework/frontend/components/badge/badge.tpl" */ ?>
<?php /*%%SmartyHeaderCode:3727786485bfa60aa744265-93597355%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '02a103961bdba40688656e5292173dc6b5830291' => 
    array (
      0 => '/var/www/ls.new/framework/frontend/components/badge/badge.tpl',
      1 => 1543037260,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3727786485bfa60aa744265-93597355',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'value' => 0,
    'component' => 0,
    'mods' => 0,
    'classes' => 0,
    'attributes' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5bfa60aa7d0a73_15829688',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5bfa60aa7d0a73_15829688')) {function content_5bfa60aa7d0a73_15829688($_smarty_tpl) {?><?php if (!is_callable('smarty_function_component_define_params')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component_define_params.php';
if (!is_callable('smarty_function_cmods')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.cmods.php';
if (!is_callable('smarty_function_cattr')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.cattr.php';
?>


<?php $_smarty_tpl->tpl_vars['component'] = new Smarty_variable('ls-badge', null, 0);?>
<?php echo smarty_function_component_define_params(array('params'=>array('value','mods','classes','attributes')),$_smarty_tpl);?>





<?php if ($_smarty_tpl->tpl_vars['value']->value){?>
    <div class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
 <?php echo smarty_function_cmods(array('name'=>$_smarty_tpl->tpl_vars['component']->value,'mods'=>$_smarty_tpl->tpl_vars['mods']->value),$_smarty_tpl);?>
 <?php echo $_smarty_tpl->tpl_vars['classes']->value;?>
" <?php echo smarty_function_cattr(array('list'=>$_smarty_tpl->tpl_vars['attributes']->value),$_smarty_tpl);?>
>
        <?php echo $_smarty_tpl->tpl_vars['value']->value;?>

    </div>
<?php }?><?php }} ?>