<?php /* Smarty version Smarty-3.1.13, created on 2018-11-25 11:43:22
         compiled from "/var/www/ls.new/application/frontend/components/topic/topic.tpl" */ ?>
<?php /*%%SmartyHeaderCode:8857179705bfa60aa8e5905-19220056%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '805e9dddfb1a49ef206441dff9649290d620b42e' => 
    array (
      0 => '/var/www/ls.new/application/frontend/components/topic/topic.tpl',
      1 => 1543035802,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '8857179705bfa60aa8e5905-19220056',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'topic' => 0,
    'type' => 0,
    'isList' => 0,
    'mods' => 0,
    'classes' => 0,
    'component' => 0,
    'attributes' => 0,
    '_headingTag' => 0,
    'isPreview' => 0,
    'blog' => 0,
    'isDeferred' => 0,
    'aLang' => 0,
    'LIVESTREET_SECURITY_KEY' => 0,
    'items' => 0,
    'previewImage' => 0,
    'favourite' => 0,
    'oUserCurrent' => 0,
    'isExpired' => 0,
    'user' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5bfa60aacb8f45_33252587',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5bfa60aacb8f45_33252587')) {function content_5bfa60aacb8f45_33252587($_smarty_tpl) {?><?php if (!is_callable('smarty_function_component_define_params')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component_define_params.php';
if (!is_callable('smarty_function_cmods')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.cmods.php';
if (!is_callable('smarty_function_cattr')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.cattr.php';
if (!is_callable('smarty_function_lang')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.lang.php';
if (!is_callable('smarty_function_component')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component.php';
if (!is_callable('smarty_function_date_format')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.date_format.php';
if (!is_callable('smarty_block_hookb')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/block.hookb.php';
?>

<?php $_smarty_tpl->tpl_vars['component'] = new Smarty_variable('ls-topic', null, 0);?>
<?php echo smarty_function_component_define_params(array('params'=>array('type','topic','isPreview','isList','mods','classes','attributes')),$_smarty_tpl);?>


<?php $_smarty_tpl->tpl_vars['user'] = new Smarty_variable($_smarty_tpl->tpl_vars['topic']->value->getUser(), null, 0);?>
<?php $_smarty_tpl->tpl_vars['type'] = new Smarty_variable($_smarty_tpl->tpl_vars['topic']->value->getType() ? $_smarty_tpl->tpl_vars['topic']->value->getType() : $_smarty_tpl->tpl_vars['type']->value, null, 0);?>

<?php if (!$_smarty_tpl->tpl_vars['isList']->value){?>
    <?php $_smarty_tpl->tpl_vars['mods'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['mods']->value)." single", null, 0);?>
<?php }?>

<?php $_smarty_tpl->tpl_vars['classes'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['classes']->value)." topic js-topic", null, 0);?>



<article class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
 <?php echo smarty_function_cmods(array('name'=>$_smarty_tpl->tpl_vars['component']->value,'mods'=>$_smarty_tpl->tpl_vars['mods']->value),$_smarty_tpl);?>
 <?php echo $_smarty_tpl->tpl_vars['classes']->value;?>
" <?php echo smarty_function_cattr(array('list'=>$_smarty_tpl->tpl_vars['attributes']->value),$_smarty_tpl);?>
>
    
    
        <header class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-header">
            <?php $_smarty_tpl->tpl_vars['_headingTag'] = new Smarty_variable($_smarty_tpl->tpl_vars['isList']->value ? Config::Get('view.seo.topic_heading_list') : Config::Get('view.seo.topic_heading'), null, 0);?>

            
            <<?php echo $_smarty_tpl->tpl_vars['_headingTag']->value;?>
 class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-title ls-word-wrap">
                
                    <?php if ($_smarty_tpl->tpl_vars['topic']->value->getPublish()==0){?>
                        <?php ob_start();?><?php echo smarty_function_lang(array('_default_short'=>'topic.is_draft'),$_smarty_tpl);?>
<?php $_tmp1=ob_get_clean();?><?php echo smarty_function_component(array('_default_short'=>'icon','icon'=>'file','attributes'=>array('title'=>$_tmp1)),$_smarty_tpl);?>

                    <?php }?>

                    <?php if ($_smarty_tpl->tpl_vars['isList']->value){?>
                        <a href="<?php echo $_smarty_tpl->tpl_vars['topic']->value->getUrl();?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['topic']->value->getTitle(), ENT_QUOTES, 'UTF-8', true);?>
</a>
                    <?php }else{ ?>
                        <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['topic']->value->getTitle(), ENT_QUOTES, 'UTF-8', true);?>

                    <?php }?>
                
            </<?php echo $_smarty_tpl->tpl_vars['_headingTag']->value;?>
>

            
            <ul class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-info">
                
                    <?php if (!$_smarty_tpl->tpl_vars['isPreview']->value){?>
                        <?php  $_smarty_tpl->tpl_vars['blog'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['blog']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['topic']->value->getBlogs(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['blog']->key => $_smarty_tpl->tpl_vars['blog']->value){
$_smarty_tpl->tpl_vars['blog']->_loop = true;
?>
                            <?php if ($_smarty_tpl->tpl_vars['blog']->value->getType()!='personal'){?>
                                <li class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-info-item <?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-info-item--blog">
                                    <a href="<?php echo $_smarty_tpl->tpl_vars['blog']->value->getUrlFull();?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['blog']->value->getTitle(), ENT_QUOTES, 'UTF-8', true);?>
</a>
                                </li>
                            <?php }?>
                        <?php } ?>
                    <?php }?>

                    <?php $_smarty_tpl->tpl_vars['isDeferred'] = new Smarty_variable(strtotime($_smarty_tpl->tpl_vars['topic']->value->getDatePublish())>time() ? true : false, null, 0);?>
                    <li class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-info-item <?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-info-item--date<?php if ($_smarty_tpl->tpl_vars['isDeferred']->value){?>--deferred<?php }?>">
                        <time datetime="<?php echo smarty_function_date_format(array('date'=>$_smarty_tpl->tpl_vars['topic']->value->getDatePublish(),'format'=>'c'),$_smarty_tpl);?>
" title="<?php if ($_smarty_tpl->tpl_vars['isDeferred']->value){?><?php echo smarty_function_lang(array('_default_short'=>'topic.is_deferred'),$_smarty_tpl);?>
<?php }else{ ?><?php echo smarty_function_date_format(array('date'=>$_smarty_tpl->tpl_vars['topic']->value->getDatePublish(),'format'=>'j F Y, H:i'),$_smarty_tpl);?>
<?php }?>">
                            <?php echo smarty_function_date_format(array('date'=>$_smarty_tpl->tpl_vars['topic']->value->getDatePublish(),'format'=>"j F Y, H:i"),$_smarty_tpl);?>

                        </time>
                    </li>
                
            </ul>

            
            <?php if ($_smarty_tpl->tpl_vars['topic']->value->getIsAllowAction()&&!$_smarty_tpl->tpl_vars['isPreview']->value){?>
                
                    <?php $_smarty_tpl->tpl_vars['items'] = new Smarty_variable(array(array('icon'=>'edit','url'=>$_smarty_tpl->tpl_vars['topic']->value->getUrlEdit(),'text'=>$_smarty_tpl->tpl_vars['aLang']->value['common']['edit'],'show'=>$_smarty_tpl->tpl_vars['topic']->value->getIsAllowEdit()),array('icon'=>'trash','url'=>((string)$_smarty_tpl->tpl_vars['topic']->value->getUrlDelete())."?security_ls_key=".((string)$_smarty_tpl->tpl_vars['LIVESTREET_SECURITY_KEY']->value),'text'=>$_smarty_tpl->tpl_vars['aLang']->value['common']['remove'],'show'=>$_smarty_tpl->tpl_vars['topic']->value->getIsAllowDelete(),'classes'=>'js-confirm-remove-default')), null, 0);?>
                

                <?php echo smarty_function_component(array('_default_short'=>'actionbar','items'=>array(array('buttons'=>$_smarty_tpl->tpl_vars['items']->value))),$_smarty_tpl);?>

            <?php }?>
        </header>
    


    
    
        
        <?php $_smarty_tpl->tpl_vars['previewImage'] = new Smarty_variable($_smarty_tpl->tpl_vars['topic']->value->getPreviewImageWebPath(Config::Get('module.topic.default_preview_size')), null, 0);?>

        <?php if ($_smarty_tpl->tpl_vars['previewImage']->value){?>
            <div class="ls-topic-preview-image">
                <img src="<?php echo $_smarty_tpl->tpl_vars['previewImage']->value;?>
" />
            </div>
        <?php }?>

        <div class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-content">
            <div class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-text ls-text">
                
                    <?php if ($_smarty_tpl->tpl_vars['isList']->value&&$_smarty_tpl->tpl_vars['topic']->value->getTextShort()){?>
                        <?php echo $_smarty_tpl->tpl_vars['topic']->value->getTextShort();?>

                    <?php }else{ ?>
                        <?php echo $_smarty_tpl->tpl_vars['topic']->value->getText();?>

                    <?php }?>
                
            </div>

            
            <?php if ($_smarty_tpl->tpl_vars['isList']->value&&$_smarty_tpl->tpl_vars['topic']->value->getTextShort()){?>
                <?php ob_start();?><?php echo (($tmp = @$_smarty_tpl->tpl_vars['topic']->value->getCutText())===null||$tmp==='' ? $_smarty_tpl->tpl_vars['aLang']->value['topic']['read_more'] : $tmp);?>
<?php $_tmp2=ob_get_clean();?><?php echo smarty_function_component(array('_default_short'=>'button','classes'=>((string)$_smarty_tpl->tpl_vars['component']->value)."-cut",'url'=>((string)$_smarty_tpl->tpl_vars['topic']->value->getUrl())."#cut",'text'=>$_tmp2),$_smarty_tpl);?>

            <?php }?>
        </div>

        
        
            <?php if (!$_smarty_tpl->tpl_vars['isList']->value){?>
                <?php echo smarty_function_component(array('_default_short'=>'property','template'=>'output.list','properties'=>$_smarty_tpl->tpl_vars['topic']->value->property->getPropertyList()),$_smarty_tpl);?>

            <?php }?>
        

        
        
            <?php if (!$_smarty_tpl->tpl_vars['isList']->value){?>
                <?php echo smarty_function_component(array('_default_short'=>'poll','template'=>'list','polls'=>$_smarty_tpl->tpl_vars['topic']->value->getPolls()),$_smarty_tpl);?>

            <?php }?>
        
    


    
    
        <?php if (!$_smarty_tpl->tpl_vars['isList']->value&&$_smarty_tpl->tpl_vars['topic']->value->getTypeObject()->getParam('allow_tags')){?>
            <?php $_smarty_tpl->tpl_vars['favourite'] = new Smarty_variable($_smarty_tpl->tpl_vars['topic']->value->getFavourite(), null, 0);?>

            <?php if (!$_smarty_tpl->tpl_vars['isPreview']->value){?>
                <?php echo smarty_function_component(array('_default_short'=>'tags-personal','classes'=>'js-tags-favourite','tags'=>$_smarty_tpl->tpl_vars['topic']->value->getTagsObjects(),'tagsPersonal'=>$_smarty_tpl->tpl_vars['favourite']->value ? $_smarty_tpl->tpl_vars['favourite']->value->getTagsObjects() : array(),'isEditable'=>!$_smarty_tpl->tpl_vars['favourite']->value,'targetType'=>'topic','targetId'=>$_smarty_tpl->tpl_vars['topic']->value->getId()),$_smarty_tpl);?>

            <?php }?>
        <?php }?>

        <footer class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-footer">
            
            
                <ul class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-info ls-clearfix">
                    
                        
                        <?php if (!$_smarty_tpl->tpl_vars['isPreview']->value){?>
                            <li class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-info-item <?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-info-item--vote">
                                <?php $_smarty_tpl->tpl_vars['isExpired'] = new Smarty_variable(strtotime($_smarty_tpl->tpl_vars['topic']->value->getDatePublish())<time()-Config::Get('acl.vote.topic.limit_time'), null, 0);?>

                                <?php echo smarty_function_component(array('_default_short'=>'vote','target'=>$_smarty_tpl->tpl_vars['topic']->value,'classes'=>'js-vote-topic','mods'=>'small white topic','useAbstain'=>true,'isLocked'=>($_smarty_tpl->tpl_vars['oUserCurrent']->value&&$_smarty_tpl->tpl_vars['topic']->value->getUserId()==$_smarty_tpl->tpl_vars['oUserCurrent']->value->getId())||$_smarty_tpl->tpl_vars['isExpired']->value,'showRating'=>$_smarty_tpl->tpl_vars['topic']->value->getVote()||($_smarty_tpl->tpl_vars['oUserCurrent']->value&&$_smarty_tpl->tpl_vars['topic']->value->getUserId()==$_smarty_tpl->tpl_vars['oUserCurrent']->value->getId())||$_smarty_tpl->tpl_vars['isExpired']->value),$_smarty_tpl);?>

                            </li>
                        <?php }?>

                        
                        <li class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-info-item <?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-info-item--author">
                            <?php echo smarty_function_component(array('_default_short'=>'user','template'=>'avatar','user'=>$_smarty_tpl->tpl_vars['user']->value,'size'=>'xsmall','mods'=>'inline'),$_smarty_tpl);?>

                        </li>

                        
                        
                        <?php if ($_smarty_tpl->tpl_vars['isList']->value&&(!$_smarty_tpl->tpl_vars['topic']->value->getForbidComment()||($_smarty_tpl->tpl_vars['topic']->value->getForbidComment()&&$_smarty_tpl->tpl_vars['topic']->value->getCountComment()))){?>
                            <li class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-info-item <?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-info-item--comments">
                                <a href="<?php echo $_smarty_tpl->tpl_vars['topic']->value->getUrl();?>
#comments">
                                    <?php echo smarty_function_lang(array('name'=>'comments.comments_declension','count'=>$_smarty_tpl->tpl_vars['topic']->value->getCountComment(),'plural'=>true),$_smarty_tpl);?>

                                </a>

                                <?php if ($_smarty_tpl->tpl_vars['topic']->value->getCountCommentNew()){?><span>+<?php echo $_smarty_tpl->tpl_vars['topic']->value->getCountCommentNew();?>
</span><?php }?>
                            </li>
                        <?php }?>

                        <?php if (!$_smarty_tpl->tpl_vars['isList']->value&&!$_smarty_tpl->tpl_vars['isPreview']->value){?>
                            
                            <li class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-info-item <?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-info-item--favourite">
                                <?php echo smarty_function_component(array('_default_short'=>'favourite','classes'=>"js-favourite-topic",'target'=>$_smarty_tpl->tpl_vars['topic']->value,'attributes'=>array('data-param-target_type'=>$_smarty_tpl->tpl_vars['type']->value)),$_smarty_tpl);?>

                            </li>

                            
                            <li class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-info-item <?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-info-item--share">
                                <?php ob_start();?><?php echo smarty_function_lang(array('_default_short'=>'topic.share'),$_smarty_tpl);?>
<?php $_tmp3=ob_get_clean();?><?php echo smarty_function_component(array('_default_short'=>'icon','icon'=>'share','classes'=>"js-popover-default",'attributes'=>array('title'=>$_tmp3,'data-tooltip-target'=>"#topic_share_".((string)$_smarty_tpl->tpl_vars['topic']->value->getId()))),$_smarty_tpl);?>

                            </li>
                        <?php }?>
                     
                </ul>
             
        </footer>

        
        <?php if (!$_smarty_tpl->tpl_vars['isList']->value&&!$_smarty_tpl->tpl_vars['isPreview']->value){?>
            <div class="ls-tooltip" id="topic_share_<?php echo $_smarty_tpl->tpl_vars['topic']->value->getId();?>
">
                <div class="ls-tooltip-content js-ls-tooltip-content">
                    <?php $_smarty_tpl->smarty->_tag_stack[] = array('hookb', array('run'=>"topic_share",'topic'=>$_smarty_tpl->tpl_vars['topic']->value,'isList'=>$_smarty_tpl->tpl_vars['isList']->value)); $_block_repeat=true; echo smarty_block_hookb(array('run'=>"topic_share",'topic'=>$_smarty_tpl->tpl_vars['topic']->value,'isList'=>$_smarty_tpl->tpl_vars['isList']->value), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>

                        <div class="yashare-auto-init" data-yashareTitle="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['topic']->value->getTitle(), ENT_QUOTES, 'UTF-8', true);?>
" data-yashareLink="<?php echo $_smarty_tpl->tpl_vars['topic']->value->getUrl();?>
" data-yashareL10n="ru" data-yashareType="small" data-yashareTheme="counter" data-yashareQuickServices="yaru,vkontakte,facebook,twitter,odnoklassniki,moimir,gplus"></div>
                    <?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_hookb(array('run'=>"topic_share",'topic'=>$_smarty_tpl->tpl_vars['topic']->value,'isList'=>$_smarty_tpl->tpl_vars['isList']->value), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>

                </div>
            </div>
        <?php }?>
     
</article>
<?php }} ?>