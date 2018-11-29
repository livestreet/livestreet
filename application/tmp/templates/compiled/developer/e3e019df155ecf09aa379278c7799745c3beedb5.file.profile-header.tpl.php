<?php /* Smarty version Smarty-3.1.13, created on 2018-11-29 11:18:26
         compiled from "/var/www/ls.new/application/plugins/admin/frontend/components/p-user/profile-header.tpl" */ ?>
<?php /*%%SmartyHeaderCode:7088674135bffa0d2e8f0b0-92734062%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e3e019df155ecf09aa379278c7799745c3beedb5' => 
    array (
      0 => '/var/www/ls.new/application/plugins/admin/frontend/components/p-user/profile-header.tpl',
      1 => 1543039194,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '7088674135bffa0d2e8f0b0-92734062',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'component' => 0,
    'mods' => 0,
    'classes' => 0,
    'attributes' => 0,
    'user' => 0,
    'aLang' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5bffa0d2ef21a0_98739564',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5bffa0d2ef21a0_98739564')) {function content_5bffa0d2ef21a0_98739564($_smarty_tpl) {?><?php if (!is_callable('smarty_function_component_define_params')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component_define_params.php';
if (!is_callable('smarty_function_cmods')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.cmods.php';
if (!is_callable('smarty_function_cattr')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.cattr.php';
if (!is_callable('smarty_function_component')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component.php';
?><?php $_smarty_tpl->tpl_vars['component'] = new Smarty_variable('p-user-profile-header', null, 0);?>
<?php echo smarty_function_component_define_params(array('params'=>array('user')),$_smarty_tpl);?>


<div class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
 <?php echo smarty_function_cmods(array('name'=>$_smarty_tpl->tpl_vars['component']->value,'mods'=>$_smarty_tpl->tpl_vars['mods']->value),$_smarty_tpl);?>
 <?php echo $_smarty_tpl->tpl_vars['classes']->value;?>
" <?php echo smarty_function_cattr(array('list'=>$_smarty_tpl->tpl_vars['attributes']->value),$_smarty_tpl);?>
>
    <div class="user-brief-body">
        <a href="<?php echo $_smarty_tpl->tpl_vars['user']->value->getUserWebPath();?>
" class="user-avatar <?php if ($_smarty_tpl->tpl_vars['user']->value->isOnline()){?>user-is-online<?php }?>">
            <img src="<?php echo $_smarty_tpl->tpl_vars['user']->value->getProfileAvatarPath(100);?>
" alt="avatar" title="<?php if ($_smarty_tpl->tpl_vars['user']->value->isOnline()){?><?php echo $_smarty_tpl->tpl_vars['aLang']->value['user_status_online'];?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['aLang']->value['user_status_offline'];?>
<?php }?>" />
        </a>

        <h3 class="user-login">
            <?php echo $_smarty_tpl->tpl_vars['user']->value->getLogin();?>


            <?php if ($_smarty_tpl->tpl_vars['user']->value->isAdministrator()){?>
                <i class="p-icon--user-admin" title="<?php echo $_smarty_tpl->tpl_vars['aLang']->value['plugin']['admin']['users']['admin'];?>
"></i>
            <?php }?>
        </h3>

        <?php if ($_smarty_tpl->tpl_vars['user']->value->getProfileName()){?>
            <p class="user-name">
                <?php echo $_smarty_tpl->tpl_vars['user']->value->getProfileName();?>

            </p>
        <?php }?>

        <p class="user-mail">
            <a href="mailto:<?php echo $_smarty_tpl->tpl_vars['user']->value->getMail();?>
" target="_blank" class="link-border"><span><?php echo $_smarty_tpl->tpl_vars['user']->value->getMail();?>
</span></a>
        </p>

        <p class="user-id"><?php echo $_smarty_tpl->tpl_vars['aLang']->value['plugin']['admin']['users']['profile']['user_no'];?>
<?php echo $_smarty_tpl->tpl_vars['user']->value->getId();?>
</p>
    </div>

    <?php echo smarty_function_component(array('_default_short'=>'admin:p-user.actions','user'=>$_smarty_tpl->tpl_vars['user']->value,'text'=>'Действия'),$_smarty_tpl);?>

</div><?php }} ?>