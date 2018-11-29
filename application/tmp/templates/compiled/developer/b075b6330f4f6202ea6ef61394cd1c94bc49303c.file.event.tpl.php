<?php /* Smarty version Smarty-3.1.13, created on 2018-11-29 11:18:08
         compiled from "/var/www/ls.new/application/frontend/components/activity/event.tpl" */ ?>
<?php /*%%SmartyHeaderCode:15831211865bffa0c0eef491-25801080%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b075b6330f4f6202ea6ef61394cd1c94bc49303c' => 
    array (
      0 => '/var/www/ls.new/application/frontend/components/activity/event.tpl',
      1 => 1543479353,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '15831211865bffa0c0eef491-25801080',
  'function' => 
  array (
    'activity_event_text' => 
    array (
      'parameter' => 
      array (
        'text' => '',
      ),
      'compiled' => '',
    ),
  ),
  'variables' => 
  array (
    'event' => 0,
    'user' => 0,
    'text' => 0,
    'component' => 0,
    'type' => 0,
    'gender' => 0,
    'target' => 0,
  ),
  'has_nocache_code' => 0,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5bffa0c115dda6_59377342',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5bffa0c115dda6_59377342')) {function content_5bffa0c115dda6_59377342($_smarty_tpl) {?><?php if (!is_callable('smarty_function_component_define_params')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component_define_params.php';
if (!is_callable('smarty_function_date_format')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.date_format.php';
if (!is_callable('smarty_function_lang')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.lang.php';
if (!is_callable('smarty_function_hook')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.hook.php';
if (!is_callable('smarty_function_cmods')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.cmods.php';
if (!is_callable('smarty_function_component')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component.php';
?>

<?php $_smarty_tpl->tpl_vars['component'] = new Smarty_variable('activity-event', null, 0);?>
<?php echo smarty_function_component_define_params(array('params'=>array('event')),$_smarty_tpl);?>


<?php $_smarty_tpl->tpl_vars['type'] = new Smarty_variable($_smarty_tpl->tpl_vars['event']->value->getEventType(), null, 0);?>
<?php $_smarty_tpl->tpl_vars['target'] = new Smarty_variable($_smarty_tpl->tpl_vars['event']->value->getTarget(), null, 0);?>
<?php $_smarty_tpl->tpl_vars['user'] = new Smarty_variable($_smarty_tpl->tpl_vars['event']->value->getUser(), null, 0);?>
<?php $_smarty_tpl->tpl_vars['gender'] = new Smarty_variable($_smarty_tpl->tpl_vars['user']->value->getProfileSex()=='woman' ? 'female' : 'male', null, 0);?>


<?php if (!function_exists('smarty_template_function_activity_event_text')) {
    function smarty_template_function_activity_event_text($_smarty_tpl,$params) {
    $saved_tpl_vars = $_smarty_tpl->tpl_vars;
    foreach ($_smarty_tpl->smarty->template_functions['activity_event_text']['parameter'] as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);};
    foreach ($params as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);}?>
    <?php if (trim($_smarty_tpl->tpl_vars['text']->value)){?>
        <div class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-text ls-text"><?php echo $_smarty_tpl->tpl_vars['text']->value;?>
</div>
    <?php }?>
<?php $_smarty_tpl->tpl_vars = $saved_tpl_vars;
foreach (Smarty::$global_tpl_vars as $key => $value) if(!isset($_smarty_tpl->tpl_vars[$key])) $_smarty_tpl->tpl_vars[$key] = $value;}}?>




<?php $_smarty_tpl->_capture_stack[0][] = array('event_content', null, null); ob_start(); ?>
    
    <time datetime="<?php echo smarty_function_date_format(array('date'=>$_smarty_tpl->tpl_vars['event']->value->getDateAdded(),'format'=>'c','notz'=>1),$_smarty_tpl);?>
"
          data-date="<?php echo smarty_function_date_format(array('date'=>$_smarty_tpl->tpl_vars['event']->value->getDateAdded(),'format'=>'Y-m-d','notz'=>1),$_smarty_tpl);?>
"
          class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-date"
          title="<?php echo smarty_function_date_format(array('date'=>$_smarty_tpl->tpl_vars['event']->value->getDateAdded()),$_smarty_tpl);?>
">
        <?php echo smarty_function_date_format(array('date'=>$_smarty_tpl->tpl_vars['event']->value->getDateAdded(),'hours_back'=>"12",'minutes_back'=>"60",'now'=>"60",'day'=>"day H:i",'format'=>"j F Y, H:i"),$_smarty_tpl);?>

    </time>

    
    <a href="<?php echo $_smarty_tpl->tpl_vars['user']->value->getUserWebPath();?>
" class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-username"><?php echo $_smarty_tpl->tpl_vars['user']->value->getDisplayName();?>
</a>

    
    <?php if ($_smarty_tpl->tpl_vars['type']->value=='add_topic'){?>
        
        <?php ob_start();?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['target']->value->getTitle(), ENT_QUOTES, 'UTF-8', true);?>
<?php $_tmp1=ob_get_clean();?><?php echo smarty_function_lang(array('_default_short'=>"activity.events.".((string)$_smarty_tpl->tpl_vars['type']->value)."_".((string)$_smarty_tpl->tpl_vars['gender']->value),'topic'=>"<a href=\"".((string)$_smarty_tpl->tpl_vars['target']->value->getUrl())."\">".$_tmp1."</a>"),$_smarty_tpl);?>

    <?php }elseif($_smarty_tpl->tpl_vars['type']->value=='add_comment'){?>
        
        <?php ob_start();?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['target']->value->getTarget()->getTitle(), ENT_QUOTES, 'UTF-8', true);?>
<?php $_tmp2=ob_get_clean();?><?php echo smarty_function_lang(array('_default_short'=>"activity.events.".((string)$_smarty_tpl->tpl_vars['type']->value)."_".((string)$_smarty_tpl->tpl_vars['gender']->value),'topic'=>"<a href=\"".((string)$_smarty_tpl->tpl_vars['target']->value->getTarget()->getUrl())."#comment".((string)$_smarty_tpl->tpl_vars['target']->value->getId())."\">".$_tmp2."</a>"),$_smarty_tpl);?>


        <?php smarty_template_function_activity_event_text($_smarty_tpl,array('text'=>$_smarty_tpl->tpl_vars['target']->value->getText()));?>

    <?php }elseif($_smarty_tpl->tpl_vars['type']->value=='add_blog'){?>
        
        <?php ob_start();?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['target']->value->getTitle(), ENT_QUOTES, 'UTF-8', true);?>
<?php $_tmp3=ob_get_clean();?><?php echo smarty_function_lang(array('_default_short'=>"activity.events.".((string)$_smarty_tpl->tpl_vars['type']->value)."_".((string)$_smarty_tpl->tpl_vars['gender']->value),'blog'=>"<a href=\"".((string)$_smarty_tpl->tpl_vars['target']->value->getUrlFull())."\">".$_tmp3."</a>"),$_smarty_tpl);?>

    <?php }elseif($_smarty_tpl->tpl_vars['type']->value=='vote_blog'){?>
        
        <?php ob_start();?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['target']->value->getTitle(), ENT_QUOTES, 'UTF-8', true);?>
<?php $_tmp4=ob_get_clean();?><?php echo smarty_function_lang(array('_default_short'=>"activity.events.".((string)$_smarty_tpl->tpl_vars['type']->value)."_".((string)$_smarty_tpl->tpl_vars['gender']->value),'blog'=>"<a href=\"".((string)$_smarty_tpl->tpl_vars['target']->value->getUrlFull())."\">".$_tmp4."</a>"),$_smarty_tpl);?>

    <?php }elseif($_smarty_tpl->tpl_vars['type']->value=='vote_topic'){?>
        
        <?php ob_start();?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['target']->value->getTitle(), ENT_QUOTES, 'UTF-8', true);?>
<?php $_tmp5=ob_get_clean();?><?php echo smarty_function_lang(array('_default_short'=>"activity.events.".((string)$_smarty_tpl->tpl_vars['type']->value)."_".((string)$_smarty_tpl->tpl_vars['gender']->value),'topic'=>"<a href=\"".((string)$_smarty_tpl->tpl_vars['target']->value->getUrl())."\">".$_tmp5."</a>"),$_smarty_tpl);?>

    <?php }elseif($_smarty_tpl->tpl_vars['type']->value=='vote_comment_topic'){?>
        
        <?php ob_start();?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['target']->value->getTarget()->getTitle(), ENT_QUOTES, 'UTF-8', true);?>
<?php $_tmp6=ob_get_clean();?><?php echo smarty_function_lang(array('_default_short'=>"activity.events.".((string)$_smarty_tpl->tpl_vars['type']->value)."_".((string)$_smarty_tpl->tpl_vars['gender']->value),'topic'=>"<a href=\"".((string)$_smarty_tpl->tpl_vars['target']->value->getTarget()->getUrl())."#comment".((string)$_smarty_tpl->tpl_vars['target']->value->getId())."\">".$_tmp6."</a>"),$_smarty_tpl);?>

    <?php }elseif($_smarty_tpl->tpl_vars['type']->value=='vote_user'){?>
        
        <?php echo smarty_function_lang(array('_default_short'=>"activity.events.".((string)$_smarty_tpl->tpl_vars['type']->value)."_".((string)$_smarty_tpl->tpl_vars['gender']->value),'user'=>"<a href=\"".((string)$_smarty_tpl->tpl_vars['target']->value->getUserWebPath())."\">".((string)$_smarty_tpl->tpl_vars['target']->value->getDisplayName())."</a>"),$_smarty_tpl);?>

    <?php }elseif($_smarty_tpl->tpl_vars['type']->value=='join_blog'){?>
        
        <?php ob_start();?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['target']->value->getTitle(), ENT_QUOTES, 'UTF-8', true);?>
<?php $_tmp7=ob_get_clean();?><?php echo smarty_function_lang(array('_default_short'=>"activity.events.".((string)$_smarty_tpl->tpl_vars['type']->value)."_".((string)$_smarty_tpl->tpl_vars['gender']->value),'blog'=>"<a href=\"".((string)$_smarty_tpl->tpl_vars['target']->value->getUrlFull())."\">".$_tmp7."</a>"),$_smarty_tpl);?>

    <?php }elseif($_smarty_tpl->tpl_vars['type']->value=='add_friend'){?>
        
        <?php echo smarty_function_lang(array('_default_short'=>"activity.events.".((string)$_smarty_tpl->tpl_vars['type']->value)."_".((string)$_smarty_tpl->tpl_vars['gender']->value),'user'=>"<a href=\"".((string)$_smarty_tpl->tpl_vars['target']->value->getUserWebPath())."\">".((string)$_smarty_tpl->tpl_vars['target']->value->getDisplayName())."</a>"),$_smarty_tpl);?>

    <?php }else{ ?>
        <?php echo smarty_function_hook(array('run'=>"activity_event_".((string)$_smarty_tpl->tpl_vars['type']->value),'event'=>$_smarty_tpl->tpl_vars['event']->value),$_smarty_tpl);?>

    <?php }?>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>

<?php ob_start();?><?php echo smarty_function_cmods(array('name'=>$_smarty_tpl->tpl_vars['component']->value,'mods'=>$_smarty_tpl->tpl_vars['type']->value),$_smarty_tpl);?>
<?php $_tmp8=ob_get_clean();?><?php echo smarty_function_component(array('_default_short'=>'item','element'=>'li','classes'=>((string)$_smarty_tpl->tpl_vars['component']->value)." ".$_tmp8." js-activity-event",'mods'=>'image-rounded','desc'=>Smarty::$_smarty_vars['capture']['event_content'],'image'=>array('url'=>$_smarty_tpl->tpl_vars['user']->value->getUserWebPath(),'path'=>$_smarty_tpl->tpl_vars['user']->value->getProfileAvatarPath(48),'alt'=>$_smarty_tpl->tpl_vars['user']->value->getDisplayName())),$_smarty_tpl);?>
<?php }} ?>