<?php /* Smarty version Smarty-3.1.13, created on 2018-11-24 09:24:24
         compiled from "/var/www/ls.new/application/frontend/skin/synio/components/dropdown/dropdown.tpl" */ ?>
<?php /*%%SmartyHeaderCode:7647852475bf8ee98e2dee2-96056781%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4bf5a1268a64c8a4f515bd6fc12d482ac6384214' => 
    array (
      0 => '/var/www/ls.new/application/frontend/skin/synio/components/dropdown/dropdown.tpl',
      1 => 1543035802,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '7647852475bf8ee98e2dee2-96056781',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'text' => 0,
    'component' => 0,
    'mods' => 0,
    'classes' => 0,
    'attributes' => 0,
    'isSplit' => 0,
    'icon' => 0,
    'activeItem' => 0,
    'menu' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5bf8ee98e9f494_06397215',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5bf8ee98e9f494_06397215')) {function content_5bf8ee98e9f494_06397215($_smarty_tpl) {?><?php if (!is_callable('smarty_function_component_define_params')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component_define_params.php';
if (!is_callable('smarty_function_cmods')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.cmods.php';
if (!is_callable('smarty_function_cattr')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.cattr.php';
if (!is_callable('smarty_function_component')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component.php';
?>


<?php $_smarty_tpl->tpl_vars['component'] = new Smarty_variable('ls-dropdown', null, 0);?>
<?php echo smarty_function_component_define_params(array('params'=>array('text','icon','activeItem','isSplit','menu','mods','classes','attributes')),$_smarty_tpl);?>


<?php if (!$_smarty_tpl->tpl_vars['text']->value){?>
    <?php $_smarty_tpl->tpl_vars['mods'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['mods']->value)." no-text", null, 0);?>
<?php }?>



<div class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
 <?php echo smarty_function_cmods(array('name'=>$_smarty_tpl->tpl_vars['component']->value,'mods'=>$_smarty_tpl->tpl_vars['mods']->value),$_smarty_tpl);?>
 <?php echo $_smarty_tpl->tpl_vars['classes']->value;?>
 ls-nav--dropdown" <?php echo smarty_function_cattr(array('list'=>$_smarty_tpl->tpl_vars['attributes']->value),$_smarty_tpl);?>
>
    
    <?php if ($_smarty_tpl->tpl_vars['isSplit']->value){?>
        <?php ob_start();?><?php echo smarty_function_component(array('_default_short'=>'button','type'=>'button','classes'=>((string)$_smarty_tpl->tpl_vars['component']->value)."-toggle js-".((string)$_smarty_tpl->tpl_vars['component']->value)."-toggle",'mods'=>((string)$_smarty_tpl->tpl_vars['mods']->value)." no-text",'attributes'=>array_merge((($tmp = @$_smarty_tpl->tpl_vars['attributes']->value)===null||$tmp==='' ? array() : $tmp),array('aria-haspopup'=>'true','aria-expanded'=>'false'))),$_smarty_tpl);?>
<?php $_tmp1=ob_get_clean();?><?php echo smarty_function_component(array('_default_short'=>'button','template'=>'group','buttons'=>array(array('text'=>$_smarty_tpl->tpl_vars['text']->value,'mods'=>$_smarty_tpl->tpl_vars['mods']->value,'attributes'=>array('tabindex'=>-1)),$_tmp1)),$_smarty_tpl);?>

    <?php }else{ ?>
        <?php echo smarty_function_component(array('_default_short'=>'button','type'=>'button','classes'=>((string)$_smarty_tpl->tpl_vars['component']->value)."-toggle js-".((string)$_smarty_tpl->tpl_vars['component']->value)."-toggle",'mods'=>$_smarty_tpl->tpl_vars['mods']->value,'text'=>$_smarty_tpl->tpl_vars['text']->value,'icon'=>$_smarty_tpl->tpl_vars['icon']->value,'attributes'=>array_merge((($tmp = @$_smarty_tpl->tpl_vars['attributes']->value)===null||$tmp==='' ? array() : $tmp),array('aria-haspopup'=>'true','aria-expanded'=>'false'))),$_smarty_tpl);?>

    <?php }?>

    
    <?php echo smarty_function_component(array('_default_short'=>'dropdown','template'=>'menu','activeItem'=>$_smarty_tpl->tpl_vars['activeItem']->value,'items'=>$_smarty_tpl->tpl_vars['menu']->value),$_smarty_tpl);?>

</div>
<?php }} ?>