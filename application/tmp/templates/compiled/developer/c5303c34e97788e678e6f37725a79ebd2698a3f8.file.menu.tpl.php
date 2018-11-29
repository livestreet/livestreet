<?php /* Smarty version Smarty-3.1.13, created on 2018-11-24 09:24:29
         compiled from "/var/www/ls.new/application/plugins/admin/frontend/components/p-menu/menu.tpl" */ ?>
<?php /*%%SmartyHeaderCode:17566165705bf8ee9d4ea121-50362834%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c5303c34e97788e678e6f37725a79ebd2698a3f8' => 
    array (
      0 => '/var/www/ls.new/application/plugins/admin/frontend/components/p-menu/menu.tpl',
      1 => 1543039194,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '17566165705bf8ee9d4ea121-50362834',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'component' => 0,
    'mods' => 0,
    'classes' => 0,
    'attributes' => 0,
    'menu' => 0,
    'section' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5bf8ee9d57efe9_50881924',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5bf8ee9d57efe9_50881924')) {function content_5bf8ee9d57efe9_50881924($_smarty_tpl) {?><?php if (!is_callable('smarty_function_component_define_params')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component_define_params.php';
if (!is_callable('smarty_function_cmods')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.cmods.php';
if (!is_callable('smarty_function_cattr')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.cattr.php';
if (!is_callable('smarty_function_component')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component.php';
?>

<?php $_smarty_tpl->tpl_vars['component'] = new Smarty_variable('p-menu', null, 0);?>
<?php echo smarty_function_component_define_params(array('params'=>array('menu')),$_smarty_tpl);?>


<div class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
 <?php echo smarty_function_cmods(array('name'=>$_smarty_tpl->tpl_vars['component']->value,'mods'=>$_smarty_tpl->tpl_vars['mods']->value),$_smarty_tpl);?>
 <?php echo $_smarty_tpl->tpl_vars['classes']->value;?>
 js-menu" <?php echo smarty_function_cattr(array('list'=>$_smarty_tpl->tpl_vars['attributes']->value),$_smarty_tpl);?>
>
    <div class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-toggle js-menu-mobile-toggle">
        Навигация
    </div>

    <ul class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-nav js-menu-nav">
        
        <?php  $_smarty_tpl->tpl_vars['section'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['section']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['menu']->value->GetSections(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['section']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['section']->key => $_smarty_tpl->tpl_vars['section']->value){
$_smarty_tpl->tpl_vars['section']->_loop = true;
 $_smarty_tpl->tpl_vars['section']->index++;
?>
            <?php if ($_smarty_tpl->tpl_vars['section']->value->getName()!='addition'){?>
                <?php echo smarty_function_component(array('_default_short'=>'admin:p-menu.section','section'=>$_smarty_tpl->tpl_vars['section']->value,'uid'=>"c".((string)$_smarty_tpl->tpl_vars['section']->index)),$_smarty_tpl);?>

            <?php }?>
        <?php } ?>

        
        <?php if (!isset($_smarty_tpl->tpl_vars['section'])) $_smarty_tpl->tpl_vars['section'] = new Smarty_Variable(null);if ($_smarty_tpl->tpl_vars['section']->value = $_smarty_tpl->tpl_vars['menu']->value->GetSection('addition')){?>
            <?php echo smarty_function_component(array('_default_short'=>'admin:p-menu.section','section'=>$_smarty_tpl->tpl_vars['section']->value,'uid'=>"a".((string)$_smarty_tpl->tpl_vars['section']->index)),$_smarty_tpl);?>

        <?php }?>

        
        <div class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-section <?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-section--fold">
            <a href="#" class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-section-item js-menu-fold">
                <i class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-section-icon <?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-section-icon-custom <?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-section-icon-custom--fold"></i>
                <span class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-section-text">Свернуть меню</span>
            </a>
        </div>
    </ul>
</div><?php }} ?>