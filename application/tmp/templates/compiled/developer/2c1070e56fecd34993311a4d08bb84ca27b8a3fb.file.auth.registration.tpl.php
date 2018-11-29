<?php /* Smarty version Smarty-3.1.13, created on 2018-11-25 13:37:06
         compiled from "/var/www/ls.new/application/frontend/components/auth/auth.registration.tpl" */ ?>
<?php /*%%SmartyHeaderCode:19682118755bfa7b5260c834-77124760%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2c1070e56fecd34993311a4d08bb84ca27b8a3fb' => 
    array (
      0 => '/var/www/ls.new/application/frontend/components/auth/auth.registration.tpl',
      1 => 1543035802,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '19682118755bfa7b5260c834-77124760',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'redirectUrl' => 0,
    'PATH_WEB_CURRENT' => 0,
    'aLang' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5bfa7b526c5579_82677987',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5bfa7b526c5579_82677987')) {function content_5bfa7b526c5579_82677987($_smarty_tpl) {?><?php if (!is_callable('smarty_function_component_define_params')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component_define_params.php';
if (!is_callable('smarty_function_hook')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.hook.php';
if (!is_callable('smarty_function_router')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.router.php';
if (!is_callable('smarty_function_component')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component.php';
if (!is_callable('smarty_function_lang')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.lang.php';
?>

<?php echo smarty_function_component_define_params(array('params'=>array('redirectUrl')),$_smarty_tpl);?>


<?php $_smarty_tpl->tpl_vars['redirectUrl'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['redirectUrl']->value)===null||$tmp==='' ? $_smarty_tpl->tpl_vars['PATH_WEB_CURRENT']->value : $tmp), null, 0);?>

<?php echo smarty_function_hook(array('run'=>'registration_begin'),$_smarty_tpl);?>


<form action="<?php echo smarty_function_router(array('page'=>'auth/register'),$_smarty_tpl);?>
" method="post" class="js-form-validate js-auth-registration-form">
    <?php echo smarty_function_hook(array('run'=>'form_registration_begin'),$_smarty_tpl);?>


    
    <?php ob_start();?><?php echo smarty_function_router(array('page'=>'auth'),$_smarty_tpl);?>
<?php $_tmp1=ob_get_clean();?><?php echo smarty_function_component(array('_default_short'=>'field','template'=>'text','name'=>'login','rules'=>array('required'=>true,'minlength'=>'3','remote'=>$_tmp1."ajax-validate-login"),'label'=>$_smarty_tpl->tpl_vars['aLang']->value['auth']['labels']['login']),$_smarty_tpl);?>


    
    <?php ob_start();?><?php echo smarty_function_router(array('page'=>'auth'),$_smarty_tpl);?>
<?php $_tmp2=ob_get_clean();?><?php echo smarty_function_component(array('_default_short'=>'field','template'=>'email','rules'=>array('remote'=>$_tmp2."ajax-validate-email")),$_smarty_tpl);?>


    
    <?php echo smarty_function_component(array('_default_short'=>'field','template'=>'text','name'=>'password','type'=>'password','rules'=>array('required'=>true,'minlength'=>'5','trigger'=>'input'),'label'=>$_smarty_tpl->tpl_vars['aLang']->value['auth']['labels']['password'],'inputClasses'=>'js-input-password-reg'),$_smarty_tpl);?>


    
    <?php ob_start();?><?php echo smarty_function_lang(array('_default_short'=>'auth.registration.notices.error_password_equal'),$_smarty_tpl);?>
<?php $_tmp3=ob_get_clean();?><?php echo smarty_function_component(array('_default_short'=>'field','template'=>'text','name'=>'password_confirm','type'=>'password','rules'=>array('required'=>true,'minlength'=>'5','trigger'=>'input','equalto'=>'.js-input-password-reg','equalto-message'=>$_tmp3),'label'=>$_smarty_tpl->tpl_vars['aLang']->value['auth']['registration']['form']['fields']['password_confirm']['label']),$_smarty_tpl);?>


    
    <?php if (Config::Get('module.user.captcha_use_registration')){?>
        <?php echo smarty_function_component(array('_default_short'=>'field','template'=>'captcha','captchaType'=>Config::Get('sys.captcha.type'),'captchaName'=>'user_signup','name'=>'captcha','label'=>$_smarty_tpl->tpl_vars['aLang']->value['auth']['labels']['captcha']),$_smarty_tpl);?>

    <?php }?>

    <?php echo smarty_function_hook(array('run'=>'form_registration_end'),$_smarty_tpl);?>


    <?php if ($_smarty_tpl->tpl_vars['redirectUrl']->value){?>
        <?php echo smarty_function_component(array('_default_short'=>'field','template'=>'hidden','name'=>'return-path','value'=>$_smarty_tpl->tpl_vars['redirectUrl']->value),$_smarty_tpl);?>

    <?php }?>

    <?php echo smarty_function_component(array('_default_short'=>'button','name'=>'submit_register','mods'=>'primary','text'=>$_smarty_tpl->tpl_vars['aLang']->value['auth']['registration']['form']['fields']['submit']['text']),$_smarty_tpl);?>

</form>

<?php echo smarty_function_hook(array('run'=>'registration_end'),$_smarty_tpl);?>

<?php }} ?>