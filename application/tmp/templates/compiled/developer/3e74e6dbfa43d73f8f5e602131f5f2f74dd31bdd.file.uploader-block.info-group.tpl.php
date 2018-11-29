<?php /* Smarty version Smarty-3.1.13, created on 2018-11-25 11:43:08
         compiled from "/var/www/ls.new/framework/frontend/components/uploader/uploader-block.info-group.tpl" */ ?>
<?php /*%%SmartyHeaderCode:11153518145bfa609c9f62e8-85309493%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3e74e6dbfa43d73f8f5e602131f5f2f74dd31bdd' => 
    array (
      0 => '/var/www/ls.new/framework/frontend/components/uploader/uploader-block.info-group.tpl',
      1 => 1543037260,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '11153518145bfa609c9f62e8-85309493',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'component_info' => 0,
    'type' => 0,
    'properties' => 0,
    'property' => 0,
    'propertiesFields' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5bfa609ca9cc45_09466029',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5bfa609ca9cc45_09466029')) {function content_5bfa609ca9cc45_09466029($_smarty_tpl) {?><?php if (!is_callable('smarty_function_component_define_params')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component_define_params.php';
if (!is_callable('smarty_function_lang')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.lang.php';
if (!is_callable('smarty_function_component')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component.php';
?>

<?php $_smarty_tpl->tpl_vars['component_info'] = new Smarty_variable('ls-uploader-info', null, 0);?>
<?php echo smarty_function_component_define_params(array('params'=>array('properties','propertiesFields','type')),$_smarty_tpl);?>


<div class="<?php echo $_smarty_tpl->tpl_vars['component_info']->value;?>
-group js-uploader-info-group" data-type="<?php echo $_smarty_tpl->tpl_vars['type']->value;?>
">
    
    <ul class="<?php echo $_smarty_tpl->tpl_vars['component_info']->value;?>
-actions">
        <li><a href="#" class="ls-link-dotted js-uploader-info-remove"><?php echo smarty_function_lang(array('name'=>'uploader.actions.remove'),$_smarty_tpl);?>
</a></li>
    </ul>

    
    <div class="<?php echo $_smarty_tpl->tpl_vars['component_info']->value;?>
-properties">
        
        <ul class="<?php echo $_smarty_tpl->tpl_vars['component_info']->value;?>
-list">
            <?php  $_smarty_tpl->tpl_vars['property'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['property']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['properties']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['property']->key => $_smarty_tpl->tpl_vars['property']->value){
$_smarty_tpl->tpl_vars['property']->_loop = true;
?>
                <li class="<?php echo $_smarty_tpl->tpl_vars['component_info']->value;?>
-list-item">
                    <span class="<?php echo $_smarty_tpl->tpl_vars['component_info']->value;?>
-list-item-label"><?php echo $_smarty_tpl->tpl_vars['property']->value['label'];?>
:</span>
                    <span class="js-uploader-info-property" data-name="<?php echo $_smarty_tpl->tpl_vars['property']->value['name'];?>
"></span>
                </li>
            <?php } ?>
        </ul>

        
        <div class="<?php echo $_smarty_tpl->tpl_vars['component_info']->value;?>
-fields">
            <?php  $_smarty_tpl->tpl_vars['property'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['property']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['propertiesFields']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['property']->key => $_smarty_tpl->tpl_vars['property']->value){
$_smarty_tpl->tpl_vars['property']->_loop = true;
?>
                <?php echo smarty_function_component(array('_default_short'=>'field','template'=>'text','name'=>$_smarty_tpl->tpl_vars['property']->value['name'],'inputClasses'=>"js-uploader-info-property",'inputAttributes'=>array('data-name'=>$_smarty_tpl->tpl_vars['property']->value['name']),'label'=>$_smarty_tpl->tpl_vars['property']->value['label']),$_smarty_tpl);?>

            <?php } ?>
        </div>
    </div>
</div><?php }} ?>