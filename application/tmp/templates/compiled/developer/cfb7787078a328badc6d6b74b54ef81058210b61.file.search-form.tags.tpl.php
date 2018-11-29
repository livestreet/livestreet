<?php /* Smarty version Smarty-3.1.13, created on 2018-11-24 09:24:31
         compiled from "/var/www/ls.new/framework/frontend/components/tags/search-form.tags.tpl" */ ?>
<?php /*%%SmartyHeaderCode:14516763645bf8ee9f501354-00500737%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'cfb7787078a328badc6d6b74b54ef81058210b61' => 
    array (
      0 => '/var/www/ls.new/framework/frontend/components/tags/search-form.tags.tpl',
      1 => 1543037260,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '14516763645bf8ee9f501354-00500737',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'mods' => 0,
    'tag' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5bf8ee9f518a17_42075775',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5bf8ee9f518a17_42075775')) {function content_5bf8ee9f518a17_42075775($_smarty_tpl) {?><?php if (!is_callable('smarty_function_component_define_params')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component_define_params.php';
if (!is_callable('smarty_function_lang')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.lang.php';
if (!is_callable('smarty_function_component')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component.php';
?>

<?php echo smarty_function_component_define_params(array('params'=>array('mods','classes','attributes')),$_smarty_tpl);?>


<?php ob_start();?><?php echo smarty_function_lang(array('_default_short'=>'tags.search.label'),$_smarty_tpl);?>
<?php $_tmp1=ob_get_clean();?><?php echo smarty_function_component(array('_default_short'=>'search-form','name'=>'tags','mods'=>$_smarty_tpl->tpl_vars['mods']->value,'placeholder'=>$_tmp1,'classes'=>'js-tag-search-form','inputClasses'=>'autocomplete-tags js-tag-search','inputName'=>'tag','value'=>$_smarty_tpl->tpl_vars['tag']->value),$_smarty_tpl);?>
<?php }} ?>