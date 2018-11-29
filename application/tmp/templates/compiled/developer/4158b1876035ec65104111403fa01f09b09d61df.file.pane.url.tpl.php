<?php /* Smarty version Smarty-3.1.13, created on 2018-11-25 11:43:09
         compiled from "/var/www/ls.new/application/frontend/components/media/panes/pane.url.tpl" */ ?>
<?php /*%%SmartyHeaderCode:11626974965bfa609d902c99-34034212%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4158b1876035ec65104111403fa01f09b09d61df' => 
    array (
      0 => '/var/www/ls.new/application/frontend/components/media/panes/pane.url.tpl',
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
  'nocache_hash' => '11626974965bfa609d902c99-34034212',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5bfa609d951626_46112234',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5bfa609d951626_46112234')) {function content_5bfa609d951626_46112234($_smarty_tpl) {?><?php if (!is_callable('smarty_function_component_define_params')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component_define_params.php';
if (!is_callable('smarty_function_lang')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.lang.php';
if (!is_callable('smarty_function_component')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component.php';
?>
    <?php echo smarty_function_component_define_params(array('params'=>array('id')),$_smarty_tpl);?>


    <?php $_smarty_tpl->tpl_vars['id'] = new Smarty_variable('tab-media-url', null, 0);?>


<div class="ls-media-pane-content js-media-pane-content">
    
    <form method="post" action="" enctype="multipart/form-data" class="ls-mb-20 js-media-url-form">
        
        
        

        
        <?php ob_start();?><?php echo smarty_function_lang(array('_default_short'=>'media.url.fields.url.label'),$_smarty_tpl);?>
<?php $_tmp1=ob_get_clean();?><?php echo smarty_function_component(array('_default_short'=>'field','template'=>'text','name'=>'url','inputClasses'=>'js-media-url-form-url','label'=>$_tmp1),$_smarty_tpl);?>

    </form>

    <div class="ls-mb-15 js-media-url-image-preview" style="display: none"></div>

    <div class="js-media-url-settings-blocks">
        <?php echo smarty_function_component(array('_default_short'=>'media','template'=>'uploader-block.insert.image','useSizes'=>false),$_smarty_tpl);?>

    </div>

</div>

<div class="ls-media-pane-footer">
    
    <?php ob_start();?><?php echo smarty_function_lang(array('_default_short'=>'media.url.submit_insert'),$_smarty_tpl);?>
<?php $_tmp1=ob_get_clean();?><?php echo smarty_function_component(array('_default_short'=>'button','mods'=>'primary','classes'=>'js-media-url-submit-insert','text'=>$_tmp1),$_smarty_tpl);?>


    <?php ob_start();?><?php echo smarty_function_lang(array('_default_short'=>'media.url.submit_upload'),$_smarty_tpl);?>
<?php $_tmp2=ob_get_clean();?><?php echo smarty_function_component(array('_default_short'=>'button','mods'=>'primary','classes'=>'js-media-url-submit-upload','text'=>$_tmp2),$_smarty_tpl);?>


</div><?php }} ?>