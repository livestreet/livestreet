<?php /* Smarty version Smarty-3.1.13, created on 2018-11-25 11:43:23
         compiled from "/var/www/ls.new/application/frontend/components/comment/comments.tpl" */ ?>
<?php /*%%SmartyHeaderCode:6645512005bfa60ab57fb05-44568938%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4298c0dc7b0e784ea4a943c55a730c6e1bbf838c' => 
    array (
      0 => '/var/www/ls.new/application/frontend/components/comment/comments.tpl',
      1 => 1543035802,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '6645512005bfa60ab57fb05-44568938',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'maxLevel' => 0,
    'hookPrefix' => 0,
    'forbidAdd' => 0,
    'oUserCurrent' => 0,
    'pagination' => 0,
    'component' => 0,
    'mods' => 0,
    'classes' => 0,
    'targetType' => 0,
    'targetId' => 0,
    'lastCommentId' => 0,
    'attributes' => 0,
    'params' => 0,
    'count' => 0,
    'title' => 0,
    'titleNoComments' => 0,
    'aLang' => 0,
    'useSubscribe' => 0,
    'isSubscribed' => 0,
    'items' => 0,
    'comments' => 0,
    'authorId' => 0,
    'authorText' => 0,
    'dateReadLast' => 0,
    'commentParams' => 0,
    'hookPrefixComment' => 0,
    'forbidText' => 0,
    'addCommentText' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5bfa60ab76aaf9_38801029',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5bfa60ab76aaf9_38801029')) {function content_5bfa60ab76aaf9_38801029($_smarty_tpl) {?><?php if (!is_callable('smarty_function_component_define_params')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component_define_params.php';
if (!is_callable('smarty_function_add_block')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.add_block.php';
if (!is_callable('smarty_function_cmods')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.cmods.php';
if (!is_callable('smarty_function_cattr')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.cattr.php';
if (!is_callable('smarty_function_hook')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.hook.php';
if (!is_callable('smarty_function_lang')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.lang.php';
if (!is_callable('smarty_function_component')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component.php';
?>

<?php $_smarty_tpl->tpl_vars['component'] = new Smarty_variable('ls-comments', null, 0);?>
<?php echo smarty_function_component_define_params(array('params'=>array('hookPrefix','hookPrefixComment','addCommentText','authorId','authorText','commentParams','comments','count','dateReadLast','forbidAdd','forbidText','isSubscribed','lastCommentId','maxLevel','pagination','targetId','targetType','title','titleNoComments','useSubscribe','mods','classes','attributes')),$_smarty_tpl);?>



    
    <?php $_smarty_tpl->tpl_vars['maxLevel'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['maxLevel']->value)===null||$tmp==='' ? Config::Get('module.comment.max_tree') : $tmp), null, 0);?>
    <?php $_smarty_tpl->tpl_vars['hookPrefix'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['hookPrefix']->value)===null||$tmp==='' ? 'comments' : $tmp), null, 0);?>

    <?php if ($_smarty_tpl->tpl_vars['forbidAdd']->value){?>
        <?php $_smarty_tpl->tpl_vars['mods'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['mods']->value)." forbid", null, 0);?>
    <?php }?>


<?php if ($_smarty_tpl->tpl_vars['oUserCurrent']->value&&!$_smarty_tpl->tpl_vars['pagination']->value['total']){?>
    <?php echo smarty_function_add_block(array('group'=>'toolbar','name'=>'component@comment.toolbar'),$_smarty_tpl);?>

<?php }?>

<div class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
 js-comments <?php echo smarty_function_cmods(array('name'=>$_smarty_tpl->tpl_vars['component']->value,'mods'=>$_smarty_tpl->tpl_vars['mods']->value),$_smarty_tpl);?>
 <?php echo $_smarty_tpl->tpl_vars['classes']->value;?>
"
    data-target-type="<?php echo $_smarty_tpl->tpl_vars['targetType']->value;?>
"
    data-target-id="<?php echo $_smarty_tpl->tpl_vars['targetId']->value;?>
"
    data-comment-last-id="<?php echo $_smarty_tpl->tpl_vars['lastCommentId']->value;?>
"
    <?php echo smarty_function_cattr(array('list'=>$_smarty_tpl->tpl_vars['attributes']->value),$_smarty_tpl);?>
>

    
    <?php echo smarty_function_hook(array('run'=>((string)$_smarty_tpl->tpl_vars['hookPrefix']->value)."_begin",'params'=>$_smarty_tpl->tpl_vars['params']->value),$_smarty_tpl);?>


    
    <header class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-header">
        <h3 class="comments-title js-comments-title">
            <?php if ($_smarty_tpl->tpl_vars['count']->value){?>
                <?php ob_start();?><?php echo (($tmp = @$_smarty_tpl->tpl_vars['title']->value)===null||$tmp==='' ? 'comments.comments_declension' : $tmp);?>
<?php $_tmp1=ob_get_clean();?><?php echo smarty_function_lang(array('_default_short'=>$_tmp1,'count'=>$_smarty_tpl->tpl_vars['count']->value,'plural'=>true),$_smarty_tpl);?>

            <?php }else{ ?>
                <?php ob_start();?><?php echo (($tmp = @$_smarty_tpl->tpl_vars['titleNoComments']->value)===null||$tmp==='' ? 'comments.no_comments' : $tmp);?>
<?php $_tmp2=ob_get_clean();?><?php echo smarty_function_lang(array('_default_short'=>$_tmp2),$_smarty_tpl);?>

            <?php }?>
        </h3>

        
        <?php echo smarty_function_hook(array('run'=>((string)$_smarty_tpl->tpl_vars['hookPrefix']->value)."_header_end",'params'=>$_smarty_tpl->tpl_vars['params']->value),$_smarty_tpl);?>

    </header>


    

    <?php $_smarty_tpl->tpl_vars['items'] = new Smarty_variable(array(), null, 0);?>

    
    
    <?php if ($_smarty_tpl->tpl_vars['maxLevel']->value>0){?>
        <?php $_smarty_tpl->createLocalArrayVariable('items', null, 0);
$_smarty_tpl->tpl_vars['items']->value[] = array('buttons'=>array(array('classes'=>'js-comments-fold-all-toggle','text'=>$_smarty_tpl->tpl_vars['aLang']->value['comments']['folding']['fold_all'])));?>
    <?php }?>

    
    <?php if ($_smarty_tpl->tpl_vars['useSubscribe']->value&&$_smarty_tpl->tpl_vars['oUserCurrent']->value){?>
        <?php ob_start();?><?php if ($_smarty_tpl->tpl_vars['isSubscribed']->value){?><?php echo "active";?><?php }?><?php $_tmp3=ob_get_clean();?><?php $_smarty_tpl->createLocalArrayVariable('items', null, 0);
$_smarty_tpl->tpl_vars['items']->value[] = array('buttons'=>array(array('classes'=>((string)$_smarty_tpl->tpl_vars['component']->value)."-subscribe js-comments-subscribe ".$_tmp3,'text'=>$_smarty_tpl->tpl_vars['isSubscribed']->value ? $_smarty_tpl->tpl_vars['aLang']->value['comments']['unsubscribe'] : $_smarty_tpl->tpl_vars['aLang']->value['comments']['subscribe'])));?>
    <?php }?>

    <?php if ($_smarty_tpl->tpl_vars['items']->value){?>
        <?php echo smarty_function_component(array('_default_short'=>'actionbar','name'=>'comments_actionbar','items'=>$_smarty_tpl->tpl_vars['items']->value,'classes'=>((string)$_smarty_tpl->tpl_vars['component']->value)."-actions"),$_smarty_tpl);?>

    <?php }?>

    
    <?php echo smarty_function_hook(array('run'=>((string)$_smarty_tpl->tpl_vars['hookPrefix']->value)."_list_before",'params'=>$_smarty_tpl->tpl_vars['params']->value),$_smarty_tpl);?>


    
    <div class="ls-comment-list js-comment-list" <?php if (!$_smarty_tpl->tpl_vars['comments']->value){?>style="display: none"<?php }?>>
        <?php echo smarty_function_component(array('_default_short'=>'comment','template'=>'tree','comments'=>$_smarty_tpl->tpl_vars['comments']->value,'forbidAdd'=>$_smarty_tpl->tpl_vars['forbidAdd']->value,'maxLevel'=>$_smarty_tpl->tpl_vars['maxLevel']->value,'authorId'=>$_smarty_tpl->tpl_vars['authorId']->value,'authorText'=>$_smarty_tpl->tpl_vars['authorText']->value,'dateReadLast'=>$_smarty_tpl->tpl_vars['dateReadLast']->value,'commentParams'=>$_smarty_tpl->tpl_vars['commentParams']->value,'hookPrefixComment'=>$_smarty_tpl->tpl_vars['hookPrefixComment']->value),$_smarty_tpl);?>

    </div>

    
    <?php echo smarty_function_hook(array('run'=>((string)$_smarty_tpl->tpl_vars['hookPrefix']->value)."_list_after",'params'=>$_smarty_tpl->tpl_vars['params']->value),$_smarty_tpl);?>



    
    <?php echo smarty_function_component(array('_default_short'=>'pagination','classes'=>((string)$_smarty_tpl->tpl_vars['component']->value)."-pagination",'params'=>$_smarty_tpl->tpl_vars['pagination']->value),$_smarty_tpl);?>



    

    
    <?php if ($_smarty_tpl->tpl_vars['forbidAdd']->value){?>
        <?php echo smarty_function_component(array('_default_short'=>'alert','mods'=>'info','text'=>$_smarty_tpl->tpl_vars['forbidText']->value),$_smarty_tpl);?>


    
    <?php }else{ ?>
        <?php if ($_smarty_tpl->tpl_vars['oUserCurrent']->value){?>
            
            <h4 class="ls-comment-reply-root js-comment-reply js-comment-reply-root" data-id="0">
                <a href="#" class="ls-link-dotted"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['addCommentText']->value)===null||$tmp==='' ? $_smarty_tpl->tpl_vars['aLang']->value['comments']['form']['title'] : $tmp);?>
</a>
            </h4>
        <?php }else{ ?>
            <?php echo smarty_function_component(array('_default_short'=>'alert','mods'=>'info','text'=>$_smarty_tpl->tpl_vars['aLang']->value['comments']['alerts']['unregistered']),$_smarty_tpl);?>

        <?php }?>
    <?php }?>

    
    <?php if ($_smarty_tpl->tpl_vars['oUserCurrent']->value&&(!$_smarty_tpl->tpl_vars['forbidAdd']->value||($_smarty_tpl->tpl_vars['forbidAdd']->value&&$_smarty_tpl->tpl_vars['count']->value))){?>
        <?php echo smarty_function_component(array('_default_short'=>'comment','template'=>'form','classes'=>'js-comment-form','targetType'=>$_smarty_tpl->tpl_vars['targetType']->value,'targetId'=>$_smarty_tpl->tpl_vars['targetId']->value),$_smarty_tpl);?>

    <?php }?>

    
    <?php echo smarty_function_hook(array('run'=>((string)$_smarty_tpl->tpl_vars['hookPrefix']->value)."_end",'params'=>$_smarty_tpl->tpl_vars['params']->value),$_smarty_tpl);?>

</div>
<?php }} ?>