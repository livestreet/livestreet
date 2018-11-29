<?php /* Smarty version Smarty-3.1.13, created on 2018-11-29 11:18:26
         compiled from "/var/www/ls.new/application/plugins/admin/frontend/components/p-user/profile.tpl" */ ?>
<?php /*%%SmartyHeaderCode:18023582345bffa0d2f064a3-51085799%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '18e54195d4eda8ceb0cfe188b76dc101b5c3b48b' => 
    array (
      0 => '/var/www/ls.new/application/plugins/admin/frontend/components/p-user/profile.tpl',
      1 => 1543477548,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '18023582345bffa0d2f064a3-51085799',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'component' => 0,
    'user' => 0,
    'aLang' => 0,
    'oSession' => 0,
    'list' => 0,
    'iCountTopicUser' => 0,
    'iCountCommentUser' => 0,
    'iCountBlogsUser' => 0,
    'iCountTopicFavourite' => 0,
    'iCountCommentFavourite' => 0,
    'iCountBlogReads' => 0,
    'iCountFriendsUser' => 0,
    'sType' => 0,
    'sVoteDir' => 0,
    'aUserVotedStat' => 0,
    'votings_direction' => 0,
    'aUserFieldContactValues' => 0,
    'aUserFieldSocialValues' => 0,
    'oField' => 0,
    'oUserCurrent' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5bffa0d31e0cd7_09340473',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5bffa0d31e0cd7_09340473')) {function content_5bffa0d31e0cd7_09340473($_smarty_tpl) {?><?php if (!is_callable('smarty_function_component_define_params')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component_define_params.php';
if (!is_callable('smarty_function_hook')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.hook.php';
if (!is_callable('smarty_function_component')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component.php';
if (!is_callable('smarty_function_date_format')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.date_format.php';
if (!is_callable('smarty_function_router')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.router.php';
if (!is_callable('smarty_function_request_filter')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.request_filter.php';
?><?php $_smarty_tpl->tpl_vars['component'] = new Smarty_variable('p-user-profile', null, 0);?>
<?php echo smarty_function_component_define_params(array('params'=>array('user')),$_smarty_tpl);?>


<div class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
">
    <?php $_smarty_tpl->tpl_vars['oSession'] = new Smarty_variable($_smarty_tpl->tpl_vars['user']->value->getSession(), null, 0);?>

    <div class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-body">
        
        <?php echo smarty_function_hook(array('run'=>'admin_user_profile_center_info','oUserProfile'=>$_smarty_tpl->tpl_vars['user']->value),$_smarty_tpl);?>


        
        <?php ob_start();?><?php echo smarty_function_component(array('_default_short'=>'admin:p-user.form','user'=>$_smarty_tpl->tpl_vars['user']->value),$_smarty_tpl);?>
<?php $_tmp1=ob_get_clean();?><?php echo smarty_function_component(array('_default_short'=>'admin:p-user.profile-section','title'=>$_smarty_tpl->tpl_vars['aLang']->value['plugin']['admin']['users']['profile']['info']['resume'],'content'=>$_tmp1),$_smarty_tpl);?>


        
        <?php $_smarty_tpl->_capture_stack[0][] = array('profile_section', null, null); ob_start(); ?>
            <?php ob_start();?><?php echo smarty_function_date_format(array('date'=>$_smarty_tpl->tpl_vars['user']->value->getDateRegister()),$_smarty_tpl);?>
<?php $_tmp2=ob_get_clean();?><?php ob_start();?><?php echo smarty_function_router(array('page'=>'admin/users/list'),$_smarty_tpl);?>
<?php $_tmp3=ob_get_clean();?><?php ob_start();?><?php echo smarty_function_request_filter(array('name'=>array('ip_register'),'value'=>array($_smarty_tpl->tpl_vars['user']->value->getIpRegister())),$_smarty_tpl);?>
<?php $_tmp4=ob_get_clean();?><?php $_smarty_tpl->tpl_vars['list'] = new Smarty_variable(array(array('label'=>$_smarty_tpl->tpl_vars['aLang']->value['plugin']['admin']['users']['profile']['info']['reg_date'],'content'=>$_tmp2),array('label'=>$_smarty_tpl->tpl_vars['aLang']->value['plugin']['admin']['users']['profile']['info']['ip'],'content'=>"<a href=\"".$_tmp3.$_tmp4."\" title=\"".((string)$_smarty_tpl->tpl_vars['aLang']->value['plugin']['admin']['users']['profile']['info']['search_this_ip'])."\">".((string)$_smarty_tpl->tpl_vars['user']->value->getIpRegister())."</a>")), null, 0);?>

            <?php if ($_smarty_tpl->tpl_vars['oSession']->value){?>
                <?php ob_start();?><?php echo smarty_function_date_format(array('date'=>$_smarty_tpl->tpl_vars['oSession']->value->getDateLast()),$_smarty_tpl);?>
<?php $_tmp5=ob_get_clean();?><?php $_smarty_tpl->createLocalArrayVariable('list', null, 0);
$_smarty_tpl->tpl_vars['list']->value[] = array('label'=>$_smarty_tpl->tpl_vars['aLang']->value['plugin']['admin']['users']['profile']['info']['last_visit'],'content'=>$_tmp5);?>
                <?php ob_start();?><?php echo smarty_function_router(array('page'=>'admin/users/list'),$_smarty_tpl);?>
<?php $_tmp6=ob_get_clean();?><?php ob_start();?><?php echo smarty_function_request_filter(array('name'=>array('session_ip_last'),'value'=>array($_smarty_tpl->tpl_vars['oSession']->value->getIpLast())),$_smarty_tpl);?>
<?php $_tmp7=ob_get_clean();?><?php $_smarty_tpl->createLocalArrayVariable('list', null, 0);
$_smarty_tpl->tpl_vars['list']->value[] = array('label'=>$_smarty_tpl->tpl_vars['aLang']->value['plugin']['admin']['users']['profile']['info']['ip'],'content'=>"<a href=\"".$_tmp6.$_tmp7."\" title=\"".((string)$_smarty_tpl->tpl_vars['aLang']->value['plugin']['admin']['users']['profile']['info']['search_this_ip'])."\">".((string)$_smarty_tpl->tpl_vars['oSession']->value->getIpLast())."</a>");?>
            <?php }?>

            <?php echo smarty_function_component(array('_default_short'=>'admin:info-list','list'=>$_smarty_tpl->tpl_vars['list']->value),$_smarty_tpl);?>

        <?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>

        <?php echo smarty_function_component(array('_default_short'=>'admin:p-user.profile-section','title'=>'Авторизация','content'=>Smarty::$_smarty_vars['capture']['profile_section']),$_smarty_tpl);?>


        
        <?php $_smarty_tpl->_capture_stack[0][] = array('profile_section', null, null); ob_start(); ?>
            <div class="p-user-table-stats">
                <div class="p-user-table-stats-row">
                    <div class="p-user-table-stats-header"><?php echo $_smarty_tpl->tpl_vars['aLang']->value['plugin']['admin']['users']['profile']['info']['created'];?>
</div>
                    <ul>
                        <li><a href="<?php echo $_smarty_tpl->tpl_vars['user']->value->getUserWebPath();?>
created/topics/" class="link-border"><span><?php echo $_smarty_tpl->tpl_vars['iCountTopicUser']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['aLang']->value['plugin']['admin']['users']['profile']['info']['topics'];?>
</span></a></li>
                        <li><a href="<?php echo $_smarty_tpl->tpl_vars['user']->value->getUserWebPath();?>
created/comments/" class="link-border"><span><?php echo $_smarty_tpl->tpl_vars['iCountCommentUser']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['aLang']->value['plugin']['admin']['users']['profile']['info']['comments'];?>
</span></a></li>
                        <li><span><?php echo $_smarty_tpl->tpl_vars['iCountBlogsUser']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['aLang']->value['plugin']['admin']['users']['profile']['info']['blogs'];?>
</span></li>
                    </ul>
                </div>

                <div class="p-user-table-stats-row">
                    <div class="p-user-table-stats-header"><?php echo $_smarty_tpl->tpl_vars['aLang']->value['plugin']['admin']['users']['profile']['info']['fav'];?>
</div>
                    <ul>
                        <li><a href="<?php echo $_smarty_tpl->tpl_vars['user']->value->getUserWebPath();?>
favourites/topics/" class="link-border"><span><?php echo $_smarty_tpl->tpl_vars['iCountTopicFavourite']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['aLang']->value['plugin']['admin']['users']['profile']['info']['topics'];?>
</span></a></li>
                        <li><a href="<?php echo $_smarty_tpl->tpl_vars['user']->value->getUserWebPath();?>
favourites/comments/" class="link-border"><span><?php echo $_smarty_tpl->tpl_vars['iCountCommentFavourite']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['aLang']->value['plugin']['admin']['users']['profile']['info']['comments'];?>
</span></a></li>
                    </ul>
                </div>

                <div class="p-user-table-stats-row">
                    <div class="p-user-table-stats-header"><?php echo $_smarty_tpl->tpl_vars['aLang']->value['plugin']['admin']['users']['profile']['info']['reads'];?>
</div>
                    <ul>
                        <li><span><?php echo $_smarty_tpl->tpl_vars['iCountBlogReads']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['aLang']->value['plugin']['admin']['users']['profile']['info']['blogs'];?>
</span></li>
                    </ul>
                </div>

                <div class="p-user-table-stats-row">
                    <div class="p-user-table-stats-header"><?php echo $_smarty_tpl->tpl_vars['aLang']->value['plugin']['admin']['users']['profile']['info']['has'];?>
</div>
                    <ul>
                        <li><a href="<?php echo $_smarty_tpl->tpl_vars['user']->value->getUserWebPath();?>
friends/" class="link-border"><span><?php echo $_smarty_tpl->tpl_vars['iCountFriendsUser']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['aLang']->value['plugin']['admin']['users']['profile']['info']['friends'];?>
</span></a></li>
                    </ul>
                </div>
            </div>
        <?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>

        <?php echo smarty_function_component(array('_default_short'=>'admin:p-user.profile-section','title'=>$_smarty_tpl->tpl_vars['aLang']->value['plugin']['admin']['users']['profile']['info']['stats_title'],'content'=>Smarty::$_smarty_vars['capture']['profile_section']),$_smarty_tpl);?>


        
        <?php $_smarty_tpl->tpl_vars['votings_direction'] = new Smarty_variable(array('plus'=>'<i class="p-icon-stats-up"></i>','minus'=>'<i class="p-icon-stats-down"></i>','abstain'=>'&mdash;'), null, 0);?>

        <?php $_smarty_tpl->_capture_stack[0][] = array('profile_section', null, null); ob_start(); ?>
            <div class="p-user-table-stats">
            <?php  $_smarty_tpl->tpl_vars['sType'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['sType']->_loop = false;
 $_from = array('topic','comment','blog','user'); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['sType']->key => $_smarty_tpl->tpl_vars['sType']->value){
$_smarty_tpl->tpl_vars['sType']->_loop = true;
?>
                <div class="p-user-table-stats-row">
                    <div class="p-user-table-stats-header">
                        <a href="<?php echo smarty_function_router(array('page'=>"admin/users/votes/".((string)$_smarty_tpl->tpl_vars['user']->value->getId())),$_smarty_tpl);?>
?filter[type]=<?php echo $_smarty_tpl->tpl_vars['sType']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['aLang']->value['plugin']['admin']['users']['profile']['info']['votings'][$_smarty_tpl->tpl_vars['sType']->value];?>
</a>
                    </div>
                    <ul>
                        <?php  $_smarty_tpl->tpl_vars['sVoteDir'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['sVoteDir']->_loop = false;
 $_from = array('plus','minus','abstain'); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['sVoteDir']->key => $_smarty_tpl->tpl_vars['sVoteDir']->value){
$_smarty_tpl->tpl_vars['sVoteDir']->_loop = true;
?>
                            <?php if ($_smarty_tpl->tpl_vars['aUserVotedStat']->value[$_smarty_tpl->tpl_vars['sType']->value][$_smarty_tpl->tpl_vars['sVoteDir']->value]){?>
                                <li title="<?php echo $_smarty_tpl->tpl_vars['sVoteDir']->value;?>
">
                                    <a href="<?php echo smarty_function_router(array('page'=>"admin/users/votes/".((string)$_smarty_tpl->tpl_vars['user']->value->getId())),$_smarty_tpl);?>
?filter[type]=<?php echo $_smarty_tpl->tpl_vars['sType']->value;?>
&filter[dir]=<?php echo $_smarty_tpl->tpl_vars['sVoteDir']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['aUserVotedStat']->value[$_smarty_tpl->tpl_vars['sType']->value][$_smarty_tpl->tpl_vars['sVoteDir']->value];?>
</a>
                                    <?php echo $_smarty_tpl->tpl_vars['votings_direction']->value[$_smarty_tpl->tpl_vars['sVoteDir']->value];?>

                                </li>
                            <?php }?>
                        <?php } ?>
                    </ul>
                </div>
            <?php } ?>
            </div>
        <?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>

        <?php echo smarty_function_component(array('_default_short'=>'admin:p-user.profile-section','title'=>$_smarty_tpl->tpl_vars['aLang']->value['plugin']['admin']['users']['profile']['info']['votings_title'],'content'=>Smarty::$_smarty_vars['capture']['profile_section']),$_smarty_tpl);?>


        
        <?php $_smarty_tpl->tpl_vars['aUserFieldContactValues'] = new Smarty_variable($_smarty_tpl->tpl_vars['user']->value->getUserFieldValues(true,array('contact')), null, 0);?>
        <?php $_smarty_tpl->tpl_vars['aUserFieldSocialValues'] = new Smarty_variable($_smarty_tpl->tpl_vars['user']->value->getUserFieldValues(true,array('social')), null, 0);?>

        <?php if ($_smarty_tpl->tpl_vars['aUserFieldContactValues']->value||$_smarty_tpl->tpl_vars['aUserFieldSocialValues']->value){?>
            <?php $_smarty_tpl->_capture_stack[0][] = array('profile_section', null, null); ob_start(); ?>
                <div class="p-user-contacts ls-clearfix">
                    <?php if ($_smarty_tpl->tpl_vars['aUserFieldContactValues']->value){?>
                        <ul class="p-user-contact-list">
                            <?php  $_smarty_tpl->tpl_vars['oField'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['oField']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['aUserFieldContactValues']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['oField']->key => $_smarty_tpl->tpl_vars['oField']->value){
$_smarty_tpl->tpl_vars['oField']->_loop = true;
?>
                                <li>
                                    <i class="fa p-icon--contact p-icon--contact-<?php echo $_smarty_tpl->tpl_vars['oField']->value->getName();?>
" title="<?php echo $_smarty_tpl->tpl_vars['oField']->value->getName();?>
"></i>
                                    <?php echo $_smarty_tpl->tpl_vars['oField']->value->getValue(true,true);?>

                                </li>
                            <?php } ?>
                        </ul>
                    <?php }?>

                    <?php if ($_smarty_tpl->tpl_vars['aUserFieldSocialValues']->value){?>
                        <ul class="p-user-contact-list">
                            <?php  $_smarty_tpl->tpl_vars['oField'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['oField']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['aUserFieldSocialValues']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['oField']->key => $_smarty_tpl->tpl_vars['oField']->value){
$_smarty_tpl->tpl_vars['oField']->_loop = true;
?>
                                <li>
                                    <i class="fa p-icon--contact p-icon--contact-<?php echo $_smarty_tpl->tpl_vars['oField']->value->getName();?>
" title="<?php echo $_smarty_tpl->tpl_vars['oField']->value->getName();?>
"></i>
                                    <?php echo $_smarty_tpl->tpl_vars['oField']->value->getValue(true,true);?>

                                </li>
                            <?php } ?>
                        </ul>
                    <?php }?>
                </div>
            <?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>

            <?php echo smarty_function_component(array('_default_short'=>'admin:p-user.profile-section','title'=>'Контакты','content'=>Smarty::$_smarty_vars['capture']['profile_section']),$_smarty_tpl);?>

        <?php }?>
    </div>

    <aside class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-aside">
        <div class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-aside-block">
            <a href="<?php echo $_smarty_tpl->tpl_vars['user']->value->getUserWebPath();?>
"><img src="<?php echo $_smarty_tpl->tpl_vars['user']->value->getProfileFotoPath();?>
" alt="photo" class="photo" /></a>
        </div>

        <?php if ($_smarty_tpl->tpl_vars['oUserCurrent']->value->getId()!=$_smarty_tpl->tpl_vars['user']->value->getId()){?>
            <div class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-aside-block">
                <?php echo smarty_function_component(array('_default_short'=>'note','targetId'=>$_smarty_tpl->tpl_vars['user']->value->getId(),'note'=>$_smarty_tpl->tpl_vars['user']->value->getUserNote(),'classes'=>'js-user-profile-note'),$_smarty_tpl);?>

            </div>
        <?php }?>

        <div class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-aside-block">
            <ul class="p-user-menu">
                <li class="p-user-menu-item"><a href="<?php echo $_smarty_tpl->tpl_vars['user']->value->getUserWebPath();?>
"><span><?php echo $_smarty_tpl->tpl_vars['aLang']->value['plugin']['admin']['users']['profile']['middle_bar']['profile'];?>
</span></a></li>
                <li class="p-user-menu-item"><a href="<?php echo $_smarty_tpl->tpl_vars['user']->value->getUserWebPath();?>
created/"><span><?php echo $_smarty_tpl->tpl_vars['aLang']->value['plugin']['admin']['users']['profile']['middle_bar']['publications'];?>
</span></a></li>
                <li class="p-user-menu-item"><a href="<?php echo $_smarty_tpl->tpl_vars['user']->value->getUserWebPath();?>
stream/"><span><?php echo $_smarty_tpl->tpl_vars['aLang']->value['plugin']['admin']['users']['profile']['middle_bar']['activity'];?>
</span></a></li>
                <li class="p-user-menu-item"><a href="<?php echo $_smarty_tpl->tpl_vars['user']->value->getUserWebPath();?>
friends/"><span><?php echo $_smarty_tpl->tpl_vars['aLang']->value['plugin']['admin']['users']['profile']['middle_bar']['friends'];?>
</span></a></li>
                <li class="p-user-menu-item"><a href="<?php echo $_smarty_tpl->tpl_vars['user']->value->getUserWebPath();?>
favourites/"><span><?php echo $_smarty_tpl->tpl_vars['aLang']->value['plugin']['admin']['users']['profile']['middle_bar']['fav'];?>
</span></a></li>
            </ul>
        </div>
    </aside>
</div><?php }} ?>