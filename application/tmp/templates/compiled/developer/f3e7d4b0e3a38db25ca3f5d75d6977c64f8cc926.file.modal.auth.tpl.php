<?php /* Smarty version Smarty-3.1.13, created on 2018-11-25 13:37:06
         compiled from "/var/www/ls.new/application/frontend/components/auth/modal.auth.tpl" */ ?>
<?php /*%%SmartyHeaderCode:81404475bfa7b5255c387-24886358%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f3e7d4b0e3a38db25ca3f5d75d6977c64f8cc926' => 
    array (
      0 => '/var/www/ls.new/application/frontend/components/auth/modal.auth.tpl',
      1 => 1543035802,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '81404475bfa7b5255c387-24886358',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'auth_tab_reg' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5bfa7b525e6972_07031780',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5bfa7b525e6972_07031780')) {function content_5bfa7b525e6972_07031780($_smarty_tpl) {?><?php if (!is_callable('smarty_function_component')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component.php';
if (!is_callable('smarty_function_lang')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.lang.php';
?>

<?php if (!Config::Get('general.reg.invite')){?>
    <?php echo smarty_function_component(array('_default_short'=>'auth','template'=>'registration','assign'=>'auth_tab_reg'),$_smarty_tpl);?>

<?php }else{ ?>
    <?php echo smarty_function_component(array('_default_short'=>'auth','template'=>'invite','assign'=>'auth_tab_reg'),$_smarty_tpl);?>

<?php }?>

<?php ob_start();?><?php echo smarty_function_lang(array('_default_short'=>'auth.authorization'),$_smarty_tpl);?>
<?php $_tmp1=ob_get_clean();?><?php ob_start();?><?php echo smarty_function_lang(array('_default_short'=>'auth.login.title'),$_smarty_tpl);?>
<?php $_tmp2=ob_get_clean();?><?php ob_start();?><?php echo smarty_function_component(array('_default_short'=>'auth','template'=>'login'),$_smarty_tpl);?>
<?php $_tmp3=ob_get_clean();?><?php ob_start();?><?php echo smarty_function_lang(array('_default_short'=>'auth.registration.title'),$_smarty_tpl);?>
<?php $_tmp4=ob_get_clean();?><?php ob_start();?><?php echo smarty_function_lang(array('_default_short'=>'auth.reset.title'),$_smarty_tpl);?>
<?php $_tmp5=ob_get_clean();?><?php ob_start();?><?php echo smarty_function_component(array('_default_short'=>'auth','template'=>'reset'),$_smarty_tpl);?>
<?php $_tmp6=ob_get_clean();?><?php echo smarty_function_component(array('_default_short'=>'modal','title'=>$_tmp1,'options'=>array('center'=>'false'),'showFooter'=>false,'classes'=>'js-modal-default','mods'=>'auth','id'=>'modal-login','tabs'=>array('tabs'=>array(array('text'=>$_tmp2,'content'=>$_tmp3,'classes'=>'js-auth-tab-login'),array('text'=>$_tmp4,'content'=>$_smarty_tpl->tpl_vars['auth_tab_reg']->value,'classes'=>'js-auth-tab-reg'),array('text'=>$_tmp5,'content'=>$_tmp6)))),$_smarty_tpl);?>
<?php }} ?>