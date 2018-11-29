<?php /* Smarty version Smarty-3.1.13, created on 2018-11-29 11:18:27
         compiled from "/var/www/ls.new/application/plugins/admin/frontend/components/p-user/form.tpl" */ ?>
<?php /*%%SmartyHeaderCode:17875053525bffa0d31ee981-07032952%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '45743712403f7c2bef967f2dfe11b8614a0dc14f' => 
    array (
      0 => '/var/www/ls.new/application/plugins/admin/frontend/components/p-user/form.tpl',
      1 => 1543039194,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '17875053525bffa0d31ee981-07032952',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'user' => 0,
    'aLang' => 0,
    'aGeoCountries' => 0,
    'aGeoRegions' => 0,
    'aGeoCities' => 0,
    'oGeoTarget' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5bffa0d328ef97_82045563',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5bffa0d328ef97_82045563')) {function content_5bffa0d328ef97_82045563($_smarty_tpl) {?><?php if (!is_callable('smarty_function_component_define_params')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component_define_params.php';
if (!is_callable('smarty_function_router')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.router.php';
if (!is_callable('smarty_function_component')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component.php';
if (!is_callable('smarty_function_date_format')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.date_format.php';
if (!is_callable('smarty_function_lang')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.lang.php';
?><?php $_smarty_tpl->tpl_vars['component'] = new Smarty_variable('p-user-form', null, 0);?>
<?php echo smarty_function_component_define_params(array('params'=>array('user')),$_smarty_tpl);?>


<form action="<?php echo smarty_function_router(array('page'=>'admin/users/profile'),$_smarty_tpl);?>
<?php echo $_smarty_tpl->tpl_vars['user']->value->getId();?>
" method="post">
    <?php echo smarty_function_component(array('_default_short'=>'admin:field.hidden.security-key'),$_smarty_tpl);?>


    <?php echo smarty_function_component(array('_default_short'=>'admin:field.text','name'=>'login','value'=>$_smarty_tpl->tpl_vars['user']->value->getLogin(),'label'=>$_smarty_tpl->tpl_vars['aLang']->value['plugin']['admin']['users']['profile']['info']['login']),$_smarty_tpl);?>


    <?php echo smarty_function_component(array('_default_short'=>'admin:field.text','name'=>'profile_name','value'=>$_smarty_tpl->tpl_vars['user']->value->getProfileName(),'label'=>$_smarty_tpl->tpl_vars['aLang']->value['plugin']['admin']['users']['profile']['info']['profile_name']),$_smarty_tpl);?>


    <?php echo smarty_function_component(array('_default_short'=>'admin:field.text','name'=>'mail','value'=>$_smarty_tpl->tpl_vars['user']->value->getMail(),'label'=>$_smarty_tpl->tpl_vars['aLang']->value['plugin']['admin']['users']['profile']['info']['mail']),$_smarty_tpl);?>


    <?php echo smarty_function_component(array('_default_short'=>'admin:field.select','name'=>'profile_sex','selectedValue'=>$_smarty_tpl->tpl_vars['user']->value->getProfileSex(),'label'=>$_smarty_tpl->tpl_vars['aLang']->value['plugin']['admin']['users']['profile']['info']['sex'],'items'=>array(array('value'=>'man','text'=>$_smarty_tpl->tpl_vars['aLang']->value['plugin']['admin']['users']['sex']['man']),array('value'=>'woman','text'=>$_smarty_tpl->tpl_vars['aLang']->value['plugin']['admin']['users']['sex']['woman']),array('value'=>'other','text'=>$_smarty_tpl->tpl_vars['aLang']->value['plugin']['admin']['users']['sex']['other']))),$_smarty_tpl);?>


    <?php echo smarty_function_component(array('_default_short'=>'admin:field.text','name'=>'profile_rating','value'=>$_smarty_tpl->tpl_vars['user']->value->getRating(),'label'=>'Рейтинг'),$_smarty_tpl);?>


    <?php ob_start();?><?php echo smarty_function_date_format(array('date'=>$_smarty_tpl->tpl_vars['user']->value->getProfileBirthday(),'format'=>'d.m.Y'),$_smarty_tpl);?>
<?php $_tmp1=ob_get_clean();?><?php echo smarty_function_component(array('_default_short'=>'admin:field.date','name'=>'profile_birthday','inputAttributes'=>array('data-lsdate-format'=>'DD.MM.YYYY'),'inputClasses'=>'js-field-date-default','value'=>$_smarty_tpl->tpl_vars['user']->value->getProfileBirthday() ? $_tmp1 : '','label'=>$_smarty_tpl->tpl_vars['aLang']->value['plugin']['admin']['users']['profile']['info']['birthday']),$_smarty_tpl);?>


    
    <?php ob_start();?><?php echo smarty_function_lang(array('name'=>'plugin.admin.users.profile.info.living'),$_smarty_tpl);?>
<?php $_tmp2=ob_get_clean();?><?php echo smarty_function_component(array('_default_short'=>'admin:field.geo','classes'=>'js-field-geo-default','name'=>'geo','label'=>$_tmp2,'countries'=>$_smarty_tpl->tpl_vars['aGeoCountries']->value,'regions'=>$_smarty_tpl->tpl_vars['aGeoRegions']->value,'cities'=>$_smarty_tpl->tpl_vars['aGeoCities']->value,'place'=>$_smarty_tpl->tpl_vars['oGeoTarget']->value),$_smarty_tpl);?>


    <?php echo smarty_function_component(array('_default_short'=>'admin:field.text','name'=>'password','label'=>$_smarty_tpl->tpl_vars['aLang']->value['plugin']['admin']['users']['profile_edit']['password']),$_smarty_tpl);?>


    <?php echo smarty_function_component(array('_default_short'=>'admin:field.textarea','name'=>'profile_about','rows'=>4,'value'=>preg_replace('!<[^>]*?>!', ' ', $_smarty_tpl->tpl_vars['user']->value->getProfileAbout()),'label'=>$_smarty_tpl->tpl_vars['aLang']->value['plugin']['admin']['users']['profile_edit']['about_user']),$_smarty_tpl);?>


    <?php echo smarty_function_component(array('_default_short'=>'admin:button','text'=>$_smarty_tpl->tpl_vars['aLang']->value['common']['save'],'name'=>'submit_edit','mods'=>'primary'),$_smarty_tpl);?>

</form><?php }} ?>