<?php /* Smarty version Smarty-3.1.13, created on 2018-11-24 09:24:31
         compiled from "/var/www/ls.new/framework/frontend/components/tabs/tab.tpl" */ ?>
<?php /*%%SmartyHeaderCode:15314132115bf8ee9f46ac96-87319207%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'cba18a3c7ae56f4300420f3cfc3f00bf6d4ba371' => 
    array (
      0 => '/var/www/ls.new/framework/frontend/components/tabs/tab.tpl',
      1 => 1543037260,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '15314132115bf8ee9f46ac96-87319207',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'component' => 0,
    'mods' => 0,
    'classes' => 0,
    'isActive' => 0,
    'attributes' => 0,
    'uid' => 0,
    'url' => 0,
    'text' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5bf8ee9f4abcd4_17786325',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5bf8ee9f4abcd4_17786325')) {function content_5bf8ee9f4abcd4_17786325($_smarty_tpl) {?><?php if (!is_callable('smarty_function_component_define_params')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component_define_params.php';
if (!is_callable('smarty_function_cmods')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.cmods.php';
if (!is_callable('smarty_function_cattr')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.cattr.php';
?><?php $_smarty_tpl->tpl_vars['component'] = new Smarty_variable('ls-tab', null, 0);?>
<?php echo smarty_function_component_define_params(array('params'=>array('name','isActive','mods','classes','attributes')),$_smarty_tpl);?>


<li class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
 <?php echo smarty_function_cmods(array('name'=>$_smarty_tpl->tpl_vars['component']->value,'mods'=>$_smarty_tpl->tpl_vars['mods']->value),$_smarty_tpl);?>
 <?php echo $_smarty_tpl->tpl_vars['classes']->value;?>
 <?php if ($_smarty_tpl->tpl_vars['isActive']->value){?>active<?php }?>" <?php echo smarty_function_cattr(array('list'=>$_smarty_tpl->tpl_vars['attributes']->value),$_smarty_tpl);?>

    data-tab
    data-lstab-options='{
        "target": "<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
",
        "urls": {
            "load": "<?php echo $_smarty_tpl->tpl_vars['url']->value;?>
"
        }
    }'>

    <?php if ($_smarty_tpl->tpl_vars['url']->value){?>
        <a href="<?php echo $_smarty_tpl->tpl_vars['url']->value;?>
" class="ls-tab-inner"><?php echo $_smarty_tpl->tpl_vars['text']->value;?>
</a>
    <?php }else{ ?>
        <span class="ls-tab-inner"><?php echo $_smarty_tpl->tpl_vars['text']->value;?>
</span>
    <?php }?>
</li><?php }} ?>