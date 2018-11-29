<?php /* Smarty version Smarty-3.1.13, created on 2018-11-24 09:24:31
         compiled from "/var/www/ls.new/application/frontend/components/userbar/userbar.tpl" */ ?>
<?php /*%%SmartyHeaderCode:4115778915bf8ee9f9123e6-32085136%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '779c28832d5971cce6ff548e0b97ef3014b1be3c' => 
    array (
      0 => '/var/www/ls.new/application/frontend/components/userbar/userbar.tpl',
      1 => 1543035802,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '4115778915bf8ee9f9123e6-32085136',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'oUserCurrent' => 0,
    'LS' => 0,
    'type' => 0,
    'iUserCurrentCountTopicDraft' => 0,
    'aLang' => 0,
    'createMenu' => 1,
    'iUserCurrentCountTalkNew' => 1,
    'LIVESTREET_SECURITY_KEY' => 1,
    'sMenuHeadItemSelect' => 0,
    'items' => 1,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5bf8ee9f9f63f5_93527673',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5bf8ee9f9f63f5_93527673')) {function content_5bf8ee9f9f63f5_93527673($_smarty_tpl) {?><?php if (!is_callable('smarty_function_router')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.router.php';
if (!is_callable('smarty_function_lang')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.lang.php';
if (!is_callable('smarty_insert_block')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/insert.block.php';
if (!is_callable('smarty_function_component')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component.php';
?>

<div class="ls-userbar">
    <div class="ls-userbar-inner ls-clearfix" style="min-width: <?php echo Config::Get('view.grid.fluid_min_width');?>
; max-width: <?php echo Config::Get('view.grid.fluid_max_width');?>
;">
        <?php if (!Config::Get('view.layout_show_banner')){?>
            <h1 class="ls-userbar-logo">
                <a href="<?php echo smarty_function_router(array('page'=>'/'),$_smarty_tpl);?>
"><?php echo Config::Get('view.name');?>
</a>
            </h1>
        <?php }?>

        <nav class="ls-userbar-nav">
            <?php if ($_smarty_tpl->tpl_vars['oUserCurrent']->value){?>
                <?php $_smarty_tpl->tpl_vars['createMenu'] = new Smarty_variable(array(), null, 0);?>

                <?php  $_smarty_tpl->tpl_vars['type'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['type']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['LS']->value->Topic_GetTopicTypes(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['type']->key => $_smarty_tpl->tpl_vars['type']->value){
$_smarty_tpl->tpl_vars['type']->_loop = true;
?>
                    <?php $_smarty_tpl->createLocalArrayVariable('createMenu', null, 0);
$_smarty_tpl->tpl_vars['createMenu']->value[] = array('name'=>$_smarty_tpl->tpl_vars['type']->value->getCode(),'text'=>$_smarty_tpl->tpl_vars['type']->value->getName(),'url'=>$_smarty_tpl->tpl_vars['type']->value->getUrlForAdd());?>
                <?php } ?>

                <?php ob_start();?><?php echo smarty_function_lang(array('_default_short'=>'modal_create.items.blog'),$_smarty_tpl);?>
<?php $_tmp1=ob_get_clean();?><?php ob_start();?><?php echo smarty_function_router(array('page'=>'blog/add'),$_smarty_tpl);?>
<?php $_tmp2=ob_get_clean();?><?php $_smarty_tpl->createLocalArrayVariable('createMenu', null, 0);
$_smarty_tpl->tpl_vars['createMenu']->value[] = array('name'=>'blog','text'=>$_tmp1,'url'=>$_tmp2);?>
                <?php ob_start();?><?php echo smarty_function_lang(array('_default_short'=>'modal_create.items.talk'),$_smarty_tpl);?>
<?php $_tmp3=ob_get_clean();?><?php ob_start();?><?php echo smarty_function_router(array('page'=>'talk/add'),$_smarty_tpl);?>
<?php $_tmp4=ob_get_clean();?><?php $_smarty_tpl->createLocalArrayVariable('createMenu', null, 0);
$_smarty_tpl->tpl_vars['createMenu']->value[] = array('name'=>'talk','text'=>$_tmp3,'url'=>$_tmp4);?>
                <?php ob_start();?><?php echo smarty_function_lang(array('_default_short'=>'topic.drafts'),$_smarty_tpl);?>
<?php $_tmp5=ob_get_clean();?><?php ob_start();?><?php echo smarty_function_router(array('page'=>'content/drafts'),$_smarty_tpl);?>
<?php $_tmp6=ob_get_clean();?><?php $_smarty_tpl->createLocalArrayVariable('createMenu', null, 0);
$_smarty_tpl->tpl_vars['createMenu']->value[] = array('name'=>'drafts','text'=>$_tmp5,'url'=>$_tmp6,'count'=>$_smarty_tpl->tpl_vars['iUserCurrentCountTopicDraft']->value);?>

                <?php ob_start();?><?php echo smarty_insert_block(array('block' => 'menu', 'params' => array('name'=>"user")),$_smarty_tpl);?>
<?php $_tmp7=ob_get_clean();?><?php ob_start();?><?php echo smarty_function_router(array('page'=>'talk'),$_smarty_tpl);?>
<?php $_tmp8=ob_get_clean();?><?php ob_start();?><?php echo smarty_function_router(array('page'=>'auth'),$_smarty_tpl);?>
<?php $_tmp9=ob_get_clean();?><?php $_smarty_tpl->tpl_vars['items'] = new Smarty_variable(array(array('html'=>$_tmp7),array('text'=>$_smarty_tpl->tpl_vars['aLang']->value['common']['create'],'menu'=>array('hook'=>'create','items'=>$_smarty_tpl->tpl_vars['createMenu']->value)),array('text'=>$_smarty_tpl->tpl_vars['aLang']->value['talk']['title'],'url'=>$_tmp8,'title'=>$_smarty_tpl->tpl_vars['aLang']->value['talk']['new_messages'],'is_enabled'=>$_smarty_tpl->tpl_vars['iUserCurrentCountTalkNew']->value,'count'=>$_smarty_tpl->tpl_vars['iUserCurrentCountTalkNew']->value),array('text'=>$_smarty_tpl->tpl_vars['aLang']->value['auth']['logout'],'url'=>$_tmp9."logout/?security_ls_key=".((string)$_smarty_tpl->tpl_vars['LIVESTREET_SECURITY_KEY']->value))), true, 0);?>
            <?php }else{ ?>
                <?php ob_start();?><?php echo smarty_function_router(array('page'=>'auth/login'),$_smarty_tpl);?>
<?php $_tmp10=ob_get_clean();?><?php ob_start();?><?php echo smarty_function_router(array('page'=>'auth/register'),$_smarty_tpl);?>
<?php $_tmp11=ob_get_clean();?><?php $_smarty_tpl->tpl_vars['items'] = new Smarty_variable(array(array('text'=>$_smarty_tpl->tpl_vars['aLang']->value['auth']['login']['title'],'classes'=>'js-modal-toggle-login','url'=>$_tmp10),array('text'=>$_smarty_tpl->tpl_vars['aLang']->value['auth']['registration']['title'],'classes'=>'js-modal-toggle-registration','url'=>$_tmp11)), null, 0);?>
            <?php }?>

            <?php echo smarty_function_component(array('_default_short'=>'nav','hook'=>'userbar_nav','hookParams'=>array('user'=>$_smarty_tpl->tpl_vars['oUserCurrent']->value),'activeItem'=>$_smarty_tpl->tpl_vars['sMenuHeadItemSelect']->value,'mods'=>'userbar','items'=>$_smarty_tpl->tpl_vars['items']->value),$_smarty_tpl);?>

        </nav>

        <?php echo smarty_function_component(array('_default_short'=>'search','template'=>'main','mods'=>'light'),$_smarty_tpl);?>

    </div>
</div>
<?php }} ?>