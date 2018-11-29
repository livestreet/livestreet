<?php /* Smarty version Smarty-3.1.13, created on 2018-11-29 11:18:27
         compiled from "/var/www/ls.new/application/plugins/admin/frontend/components/p-user/profile-section.tpl" */ ?>
<?php /*%%SmartyHeaderCode:17453651295bffa0d35f45e5-11679235%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b1fbddd0ce03616b9494590ec05704ba613d5823' => 
    array (
      0 => '/var/www/ls.new/application/plugins/admin/frontend/components/p-user/profile-section.tpl',
      1 => 1543039194,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '17453651295bffa0d35f45e5-11679235',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'component' => 0,
    'mods' => 0,
    'classes' => 0,
    'attributes' => 0,
    'title' => 0,
    'content' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5bffa0d3619058_62991610',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5bffa0d3619058_62991610')) {function content_5bffa0d3619058_62991610($_smarty_tpl) {?><?php if (!is_callable('smarty_function_component_define_params')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component_define_params.php';
if (!is_callable('smarty_function_cmods')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.cmods.php';
if (!is_callable('smarty_function_cattr')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.cattr.php';
?><?php $_smarty_tpl->tpl_vars['component'] = new Smarty_variable('p-user-profile-section', null, 0);?>
<?php echo smarty_function_component_define_params(array('params'=>array('title','content','mods','classes','attributes')),$_smarty_tpl);?>


<div class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
 <?php echo smarty_function_cmods(array('name'=>$_smarty_tpl->tpl_vars['component']->value,'mods'=>$_smarty_tpl->tpl_vars['mods']->value),$_smarty_tpl);?>
 <?php echo $_smarty_tpl->tpl_vars['classes']->value;?>
" <?php echo smarty_function_cattr(array('list'=>$_smarty_tpl->tpl_vars['attributes']->value),$_smarty_tpl);?>
>
    <h2 class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-title"><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</h2>

    <div class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-body">
        <?php echo $_smarty_tpl->tpl_vars['content']->value;?>

    </div>
</div><?php }} ?>