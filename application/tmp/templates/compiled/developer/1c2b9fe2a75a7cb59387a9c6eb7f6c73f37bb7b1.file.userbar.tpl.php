<?php /* Smarty version Smarty-3.1.13, created on 2018-11-24 09:24:28
         compiled from "/var/www/ls.new/application/plugins/admin/frontend/components/p-userbar/userbar.tpl" */ ?>
<?php /*%%SmartyHeaderCode:14173514795bf8ee9cd37115-21425511%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1c2b9fe2a75a7cb59387a9c6eb7f6c73f37bb7b1' => 
    array (
      0 => '/var/www/ls.new/application/plugins/admin/frontend/components/p-userbar/userbar.tpl',
      1 => 1543039194,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '14173514795bf8ee9cd37115-21425511',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'component' => 0,
    'mods' => 0,
    'classes' => 0,
    'attributes' => 0,
    'oUserCurrent' => 0,
    'LIVESTREET_SECURITY_KEY' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5bf8ee9cd824d4_95888057',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5bf8ee9cd824d4_95888057')) {function content_5bf8ee9cd824d4_95888057($_smarty_tpl) {?><?php if (!is_callable('smarty_function_component_define_params')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component_define_params.php';
if (!is_callable('smarty_function_cmods')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.cmods.php';
if (!is_callable('smarty_function_cattr')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.cattr.php';
if (!is_callable('smarty_function_router')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.router.php';
if (!is_callable('smarty_function_component')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component.php';
?>

<?php $_smarty_tpl->tpl_vars['component'] = new Smarty_variable('p-userbar', null, 0);?>
<?php echo smarty_function_component_define_params(array('params'=>array('skin','mods','classes','attributes')),$_smarty_tpl);?>


<div id="header" class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
 <?php echo smarty_function_cmods(array('name'=>$_smarty_tpl->tpl_vars['component']->value,'mods'=>$_smarty_tpl->tpl_vars['mods']->value),$_smarty_tpl);?>
 <?php echo $_smarty_tpl->tpl_vars['classes']->value;?>
 ls-clearfix" <?php echo smarty_function_cattr(array('list'=>$_smarty_tpl->tpl_vars['attributes']->value),$_smarty_tpl);?>
>
    <div class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-logo">
        <div class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-logo-home">
            <a href="<?php echo Router::GetPath('/');?>
">Перейти на сайт</a>
        </div>

        <h1 class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-logo-name">
            <a href="<?php echo Router::GetPath('admin');?>
"><?php echo Config::Get("view.name");?>
</a>
        </h1>
    </div>

    
    <?php ob_start();?><?php echo smarty_function_router(array('page'=>"admin/users/profile/".((string)$_smarty_tpl->tpl_vars['oUserCurrent']->value->getId())),$_smarty_tpl);?>
<?php $_tmp1=ob_get_clean();?><?php ob_start();?><?php echo smarty_function_router(array('page'=>'auth/logout'),$_smarty_tpl);?>
<?php $_tmp2=ob_get_clean();?><?php echo smarty_function_component(array('_default_short'=>'admin:dropdown','text'=>"<img src=\"".((string)$_smarty_tpl->tpl_vars['oUserCurrent']->value->getProfileAvatarPath(48))."\" alt=\"Avatar\" class=\"userbar-avatar\" />".((string)$_smarty_tpl->tpl_vars['oUserCurrent']->value->getLogin()),'classes'=>'admin-userbar js-dropdown-userbar','menu'=>array(array('text'=>'Мой профиль','url'=>$_tmp1),array('text'=>'Выйти','url'=>$_tmp2."?security_ls_key=".((string)$_smarty_tpl->tpl_vars['LIVESTREET_SECURITY_KEY']->value)))),$_smarty_tpl);?>

</div><?php }} ?>