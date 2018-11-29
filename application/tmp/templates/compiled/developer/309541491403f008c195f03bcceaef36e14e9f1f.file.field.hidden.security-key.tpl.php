<?php /* Smarty version Smarty-3.1.13, created on 2018-11-29 11:18:23
         compiled from "/var/www/ls.new/framework/frontend/components/field/field.hidden.security-key.tpl" */ ?>
<?php /*%%SmartyHeaderCode:10593960075bffa0cf9d6865-15682745%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '309541491403f008c195f03bcceaef36e14e9f1f' => 
    array (
      0 => '/var/www/ls.new/framework/frontend/components/field/field.hidden.security-key.tpl',
      1 => 1543037260,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '10593960075bffa0cf9d6865-15682745',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'LIVESTREET_SECURITY_KEY' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5bffa0cf9dd2b0_94888450',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5bffa0cf9dd2b0_94888450')) {function content_5bffa0cf9dd2b0_94888450($_smarty_tpl) {?><?php if (!is_callable('smarty_function_component')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component.php';
?>

<?php echo smarty_function_component(array('_default_short'=>'field','template'=>'hidden','name'=>'security_ls_key','value'=>$_smarty_tpl->tpl_vars['LIVESTREET_SECURITY_KEY']->value),$_smarty_tpl);?>
<?php }} ?>