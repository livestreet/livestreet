<?php /* Smarty version Smarty-3.1.13, created on 2018-11-24 09:24:31
         compiled from "/var/www/ls.new/application/frontend/components/search-form/search-form.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1691922895bf8ee9f51eec1-02095831%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c62d76bad7cb715e2f1b528f6ed5ead9c0948243' => 
    array (
      0 => '/var/www/ls.new/application/frontend/components/search-form/search-form.tpl',
      1 => 1543035802,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1691922895bf8ee9f51eec1-02095831',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'action' => 0,
    'method' => 0,
    'component' => 0,
    'mods' => 0,
    'classes' => 0,
    'attributes' => 0,
    'placeholder' => 0,
    'aLang' => 0,
    'note' => 0,
    'value' => 0,
    'inputClasses' => 0,
    'inputAttributes' => 0,
    'inputName' => 0,
    'noSubmitButton' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5bf8ee9f58f371_21016620',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5bf8ee9f58f371_21016620')) {function content_5bf8ee9f58f371_21016620($_smarty_tpl) {?><?php if (!is_callable('smarty_function_component_define_params')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component_define_params.php';
if (!is_callable('smarty_function_cmods')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.cmods.php';
if (!is_callable('smarty_function_cattr')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.cattr.php';
if (!is_callable('smarty_function_component')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component.php';
?>


<?php $_smarty_tpl->tpl_vars['component'] = new Smarty_variable('ls-search-form', null, 0);?>
<?php echo smarty_function_component_define_params(array('params'=>array('action','method','placeholder','placeholder','note','value','inputClasses','inputAttributes','inputName','noSubmitButton','mods','classes','attributes')),$_smarty_tpl);?>


<form action="<?php echo $_smarty_tpl->tpl_vars['action']->value;?>
" method="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['method']->value)===null||$tmp==='' ? 'get' : $tmp);?>
" class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
 <?php echo smarty_function_cmods(array('name'=>$_smarty_tpl->tpl_vars['component']->value,'mods'=>$_smarty_tpl->tpl_vars['mods']->value),$_smarty_tpl);?>
 <?php echo $_smarty_tpl->tpl_vars['classes']->value;?>
" <?php echo smarty_function_cattr(array('list'=>$_smarty_tpl->tpl_vars['attributes']->value),$_smarty_tpl);?>
>
    
        <?php echo smarty_function_component(array('_default_short'=>'field','template'=>'text','placeholder'=>$_smarty_tpl->tpl_vars['placeholder']->value ? $_smarty_tpl->tpl_vars['placeholder']->value : $_smarty_tpl->tpl_vars['aLang']->value['search']['search'],'note'=>$_smarty_tpl->tpl_vars['note']->value,'value'=>$_smarty_tpl->tpl_vars['value']->value,'inputClasses'=>((string)$_smarty_tpl->tpl_vars['component']->value)."-input ".((string)$_smarty_tpl->tpl_vars['inputClasses']->value),'inputAttributes'=>$_smarty_tpl->tpl_vars['inputAttributes']->value,'name'=>(($tmp = @$_smarty_tpl->tpl_vars['inputName']->value)===null||$tmp==='' ? 'q' : $tmp)),$_smarty_tpl);?>


        <?php if (!$_smarty_tpl->tpl_vars['noSubmitButton']->value){?>
            <?php echo smarty_function_component(array('_default_short'=>'button','mods'=>'icon','classes'=>((string)$_smarty_tpl->tpl_vars['component']->value)."-submit",'icon'=>'search'),$_smarty_tpl);?>

        <?php }?>
    
</form><?php }} ?>