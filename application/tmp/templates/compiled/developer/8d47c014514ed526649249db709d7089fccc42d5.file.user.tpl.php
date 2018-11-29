<?php /* Smarty version Smarty-3.1.13, created on 2018-11-24 09:24:31
         compiled from "/var/www/ls.new/application/frontend/skin/developer/components/menu/user.tpl" */ ?>
<?php /*%%SmartyHeaderCode:13323238405bf8ee9fa371b8-58270344%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8d47c014514ed526649249db709d7089fccc42d5' => 
    array (
      0 => '/var/www/ls.new/application/frontend/skin/developer/components/menu/user.tpl',
      1 => 1543035802,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '13323238405bf8ee9fa371b8-58270344',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'oUserCurrent' => 0,
    'params' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5bf8ee9fa56d29_49448606',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5bf8ee9fa56d29_49448606')) {function content_5bf8ee9fa56d29_49448606($_smarty_tpl) {?><?php if (!is_callable('smarty_function_component')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component.php';
?>
<?php echo smarty_function_component(array('_default_short'=>'nav.item','isRoot'=>true,'text'=>"<img src=\"".((string)$_smarty_tpl->tpl_vars['oUserCurrent']->value->getProfileAvatarPath(24))."\" alt=\"".((string)$_smarty_tpl->tpl_vars['oUserCurrent']->value->getDisplayName())."\" class=\"avatar\" /> ".((string)$_smarty_tpl->tpl_vars['oUserCurrent']->value->getDisplayName()),'url'=>((string)$_smarty_tpl->tpl_vars['oUserCurrent']->value->getUserWebPath()),'classes'=>'ls-nav-item--userbar-username','menu'=>$_smarty_tpl->tpl_vars['params']->value),$_smarty_tpl);?>

<?php }} ?>