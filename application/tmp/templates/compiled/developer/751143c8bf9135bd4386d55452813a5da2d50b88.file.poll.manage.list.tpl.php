<?php /* Smarty version Smarty-3.1.13, created on 2018-11-25 11:43:09
         compiled from "/var/www/ls.new/application/frontend/components/poll/poll.manage.list.tpl" */ ?>
<?php /*%%SmartyHeaderCode:15592695275bfa609de179b8-50422011%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '751143c8bf9135bd4386d55452813a5da2d50b88' => 
    array (
      0 => '/var/www/ls.new/application/frontend/components/poll/poll.manage.list.tpl',
      1 => 1543035802,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '15592695275bfa609de179b8-50422011',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'aPollItems' => 0,
    'poll' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5bfa609dee29f3_24343756',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5bfa609dee29f3_24343756')) {function content_5bfa609dee29f3_24343756($_smarty_tpl) {?><?php if (!is_callable('smarty_function_component')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component.php';
?>

<ul class="ls-poll-manage-list js-poll-manage-list">
    <?php if ($_smarty_tpl->tpl_vars['aPollItems']->value){?>
        <?php  $_smarty_tpl->tpl_vars['poll'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['poll']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['aPollItems']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['poll']->key => $_smarty_tpl->tpl_vars['poll']->value){
$_smarty_tpl->tpl_vars['poll']->_loop = true;
?>
            <?php echo smarty_function_component(array('_default_short'=>'poll','template'=>'manage.item','poll'=>$_smarty_tpl->tpl_vars['poll']->value),$_smarty_tpl);?>

        <?php } ?>
    <?php }?>
</ul><?php }} ?>