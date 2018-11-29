<?php /* Smarty version Smarty-3.1.13, created on 2018-11-25 11:43:22
         compiled from "/var/www/ls.new/application/frontend/components/poll/poll.list.tpl" */ ?>
<?php /*%%SmartyHeaderCode:19272796035bfa60aaea2609-64970517%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0a7cc5abe8d703c6ca667b9d2b5cb4b30b1c8ecd' => 
    array (
      0 => '/var/www/ls.new/application/frontend/components/poll/poll.list.tpl',
      1 => 1543035802,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '19272796035bfa60aaea2609-64970517',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'polls' => 0,
    'poll' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5bfa60aaeecff9_94148793',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5bfa60aaeecff9_94148793')) {function content_5bfa60aaeecff9_94148793($_smarty_tpl) {?><?php if (!is_callable('smarty_function_component')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component.php';
?>

<?php  $_smarty_tpl->tpl_vars['poll'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['poll']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['polls']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['poll']->key => $_smarty_tpl->tpl_vars['poll']->value){
$_smarty_tpl->tpl_vars['poll']->_loop = true;
?>
    <?php echo smarty_function_component(array('_default_short'=>'poll','poll'=>$_smarty_tpl->tpl_vars['poll']->value),$_smarty_tpl);?>

<?php } ?><?php }} ?>