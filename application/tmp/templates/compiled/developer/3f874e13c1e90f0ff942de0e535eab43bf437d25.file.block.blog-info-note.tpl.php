<?php /* Smarty version Smarty-3.1.13, created on 2018-11-25 11:43:06
         compiled from "/var/www/ls.new/application/frontend/components/blog/blocks/block.blog-info-note.tpl" */ ?>
<?php /*%%SmartyHeaderCode:4942815095bfa609a8824a6-85567289%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3f874e13c1e90f0ff942de0e535eab43bf437d25' => 
    array (
      0 => '/var/www/ls.new/application/frontend/components/blog/blocks/block.blog-info-note.tpl',
      1 => 1543035802,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '4942815095bfa609a8824a6-85567289',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5bfa609a8a9d74_07351299',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5bfa609a8a9d74_07351299')) {function content_5bfa609a8a9d74_07351299($_smarty_tpl) {?><?php if (!is_callable('smarty_function_lang')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.lang.php';
if (!is_callable('smarty_function_component')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component.php';
?>

<?php ob_start();?><?php echo smarty_function_lang(array('_default_short'=>'topic.blocks.tip.title'),$_smarty_tpl);?>
<?php $_tmp1=ob_get_clean();?><?php ob_start();?><?php echo smarty_function_lang(array('_default_short'=>'topic.blocks.tip.text'),$_smarty_tpl);?>
<?php $_tmp2=ob_get_clean();?><?php echo smarty_function_component(array('_default_short'=>'block','mods'=>'info','title'=>$_tmp1,'content'=>$_tmp2),$_smarty_tpl);?>
<?php }} ?>