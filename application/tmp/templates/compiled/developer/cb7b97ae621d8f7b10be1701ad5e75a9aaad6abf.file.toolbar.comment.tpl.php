<?php /* Smarty version Smarty-3.1.13, created on 2018-11-25 11:43:23
         compiled from "/var/www/ls.new/application/frontend/components/comment/toolbar.comment.tpl" */ ?>
<?php /*%%SmartyHeaderCode:17989151635bfa60abc95b11-89885601%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'cb7b97ae621d8f7b10be1701ad5e75a9aaad6abf' => 
    array (
      0 => '/var/www/ls.new/application/frontend/components/comment/toolbar.comment.tpl',
      1 => 1543035802,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '17989151635bfa60abc95b11-89885601',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5bfa60abd13a69_98924751',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5bfa60abd13a69_98924751')) {function content_5bfa60abd13a69_98924751($_smarty_tpl) {?><?php if (!is_callable('smarty_function_lang')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.lang.php';
if (!is_callable('smarty_function_component')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component.php';
?>

<?php $_smarty_tpl->_capture_stack[0][] = array('toolbar_comments', null, null); ob_start(); ?>
    <div class="ls-comments-toolbar-update js-toolbar-comments-update" title="<?php echo smarty_function_lang(array('_default_short'=>'comments.update'),$_smarty_tpl);?>
">
        <?php echo smarty_function_component(array('_default_short'=>'icon','icon'=>'refresh'),$_smarty_tpl);?>

    </div>
    <div class="ls-comments-toolbar-count js-toolbar-comments-count" title="<?php echo smarty_function_lang(array('_default_short'=>'comments.count_new'),$_smarty_tpl);?>
">0</div>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>

<?php echo smarty_function_component(array('_default_short'=>'toolbar.item','html'=>Smarty::$_smarty_vars['capture']['toolbar_comments'],'classes'=>'js-comments-toolbar','mods'=>'comments'),$_smarty_tpl);?>
<?php }} ?>