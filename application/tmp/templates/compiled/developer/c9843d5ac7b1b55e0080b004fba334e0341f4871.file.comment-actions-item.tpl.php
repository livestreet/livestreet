<?php /* Smarty version Smarty-3.1.13, created on 2018-11-25 11:43:30
         compiled from "/var/www/ls.new/application/frontend/components/comment/comment-actions-item.tpl" */ ?>
<?php /*%%SmartyHeaderCode:21003755145bfa60b2465cb7-86452442%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c9843d5ac7b1b55e0080b004fba334e0341f4871' => 
    array (
      0 => '/var/www/ls.new/application/frontend/components/comment/comment-actions-item.tpl',
      1 => 1543035802,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '21003755145bfa60b2465cb7-86452442',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'component' => 0,
    'mods' => 0,
    'classes' => 0,
    'attributes' => 0,
    'link' => 0,
    'text' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5bfa60b24ca3b1_27518797',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5bfa60b24ca3b1_27518797')) {function content_5bfa60b24ca3b1_27518797($_smarty_tpl) {?><?php if (!is_callable('smarty_function_component_define_params')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component_define_params.php';
if (!is_callable('smarty_function_cmods')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.cmods.php';
if (!is_callable('smarty_function_cattr')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.cattr.php';
?>

<?php $_smarty_tpl->tpl_vars['component'] = new Smarty_variable('ls-comment-actions-item', null, 0);?>
<?php echo smarty_function_component_define_params(array('params'=>array('text','link','mods','classes','attributes')),$_smarty_tpl);?>


<li class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
 <?php echo smarty_function_cmods(array('name'=>$_smarty_tpl->tpl_vars['component']->value,'mods'=>$_smarty_tpl->tpl_vars['mods']->value),$_smarty_tpl);?>
 <?php echo $_smarty_tpl->tpl_vars['classes']->value;?>
" <?php echo smarty_function_cattr(array('list'=>$_smarty_tpl->tpl_vars['attributes']->value),$_smarty_tpl);?>
>
    <?php if ($_smarty_tpl->tpl_vars['link']->value){?>
        <a href="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['link']->value['url'])===null||$tmp==='' ? '#' : $tmp);?>
" class="ls-link-dotted <?php echo $_smarty_tpl->tpl_vars['link']->value['classes'];?>
" <?php echo smarty_function_cattr(array('list'=>$_smarty_tpl->tpl_vars['link']->value['attributes']),$_smarty_tpl);?>
>
            <?php echo $_smarty_tpl->tpl_vars['text']->value;?>

        </a>
    <?php }else{ ?>
        <?php echo $_smarty_tpl->tpl_vars['text']->value;?>

    <?php }?>
</li><?php }} ?>