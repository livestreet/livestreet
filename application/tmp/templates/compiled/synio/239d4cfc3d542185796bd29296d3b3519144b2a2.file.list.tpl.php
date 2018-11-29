<?php /* Smarty version Smarty-3.1.13, created on 2018-11-24 09:24:24
         compiled from "/var/www/ls.new/application/plugins/admin/frontend/components/p-skin/list.tpl" */ ?>
<?php /*%%SmartyHeaderCode:17697865905bf8ee98ed4930-82296550%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '239d4cfc3d542185796bd29296d3b3519144b2a2' => 
    array (
      0 => '/var/www/ls.new/application/plugins/admin/frontend/components/p-skin/list.tpl',
      1 => 1543039194,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '17697865905bf8ee98ed4930-82296550',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'skins' => 0,
    'skin' => 0,
    'updates' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5bf8ee98ef11b0_92878298',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5bf8ee98ef11b0_92878298')) {function content_5bf8ee98ef11b0_92878298($_smarty_tpl) {?><?php if (!is_callable('smarty_function_component_define_params')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component_define_params.php';
if (!is_callable('smarty_function_component')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component.php';
?>

<?php echo smarty_function_component_define_params(array('params'=>array('skins','pagination','updates','type')),$_smarty_tpl);?>


<?php if ($_smarty_tpl->tpl_vars['skins']->value){?>
    <div class="ls-skin-list">
        <?php  $_smarty_tpl->tpl_vars['skin'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['skin']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['skins']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['skin']->key => $_smarty_tpl->tpl_vars['skin']->value){
$_smarty_tpl->tpl_vars['skin']->_loop = true;
?>
            <?php echo smarty_function_component(array('_default_short'=>'admin:p-skin','skin'=>$_smarty_tpl->tpl_vars['skin']->value,'updates'=>$_smarty_tpl->tpl_vars['updates']->value),$_smarty_tpl);?>

        <?php } ?>
    </div>
<?php }?><?php }} ?>