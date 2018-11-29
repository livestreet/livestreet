<?php /* Smarty version Smarty-3.1.13, created on 2018-11-25 11:43:09
         compiled from "/var/www/ls.new/application/frontend/components/media/panes/pane.photoset.tpl" */ ?>
<?php /*%%SmartyHeaderCode:6052713065bfa609d8b1728-33513423%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'bbd056b10f4db5be8bde0af3abdae90c5d7d2818' => 
    array (
      0 => '/var/www/ls.new/application/frontend/components/media/panes/pane.photoset.tpl',
      1 => 1543035802,
      2 => 'file',
    ),
    '770f9a32e7d499024a73f6355f882b86af90f296' => 
    array (
      0 => '/var/www/ls.new/application/frontend/components/media/panes/pane.tpl',
      1 => 1543035802,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '6052713065bfa609d8b1728-33513423',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5bfa609d8fbba0_28523923',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5bfa609d8fbba0_28523923')) {function content_5bfa609d8fbba0_28523923($_smarty_tpl) {?><?php if (!is_callable('smarty_function_component_define_params')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component_define_params.php';
if (!is_callable('smarty_function_lang')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.lang.php';
if (!is_callable('smarty_function_component')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component.php';
?>
    <?php echo smarty_function_component_define_params(array('params'=>array('id')),$_smarty_tpl);?>


    <?php $_smarty_tpl->tpl_vars['id'] = new Smarty_variable('tab-media-photoset', null, 0);?>


<div class="ls-media-pane-content js-media-pane-content">
    
</div>

<div class="ls-media-pane-footer">
    
    <?php ob_start();?><?php echo smarty_function_lang(array('name'=>'media.photoset.submit'),$_smarty_tpl);?>
<?php $_tmp1=ob_get_clean();?><?php echo smarty_function_component(array('_default_short'=>'button','mods'=>'primary','classes'=>'js-media-photoset-submit','text'=>$_tmp1),$_smarty_tpl);?>


</div><?php }} ?>