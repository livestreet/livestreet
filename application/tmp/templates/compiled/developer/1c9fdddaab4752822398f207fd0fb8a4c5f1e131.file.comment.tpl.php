<?php /* Smarty version Smarty-3.1.13, created on 2018-11-25 11:43:29
         compiled from "/var/www/ls.new/application/frontend/components/comment/comment.tpl" */ ?>
<?php /*%%SmartyHeaderCode:19766787495bfa60b1efc9b0-68086872%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1c9fdddaab4752822398f207fd0fb8a4c5f1e131' => 
    array (
      0 => '/var/www/ls.new/application/frontend/components/comment/comment.tpl',
      1 => 1543035802,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '19766787495bfa60b1efc9b0-68086872',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'useEdit' => 0,
    'hookPrefix' => 0,
    'comment' => 0,
    'commentId' => 0,
    'target' => 0,
    'useVote' => 0,
    'authorId' => 0,
    'user' => 0,
    'isDeleted' => 0,
    'oUserCurrent' => 0,
    'dateReadLast' => 0,
    'component' => 0,
    'mods' => 0,
    'classes' => 0,
    'attributes' => 0,
    'params' => 0,
    'showPath' => 0,
    'useFavourite' => 0,
    'permalink' => 0,
    'aLang' => 0,
    'useScroll' => 0,
    'showReply' => 0,
    'ls_comment_edit_text' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5bfa60b23d94c8_73793991',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5bfa60b23d94c8_73793991')) {function content_5bfa60b23d94c8_73793991($_smarty_tpl) {?><?php if (!is_callable('smarty_function_component_define_params')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component_define_params.php';
if (!is_callable('smarty_function_router')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.router.php';
if (!is_callable('smarty_function_cmods')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.cmods.php';
if (!is_callable('smarty_function_cattr')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.cattr.php';
if (!is_callable('smarty_function_hook')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.hook.php';
if (!is_callable('smarty_function_component')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component.php';
if (!is_callable('smarty_function_date_format')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.date_format.php';
if (!is_callable('smarty_modifier_declension')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/modifier.declension.php';
?>


<?php $_smarty_tpl->tpl_vars['component'] = new Smarty_variable('ls-comment', null, 0);?>
<?php echo smarty_function_component_define_params(array('params'=>array('hookPrefix','dateReadLast','showPath','showReply','authorId','comment','useFavourite','useScroll','useVote','useEdit','mods','classes','attributes')),$_smarty_tpl);?>



<?php $_smarty_tpl->tpl_vars['useEdit'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['useEdit']->value)===null||$tmp==='' ? true : $tmp), null, 0);?>
<?php $_smarty_tpl->tpl_vars['hookPrefix'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['hookPrefix']->value)===null||$tmp==='' ? 'comment' : $tmp), null, 0);?>
<?php $_smarty_tpl->tpl_vars['isDeleted'] = new Smarty_variable($_smarty_tpl->tpl_vars['comment']->value->getDelete(), null, 0);?>
<?php $_smarty_tpl->tpl_vars['user'] = new Smarty_variable($_smarty_tpl->tpl_vars['comment']->value->getUser(), null, 0);?>
<?php $_smarty_tpl->tpl_vars['commentId'] = new Smarty_variable($_smarty_tpl->tpl_vars['comment']->value->getId(), null, 0);?>
<?php $_smarty_tpl->tpl_vars['target'] = new Smarty_variable($_smarty_tpl->tpl_vars['comment']->value->getTarget(), null, 0);?>



<?php ob_start();?><?php echo smarty_function_router(array('page'=>'comments'),$_smarty_tpl);?>
<?php $_tmp1=ob_get_clean();?><?php ob_start();?><?php if ($_smarty_tpl->tpl_vars['target']->value){?><?php echo (string)$_smarty_tpl->tpl_vars['target']->value->getUrl();?><?php }?><?php $_tmp2=ob_get_clean();?><?php $_smarty_tpl->tpl_vars['permalink'] = new Smarty_variable(Config::Get('module.comment.use_nested') ? $_tmp1.((string)$_smarty_tpl->tpl_vars['commentId']->value) : $_tmp2."#comment".((string)$_smarty_tpl->tpl_vars['commentId']->value), null, 0);?>




<?php if ($_smarty_tpl->tpl_vars['useVote']->value&&$_smarty_tpl->tpl_vars['comment']->value->isBad()){?>
    <?php $_smarty_tpl->tpl_vars['mods'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['mods']->value)." bad", null, 0);?>
<?php }?>


<?php if ($_smarty_tpl->tpl_vars['authorId']->value==$_smarty_tpl->tpl_vars['user']->value->getId()){?>
    <?php $_smarty_tpl->tpl_vars['mods'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['mods']->value)." author", null, 0);?>
<?php }?>


<?php if ($_smarty_tpl->tpl_vars['isDeleted']->value){?>
    <?php $_smarty_tpl->tpl_vars['mods'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['mods']->value)." deleted", null, 0);?>


<?php }elseif($_smarty_tpl->tpl_vars['oUserCurrent']->value&&$_smarty_tpl->tpl_vars['comment']->value->getUserId()==$_smarty_tpl->tpl_vars['oUserCurrent']->value->getId()){?>
    <?php $_smarty_tpl->tpl_vars['mods'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['mods']->value)." self", null, 0);?>


<?php }elseif($_smarty_tpl->tpl_vars['dateReadLast']->value&&strtotime($_smarty_tpl->tpl_vars['dateReadLast']->value)<=strtotime($_smarty_tpl->tpl_vars['comment']->value->getDate())){?>
    <?php $_smarty_tpl->tpl_vars['mods'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['mods']->value)." new", null, 0);?>
<?php }?>



<section class   = "<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
 <?php echo smarty_function_cmods(array('name'=>$_smarty_tpl->tpl_vars['component']->value,'mods'=>$_smarty_tpl->tpl_vars['mods']->value),$_smarty_tpl);?>
 <?php echo $_smarty_tpl->tpl_vars['classes']->value;?>
 open js-comment"
         id      = "comment<?php echo $_smarty_tpl->tpl_vars['commentId']->value;?>
"
         data-id = "<?php echo $_smarty_tpl->tpl_vars['commentId']->value;?>
"
         data-parent-id = "<?php echo $_smarty_tpl->tpl_vars['comment']->value->getPid();?>
"
         <?php echo smarty_function_cattr(array('list'=>$_smarty_tpl->tpl_vars['attributes']->value),$_smarty_tpl);?>
>
    
    <?php echo smarty_function_hook(array('run'=>((string)$_smarty_tpl->tpl_vars['hookPrefix']->value)."_comment_begin",'params'=>$_smarty_tpl->tpl_vars['params']->value),$_smarty_tpl);?>


    
    <?php if ($_smarty_tpl->tpl_vars['showPath']->value){?>
        <div class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-path">
            <?php $_smarty_tpl->tpl_vars['target'] = new Smarty_variable($_smarty_tpl->tpl_vars['comment']->value->getTarget(), null, 0);?>

            <a href="<?php echo $_smarty_tpl->tpl_vars['target']->value->getUrl();?>
" class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-path-target"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['target']->value->getTitle(), ENT_QUOTES, 'UTF-8', true);?>
</a>
            <a href="<?php echo $_smarty_tpl->tpl_vars['target']->value->getUrl();?>
#comments" class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-path-comments">(<?php echo $_smarty_tpl->tpl_vars['target']->value->getCountComment();?>
)</a>
        </div>
    <?php }?>

    
    <?php if (!$_smarty_tpl->tpl_vars['isDeleted']->value||($_smarty_tpl->tpl_vars['oUserCurrent']->value&&$_smarty_tpl->tpl_vars['oUserCurrent']->value->isAdministrator())){?>
        
        <a href="<?php echo $_smarty_tpl->tpl_vars['user']->value->getUserWebPath();?>
" class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-avatar">
            <img src="<?php echo $_smarty_tpl->tpl_vars['user']->value->getProfileAvatarPath(64);?>
" alt="<?php echo $_smarty_tpl->tpl_vars['user']->value->getDisplayName();?>
" />
        </a>

        
        <?php if ($_smarty_tpl->tpl_vars['oUserCurrent']->value&&$_smarty_tpl->tpl_vars['useFavourite']->value){?>
            <?php echo smarty_function_component(array('_default_short'=>'favourite','classes'=>((string)$_smarty_tpl->tpl_vars['component']->value)."-favourite js-comment-favourite",'target'=>$_smarty_tpl->tpl_vars['comment']->value),$_smarty_tpl);?>

        <?php }?>

        
        <ul class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-info ls-clearfix">
            
            <?php echo smarty_function_hook(array('run'=>((string)$_smarty_tpl->tpl_vars['hookPrefix']->value)."_info_begin",'params'=>$_smarty_tpl->tpl_vars['params']->value),$_smarty_tpl);?>


            
            <?php echo smarty_function_component(array('_default_short'=>'comment.info-item','classes'=>((string)$_smarty_tpl->tpl_vars['component']->value)."-username",'link'=>array('url'=>$_smarty_tpl->tpl_vars['user']->value->getUserWebPath()),'text'=>$_smarty_tpl->tpl_vars['user']->value->getDisplayName()),$_smarty_tpl);?>


            
            
            <li class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-date">
                <a href="<?php echo $_smarty_tpl->tpl_vars['permalink']->value;?>
" title="<?php echo $_smarty_tpl->tpl_vars['aLang']->value['comments']['comment']['url'];?>
">
                    <time datetime="<?php echo smarty_function_date_format(array('date'=>$_smarty_tpl->tpl_vars['comment']->value->getDate(),'format'=>'c'),$_smarty_tpl);?>
" title="<?php echo smarty_function_date_format(array('date'=>$_smarty_tpl->tpl_vars['comment']->value->getDate(),'format'=>"j F Y, H:i"),$_smarty_tpl);?>
">
                        <?php echo smarty_function_date_format(array('date'=>$_smarty_tpl->tpl_vars['comment']->value->getDate(),'hours_back'=>"12",'minutes_back'=>"60",'now'=>"60",'day'=>"day H:i",'format'=>"j F Y, H:i"),$_smarty_tpl);?>

                    </time>
                </a>
            </li>

            
            <?php if ((($tmp = @$_smarty_tpl->tpl_vars['useScroll']->value)===null||$tmp==='' ? true : $tmp)){?>
                <?php if ($_smarty_tpl->tpl_vars['comment']->value->getPid()){?>
                    <li class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-scroll-to <?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-scroll-to-parent js-comment-scroll-to-parent"
                        title="<?php echo $_smarty_tpl->tpl_vars['aLang']->value['comments']['comment']['scroll_to_parent'];?>
">↑</li>
                <?php }?>

                
                <li class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-scroll-to <?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-scroll-to-child js-comment-scroll-to-child"
                    title="<?php echo $_smarty_tpl->tpl_vars['aLang']->value['comments']['comment']['scroll_to_child'];?>
">↓</li>
            <?php }?>

            
            <?php if ($_smarty_tpl->tpl_vars['useVote']->value){?>
                <li>
                    
                    <?php echo smarty_function_component(array('_default_short'=>'vote','classes'=>((string)$_smarty_tpl->tpl_vars['component']->value)."-vote js-comment-vote",'target'=>$_smarty_tpl->tpl_vars['comment']->value,'isLocked'=>($_smarty_tpl->tpl_vars['oUserCurrent']->value&&$_smarty_tpl->tpl_vars['oUserCurrent']->value->getId()==$_smarty_tpl->tpl_vars['user']->value->getId())||strtotime($_smarty_tpl->tpl_vars['comment']->value->getDate())<time()-Config::Get('acl.vote.comment.limit_time')),$_smarty_tpl);?>

                </li>
            <?php }?>

            
            <?php echo smarty_function_hook(array('run'=>((string)$_smarty_tpl->tpl_vars['hookPrefix']->value)."_info_end",'params'=>$_smarty_tpl->tpl_vars['params']->value),$_smarty_tpl);?>

        </ul>

        
        <div class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-content">
            
            <?php echo smarty_function_hook(array('run'=>((string)$_smarty_tpl->tpl_vars['hookPrefix']->value)."_content_begin",'params'=>$_smarty_tpl->tpl_vars['params']->value),$_smarty_tpl);?>


            <div class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-text ls-text">
                <?php echo $_smarty_tpl->tpl_vars['comment']->value->getText();?>

            </div>

            
            <?php echo smarty_function_hook(array('run'=>((string)$_smarty_tpl->tpl_vars['hookPrefix']->value)."_content_end",'params'=>$_smarty_tpl->tpl_vars['params']->value),$_smarty_tpl);?>

        </div>

        
        <?php if ($_smarty_tpl->tpl_vars['comment']->value->getDateEdit()){?>
            <div class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-edit-info">
                <?php echo $_smarty_tpl->tpl_vars['aLang']->value['comments']['comment']['edit_info'];?>
:

                <span class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-edit-info-time js-comment-edit-time">
                    <?php echo smarty_function_date_format(array('date'=>$_smarty_tpl->tpl_vars['comment']->value->getDateEdit(),'hours_back'=>"12",'minutes_back'=>"60",'now'=>"60",'day'=>"day H:i",'format'=>"j F Y, H:i"),$_smarty_tpl);?>

                </span>

                <?php if ($_smarty_tpl->tpl_vars['comment']->value->getCountEdit()>1){?>
                    (<?php echo $_smarty_tpl->tpl_vars['comment']->value->getCountEdit();?>
 <?php echo smarty_modifier_declension($_smarty_tpl->tpl_vars['comment']->value->getCountEdit(),$_smarty_tpl->tpl_vars['aLang']->value['common']['times_declension']);?>
)
                <?php }?>
            </div>
        <?php }?>

        
        <ul class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-actions ls-clearfix">
            
            <?php echo smarty_function_hook(array('run'=>((string)$_smarty_tpl->tpl_vars['hookPrefix']->value)."_actions_begin",'params'=>$_smarty_tpl->tpl_vars['params']->value),$_smarty_tpl);?>


            
            <?php if ($_smarty_tpl->tpl_vars['oUserCurrent']->value&&!$_smarty_tpl->tpl_vars['isDeleted']->value&&(($tmp = @$_smarty_tpl->tpl_vars['showReply']->value)===null||$tmp==='' ? true : $tmp)){?>
                <?php echo smarty_function_component(array('_default_short'=>'comment.actions-item','link'=>array('classes'=>'js-comment-reply','attributes'=>array('data-id'=>$_smarty_tpl->tpl_vars['commentId']->value)),'text'=>$_smarty_tpl->tpl_vars['aLang']->value['comments']['comment']['reply']),$_smarty_tpl);?>

            <?php }?>

            
            <?php echo smarty_function_component(array('_default_short'=>'comment.actions-item','classes'=>((string)$_smarty_tpl->tpl_vars['component']->value)."-fold open",'link'=>array('classes'=>'js-comment-fold','attributes'=>array('data-id'=>$_smarty_tpl->tpl_vars['commentId']->value)),'text'=>$_smarty_tpl->tpl_vars['aLang']->value['comments']['folding']['fold']),$_smarty_tpl);?>


            
            <?php if ($_smarty_tpl->tpl_vars['useEdit']->value&&$_smarty_tpl->tpl_vars['oUserCurrent']->value&&$_smarty_tpl->tpl_vars['comment']->value->IsAllowEdit()){?>
                <?php $_smarty_tpl->_capture_stack[0][] = array('default', "ls_comment_edit_text", null); ob_start(); ?>
                    <?php echo $_smarty_tpl->tpl_vars['aLang']->value['common']['edit'];?>


                    
                    
                    <?php if ($_smarty_tpl->tpl_vars['comment']->value->getEditTimeRemaining()){?>
                        (<span class="js-comment-update-timer" data-seconds="<?php echo $_smarty_tpl->tpl_vars['comment']->value->getEditTimeRemaining();?>
">...</span>)
                    <?php }?>
                <?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>

                <?php echo smarty_function_component(array('_default_short'=>'comment.actions-item','link'=>array('classes'=>'js-comment-update','attributes'=>array('data-id'=>$_smarty_tpl->tpl_vars['commentId']->value)),'text'=>$_smarty_tpl->tpl_vars['ls_comment_edit_text']->value),$_smarty_tpl);?>

            <?php }?>

            
            <?php if ($_smarty_tpl->tpl_vars['oUserCurrent']->value&&$_smarty_tpl->tpl_vars['comment']->value->IsAllowDelete()){?>
                <?php echo smarty_function_component(array('_default_short'=>'comment.actions-item','link'=>array('classes'=>'js-comment-remove','attributes'=>array('data-id'=>$_smarty_tpl->tpl_vars['commentId']->value)),'text'=>($_smarty_tpl->tpl_vars['isDeleted']->value ? $_smarty_tpl->tpl_vars['aLang']->value['comments']['comment']['restore'] : $_smarty_tpl->tpl_vars['aLang']->value['common']['remove'])),$_smarty_tpl);?>

            <?php }?>

            
            <?php echo smarty_function_hook(array('run'=>((string)$_smarty_tpl->tpl_vars['hookPrefix']->value)."_actions_end",'params'=>$_smarty_tpl->tpl_vars['params']->value),$_smarty_tpl);?>

        </ul>
    <?php }else{ ?>
        <?php echo $_smarty_tpl->tpl_vars['aLang']->value['comments']['comment']['deleted'];?>

    <?php }?>

    
    <?php echo smarty_function_hook(array('run'=>((string)$_smarty_tpl->tpl_vars['hookPrefix']->value)."_comment_end",'params'=>$_smarty_tpl->tpl_vars['params']->value),$_smarty_tpl);?>

</section><?php }} ?>