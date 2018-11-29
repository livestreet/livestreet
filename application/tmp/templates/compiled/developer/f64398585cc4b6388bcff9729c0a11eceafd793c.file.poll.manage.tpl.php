<?php /* Smarty version Smarty-3.1.13, created on 2018-11-25 11:43:09
         compiled from "/var/www/ls.new/application/frontend/components/poll/poll.manage.tpl" */ ?>
<?php /*%%SmartyHeaderCode:254830325bfa609dc892f1-72602485%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f64398585cc4b6388bcff9729c0a11eceafd793c' => 
    array (
      0 => '/var/www/ls.new/application/frontend/components/poll/poll.manage.tpl',
      1 => 1543035802,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '254830325bfa609dc892f1-72602485',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'targetType' => 0,
    'targetId' => 0,
    'aLang' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5bfa609dd79956_40402683',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5bfa609dd79956_40402683')) {function content_5bfa609dd79956_40402683($_smarty_tpl) {?><?php if (!is_callable('smarty_function_component_define_params')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component_define_params.php';
if (!is_callable('smarty_function_component')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component.php';
if (!is_callable('smarty_insert_block')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/insert.block.php';
?>

<?php echo smarty_function_component_define_params(array('params'=>array('targetId','targetType')),$_smarty_tpl);?>


<div class="fieldset ls-poll-manage js-poll-manage" data-type="<?php echo $_smarty_tpl->tpl_vars['targetType']->value;?>
" data-target-id="<?php echo $_smarty_tpl->tpl_vars['targetId']->value;?>
">
    <header class="fieldset-header">
        <h3 class="fieldset-title"><?php echo $_smarty_tpl->tpl_vars['aLang']->value['poll']['polls'];?>
</h3>
    </header>

    <div class="fieldset-body">
        
        <?php echo smarty_function_component(array('_default_short'=>'button','text'=>$_smarty_tpl->tpl_vars['aLang']->value['common']['add'],'type'=>'button','classes'=>'ls-poll-manage-add js-poll-manage-add'),$_smarty_tpl);?>


        
        <?php echo smarty_insert_block(array('block' => "pollFormItems", 'params' => array('target_type'=>$_smarty_tpl->tpl_vars['targetType']->value,'target_id'=>$_smarty_tpl->tpl_vars['targetId']->value)),$_smarty_tpl);?>

    </div>
</div><?php }} ?>