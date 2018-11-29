<?php /* Smarty version Smarty-3.1.13, created on 2018-11-25 11:43:23
         compiled from "/var/www/ls.new/framework/frontend/components/tags/tags-item.tpl" */ ?>
<?php /*%%SmartyHeaderCode:11898584095bfa60ab0a8297-24795719%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '43ccc4e042ca2a9216e6c8c6b5659c4b62778b6b' => 
    array (
      0 => '/var/www/ls.new/framework/frontend/components/tags/tags-item.tpl',
      1 => 1543037260,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '11898584095bfa60ab0a8297-24795719',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'component' => 0,
    'mods' => 0,
    'classes' => 0,
    'url' => 0,
    'text' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5bfa60ab10fb99_89547499',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5bfa60ab10fb99_89547499')) {function content_5bfa60ab10fb99_89547499($_smarty_tpl) {?><?php if (!is_callable('smarty_function_component_define_params')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component_define_params.php';
if (!is_callable('smarty_function_cmods')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.cmods.php';
?>


<?php $_smarty_tpl->tpl_vars['component'] = new Smarty_variable('ls-tags-item', null, 0);?>
<?php echo smarty_function_component_define_params(array('params'=>array('text','url','isFirst','isLast','mods','classes','attributes')),$_smarty_tpl);?>





<li class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
 <?php echo smarty_function_cmods(array('name'=>$_smarty_tpl->tpl_vars['component']->value,'mods'=>$_smarty_tpl->tpl_vars['mods']->value),$_smarty_tpl);?>
 <?php echo $_smarty_tpl->tpl_vars['classes']->value;?>
">
    <a href="<?php echo $_smarty_tpl->tpl_vars['url']->value;?>
" rel="tag">
        <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['text']->value, ENT_QUOTES, 'UTF-8', true);?>

    </a>
</li><?php }} ?>