<?php /* Smarty version Smarty-3.1.13, created on 2018-11-24 09:24:06
         compiled from "/var/www/ls.new/application/frontend/skin/synio/components/userbar/usernav.tpl" */ ?>
<?php /*%%SmartyHeaderCode:3388399665bf8ee86594c91-80679334%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '92bb2deffb8768db62ba8a982437424625d0dcb4' => 
    array (
      0 => '/var/www/ls.new/application/frontend/skin/synio/components/userbar/usernav.tpl',
      1 => 1543035802,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3388399665bf8ee86594c91-80679334',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'oUserCurrent' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5bf8ee865b8204_36400422',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5bf8ee865b8204_36400422')) {function content_5bf8ee865b8204_36400422($_smarty_tpl) {?><?php if (!is_callable('smarty_insert_block')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/insert.block.php';
?>
<div class="ls-userbar-user-nav js-userbar-user-nav">
    <a href="<?php echo $_smarty_tpl->tpl_vars['oUserCurrent']->value->getUserWebPath();?>
">
        <img src="<?php echo $_smarty_tpl->tpl_vars['oUserCurrent']->value->getProfileAvatarPath(48);?>
" alt="<?php echo $_smarty_tpl->tpl_vars['oUserCurrent']->value->getDisplayName();?>
"" class="ls-userbar-user-nav-avatar" />
    </a>

    <a href="<?php echo $_smarty_tpl->tpl_vars['oUserCurrent']->value->getUserWebPath();?>
" class="ls-userbar-user-nav-username">
        <?php echo $_smarty_tpl->tpl_vars['oUserCurrent']->value->getDisplayName();?>

    </a>

    <div class="ls-userbar-user-nav-toggle js-userbar-user-nav-toggle"></div>

    <?php echo smarty_insert_block(array('block' => 'menu', 'params' => array('name'=>"user")),$_smarty_tpl);?>

    
</div><?php }} ?>