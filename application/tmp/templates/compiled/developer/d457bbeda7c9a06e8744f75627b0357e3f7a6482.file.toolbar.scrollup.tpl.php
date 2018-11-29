<?php /* Smarty version Smarty-3.1.13, created on 2018-11-24 09:24:31
         compiled from "/var/www/ls.new/application/frontend/components/toolbar-scrollup/toolbar.scrollup.tpl" */ ?>
<?php /*%%SmartyHeaderCode:4482529685bf8ee9fd05e18-89867873%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd457bbeda7c9a06e8744f75627b0357e3f7a6482' => 
    array (
      0 => '/var/www/ls.new/application/frontend/components/toolbar-scrollup/toolbar.scrollup.tpl',
      1 => 1543035802,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '4482529685bf8ee9fd05e18-89867873',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5bf8ee9fd120f0_39671257',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5bf8ee9fd120f0_39671257')) {function content_5bf8ee9fd120f0_39671257($_smarty_tpl) {?><?php if (!is_callable('smarty_function_lang')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.lang.php';
if (!is_callable('smarty_function_component')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component.php';
?>

<?php ob_start();?><?php echo smarty_function_lang(array('_default_short'=>'toolbar.scrollup.title'),$_smarty_tpl);?>
<?php $_tmp1=ob_get_clean();?><?php echo smarty_function_component(array('_default_short'=>'toolbar.item','icon'=>'chevron-up','classes'=>'js-toolbar-scrollup','mods'=>'scrollup','attributes'=>array('title'=>$_tmp1)),$_smarty_tpl);?>
<?php }} ?>