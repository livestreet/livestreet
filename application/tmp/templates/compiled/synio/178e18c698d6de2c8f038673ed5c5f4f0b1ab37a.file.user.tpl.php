<?php /* Smarty version Smarty-3.1.13, created on 2018-11-24 09:24:06
         compiled from "/var/www/ls.new/application/frontend/skin/synio/components/menu/user.tpl" */ ?>
<?php /*%%SmartyHeaderCode:18282912765bf8ee865d9354-62166290%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '178e18c698d6de2c8f038673ed5c5f4f0b1ab37a' => 
    array (
      0 => '/var/www/ls.new/application/frontend/skin/synio/components/menu/user.tpl',
      1 => 1543035802,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '18282912765bf8ee865d9354-62166290',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'params' => 0,
    'LIVESTREET_SECURITY_KEY' => 0,
    'oUserCurrent' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5bf8ee865fc434_29254024',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5bf8ee865fc434_29254024')) {function content_5bf8ee865fc434_29254024($_smarty_tpl) {?><?php if (!is_callable('smarty_function_lang')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.lang.php';
if (!is_callable('smarty_function_router')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.router.php';
if (!is_callable('smarty_function_component')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component.php';
?>
<?php if (is_array($_smarty_tpl->tpl_vars['params']->value['items'])){?>
    <?php ob_start();?><?php echo smarty_function_lang(array('name'=>'auth.logout'),$_smarty_tpl);?>
<?php $_tmp1=ob_get_clean();?><?php ob_start();?><?php echo smarty_function_router(array('page'=>'auth'),$_smarty_tpl);?>
<?php $_tmp2=ob_get_clean();?><?php $_smarty_tpl->createLocalArrayVariable('params', null, 0);
$_smarty_tpl->tpl_vars['params']->value['items'][] = array('name'=>'logout','text'=>$_tmp1,'url'=>$_tmp2."logout/?security_ls_key=".((string)$_smarty_tpl->tpl_vars['LIVESTREET_SECURITY_KEY']->value));?>
<?php }?>
 
<?php echo smarty_function_component(array('_default_short'=>'nav','classes'=>'ls-userbar-user-nav-menu js-userbar-user-nav-menu','hook'=>'user','hookParams'=>array('user'=>$_smarty_tpl->tpl_vars['oUserCurrent']->value),'params'=>$_smarty_tpl->tpl_vars['params']->value),$_smarty_tpl);?>

<?php }} ?>