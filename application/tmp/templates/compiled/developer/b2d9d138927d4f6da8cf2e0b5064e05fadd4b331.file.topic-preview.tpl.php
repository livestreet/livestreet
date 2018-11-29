<?php /* Smarty version Smarty-3.1.13, created on 2018-11-25 11:43:10
         compiled from "/var/www/ls.new/application/frontend/components/topic/topic-preview.tpl" */ ?>
<?php /*%%SmartyHeaderCode:5271376935bfa609e1f2007-31253416%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b2d9d138927d4f6da8cf2e0b5064e05fadd4b331' => 
    array (
      0 => '/var/www/ls.new/application/frontend/components/topic/topic-preview.tpl',
      1 => 1543035802,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '5271376935bfa609e1f2007-31253416',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'component' => 0,
    'aLang' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5bfa609e2914b5_96819743',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5bfa609e2914b5_96819743')) {function content_5bfa609e2914b5_96819743($_smarty_tpl) {?><?php if (!is_callable('smarty_function_component')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component.php';
?>

<?php $_smarty_tpl->tpl_vars['component'] = new Smarty_variable('ls-topic-preview', null, 0);?>

<div class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
" id="topic-text-preview">
    <header class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-header">
        <h3 class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-title"><?php echo $_smarty_tpl->tpl_vars['aLang']->value['common']['preview_text'];?>
</h3>
    </header>

    <div class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-body js-topic-preview-content"></div>

    <footer class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-footer">
        <?php echo smarty_function_component(array('_default_short'=>'button','type'=>'button','classes'=>'js-topic-preview-text-hide-button','text'=>$_smarty_tpl->tpl_vars['aLang']->value['common']['cancel']),$_smarty_tpl);?>

    </footer>
</div><?php }} ?>