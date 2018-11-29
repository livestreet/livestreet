<?php /* Smarty version Smarty-3.1.13, created on 2018-11-25 11:43:08
         compiled from "/var/www/ls.new/application/frontend/components/media/uploader/uploader-block.insert.image.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1374918135bfa609cbcdcd7-33038957%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '836b437573606819abcac26348a6c09281991ff2' => 
    array (
      0 => '/var/www/ls.new/application/frontend/components/media/uploader/uploader-block.insert.image.tpl',
      1 => 1543035802,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1374918135bfa609cbcdcd7-33038957',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'useSizes' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5bfa609cc91552_75688669',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5bfa609cc91552_75688669')) {function content_5bfa609cc91552_75688669($_smarty_tpl) {?><?php if (!is_callable('smarty_function_component_define_params')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component_define_params.php';
if (!is_callable('smarty_function_lang')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.lang.php';
if (!is_callable('smarty_function_component')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component.php';
?>

<?php echo smarty_function_component_define_params(array('params'=>array('useSizes')),$_smarty_tpl);?>


<?php $_smarty_tpl->_capture_stack[0][] = array('block_content', null, null); ob_start(); ?>
    <form method="post" action="" enctype="multipart/form-data">
        
        <?php ob_start();?><?php echo smarty_function_lang(array('name'=>'media.image_align.title'),$_smarty_tpl);?>
<?php $_tmp1=ob_get_clean();?><?php ob_start();?><?php echo smarty_function_lang(array('name'=>'media.image_align.no'),$_smarty_tpl);?>
<?php $_tmp2=ob_get_clean();?><?php ob_start();?><?php echo smarty_function_lang(array('name'=>'media.image_align.left'),$_smarty_tpl);?>
<?php $_tmp3=ob_get_clean();?><?php ob_start();?><?php echo smarty_function_lang(array('name'=>'media.image_align.right'),$_smarty_tpl);?>
<?php $_tmp4=ob_get_clean();?><?php ob_start();?><?php echo smarty_function_lang(array('name'=>'media.image_align.center'),$_smarty_tpl);?>
<?php $_tmp5=ob_get_clean();?><?php echo smarty_function_component(array('_default_short'=>'field','template'=>'select','name'=>'align','label'=>$_tmp1,'items'=>array(array('value'=>'','text'=>$_tmp2),array('value'=>'left','text'=>$_tmp3),array('value'=>'right','text'=>$_tmp4),array('value'=>'center','text'=>$_tmp5))),$_smarty_tpl);?>


        
        <?php if ((($tmp = @$_smarty_tpl->tpl_vars['useSizes']->value)===null||$tmp==='' ? true : $tmp)){?>
            <?php ob_start();?><?php echo smarty_function_lang(array('name'=>'media.insert.settings.fields.size.label'),$_smarty_tpl);?>
<?php $_tmp6=ob_get_clean();?><?php ob_start();?><?php echo smarty_function_lang(array('name'=>'media.insert.settings.fields.size.original'),$_smarty_tpl);?>
<?php $_tmp7=ob_get_clean();?><?php echo smarty_function_component(array('_default_short'=>'field','template'=>'select','name'=>'size','label'=>$_tmp6,'items'=>array(array('value'=>'original','text'=>$_tmp7))),$_smarty_tpl);?>

        <?php }?>
    </form>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>

<?php ob_start();?><?php echo smarty_function_lang(array('_default_short'=>'media.insert.settings.title'),$_smarty_tpl);?>
<?php $_tmp8=ob_get_clean();?><?php echo smarty_function_component(array('_default_short'=>'uploader','template'=>'block','title'=>$_tmp8,'content'=>Smarty::$_smarty_vars['capture']['block_content'],'classes'=>'js-media-info-block js-media-info-block-image-options','attributes'=>array('data-type'=>'insert','data-filetype'=>'1')),$_smarty_tpl);?>
<?php }} ?>