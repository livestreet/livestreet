<?php /* Smarty version Smarty-3.1.13, created on 2018-11-25 11:43:08
         compiled from "/var/www/ls.new/application/frontend/components/media/panes/pane.insert.tpl" */ ?>
<?php /*%%SmartyHeaderCode:5574261895bfa609c499950-07316465%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '904eac1d778d27e837723b0f1b3552383f9da062' => 
    array (
      0 => '/var/www/ls.new/application/frontend/components/media/panes/pane.insert.tpl',
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
  'nocache_hash' => '5574261895bfa609c499950-07316465',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5bfa609c5b1054_85303292',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5bfa609c5b1054_85303292')) {function content_5bfa609c5b1054_85303292($_smarty_tpl) {?><?php if (!is_callable('smarty_function_component_define_params')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component_define_params.php';
if (!is_callable('smarty_function_component')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component.php';
if (!is_callable('smarty_function_lang')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.lang.php';
?>
    <?php echo smarty_function_component_define_params(array('params'=>array('id')),$_smarty_tpl);?>


    <?php $_smarty_tpl->tpl_vars['id'] = new Smarty_variable('tab-media-insert', null, 0);?>


<div class="ls-media-pane-content js-media-pane-content">
    
    <?php echo smarty_function_component(array('_default_short'=>'media','template'=>'uploader','attributes'=>array('id'=>'media-uploader'),'classes'=>'js-media-uploader','targetParams'=>$_smarty_tpl->tpl_vars['aTargetParams']->value,'targetType'=>$_smarty_tpl->tpl_vars['sMediaTargetType']->value,'targetId'=>$_smarty_tpl->tpl_vars['sMediaTargetId']->value,'targetTmp'=>$_smarty_tpl->tpl_vars['sMediaTargetTmp']->value),$_smarty_tpl);?>


</div>

<div class="ls-media-pane-footer">
    
    <?php ob_start();?><?php echo smarty_function_lang(array('name'=>'media.insert.submit'),$_smarty_tpl);?>
<?php $_tmp1=ob_get_clean();?><?php echo smarty_function_component(array('_default_short'=>'button','mods'=>'primary','classes'=>'js-media-insert-submit','text'=>$_tmp1),$_smarty_tpl);?>


</div><?php }} ?>