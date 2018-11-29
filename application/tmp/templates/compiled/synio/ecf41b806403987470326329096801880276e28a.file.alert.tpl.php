<?php /* Smarty version Smarty-3.1.13, created on 2018-11-24 09:00:15
         compiled from "/var/www/ls.new/framework/frontend/components/alert/alert.tpl" */ ?>
<?php /*%%SmartyHeaderCode:7402707755bf8e8efe98244-72024978%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ecf41b806403987470326329096801880276e28a' => 
    array (
      0 => '/var/www/ls.new/framework/frontend/components/alert/alert.tpl',
      1 => 1543037260,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '7402707755bf8e8efe98244-72024978',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'component' => 0,
    'visible' => 0,
    'close' => 0,
    'dismissible' => 0,
    'mods' => 0,
    'classes' => 0,
    'attributes' => 0,
    'title' => 0,
    'text' => 0,
    'alert' => 0,
    'uid' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5bf8e8eff24939_16687858',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5bf8e8eff24939_16687858')) {function content_5bf8e8eff24939_16687858($_smarty_tpl) {?><?php if (!is_callable('smarty_function_component_define_params')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component_define_params.php';
if (!is_callable('smarty_function_cmods')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.cmods.php';
if (!is_callable('smarty_function_cattr')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.cattr.php';
if (!is_callable('smarty_function_component')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component.php';
if (!is_callable('smarty_function_lang')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.lang.php';
?>


<?php $_smarty_tpl->tpl_vars['component'] = new Smarty_variable('ls-alert', null, 0);?>
<?php echo smarty_function_component_define_params(array('params'=>array('title','text','visible','dismissible','close','mods','classes','attributes')),$_smarty_tpl);?>



<?php ob_start();?><?php echo mt_rand();?>
<?php $_tmp1=ob_get_clean();?><?php $_smarty_tpl->tpl_vars['uid'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['component']->value).$_tmp1, null, 0);?>
<?php $_smarty_tpl->tpl_vars['visible'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['visible']->value)===null||$tmp==='' ? true : $tmp), null, 0);?>

<?php $_smarty_tpl->tpl_vars['dismissible'] = new Smarty_variable($_smarty_tpl->tpl_vars['close']->value ? $_smarty_tpl->tpl_vars['close']->value : $_smarty_tpl->tpl_vars['dismissible']->value, null, 0);?>
<?php if ($_smarty_tpl->tpl_vars['dismissible']->value){?>
    <?php $_smarty_tpl->tpl_vars['mods'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['mods']->value)." dismissible", null, 0);?>
<?php }?>




<div class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
 <?php echo smarty_function_cmods(array('name'=>$_smarty_tpl->tpl_vars['component']->value,'mods'=>$_smarty_tpl->tpl_vars['mods']->value),$_smarty_tpl);?>
 <?php echo $_smarty_tpl->tpl_vars['classes']->value;?>
 js-alert" role="alert" <?php if (!$_smarty_tpl->tpl_vars['visible']->value){?>hidden<?php }?> <?php echo smarty_function_cattr(array('list'=>$_smarty_tpl->tpl_vars['attributes']->value),$_smarty_tpl);?>
>
    
    <?php if ($_smarty_tpl->tpl_vars['title']->value){?>
        <h4 class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-title"><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</h4>
    <?php }?>

    
    <?php if ($_smarty_tpl->tpl_vars['text']->value){?>
        <div class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-body">
            
                <?php if (is_array($_smarty_tpl->tpl_vars['text']->value)){?>
                    <ul class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-list">
                        <?php  $_smarty_tpl->tpl_vars['alert'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['alert']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['text']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['alert']->key => $_smarty_tpl->tpl_vars['alert']->value){
$_smarty_tpl->tpl_vars['alert']->_loop = true;
?>
                            <li class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-list-item">
                                <?php if ($_smarty_tpl->tpl_vars['alert']->value['title']){?>
                                    <strong><?php echo $_smarty_tpl->tpl_vars['alert']->value['title'];?>
</strong>:
                                <?php }?>

                                <?php echo $_smarty_tpl->tpl_vars['alert']->value['msg'];?>

                            </li>
                        <?php } ?>
                    </ul>
                <?php }else{ ?>
                    <?php echo $_smarty_tpl->tpl_vars['text']->value;?>

                <?php }?>
            
        </div>
    <?php }?>

    
    <?php if ($_smarty_tpl->tpl_vars['dismissible']->value){?>
        <button class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-close js-<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-close" aria-labelledby="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">
            <?php echo smarty_function_component(array('_default_short'=>'icon','icon'=>'remove'),$_smarty_tpl);?>

            <span id="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" aria-hidden="true" hidden><?php echo smarty_function_lang(array('_default_short'=>'common.close'),$_smarty_tpl);?>
</span>
        </button>
    <?php }?>
</div>
<?php }} ?>