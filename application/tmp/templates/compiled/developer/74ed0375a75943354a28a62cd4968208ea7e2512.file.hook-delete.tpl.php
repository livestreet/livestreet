<?php /* Smarty version Smarty-3.1.13, created on 2018-11-25 11:43:30
         compiled from "/var/www/ls.new/application/plugins/admin/frontend/components/p-comment/hook-delete.tpl" */ ?>
<?php /*%%SmartyHeaderCode:20275959395bfa60b2524090-11866022%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '74ed0375a75943354a28a62cd4968208ea7e2512' => 
    array (
      0 => '/var/www/ls.new/application/plugins/admin/frontend/components/p-comment/hook-delete.tpl',
      1 => 1543039194,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '20275959395bfa60b2524090-11866022',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'oUserCurrent' => 0,
    'comment' => 0,
    'aLang' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5bfa60b2554d65_20474277',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5bfa60b2554d65_20474277')) {function content_5bfa60b2554d65_20474277($_smarty_tpl) {?><?php if (!is_callable('smarty_function_component_define_params')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component_define_params.php';
if (!is_callable('smarty_function_router')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.router.php';
if (!is_callable('smarty_function_component')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component.php';
?>

<?php echo smarty_function_component_define_params(array('params'=>array('comment')),$_smarty_tpl);?>


<?php if ($_smarty_tpl->tpl_vars['oUserCurrent']->value&&$_smarty_tpl->tpl_vars['oUserCurrent']->value->isAdministrator()){?>
    <?php ob_start();?><?php echo smarty_function_router(array('page'=>'admin/comments/delete'),$_smarty_tpl);?>
<?php $_tmp1=ob_get_clean();?><?php echo smarty_function_component(array('_default_short'=>'comment.actions-item','link'=>array('url'=>$_tmp1."?id=".((string)$_smarty_tpl->tpl_vars['comment']->value->getId()),'attributes'=>array('target'=>'_blank')),'text'=>$_smarty_tpl->tpl_vars['aLang']->value['plugin']['admin']['comments']['full_deleting']),$_smarty_tpl);?>

<?php }?>
<?php }} ?>