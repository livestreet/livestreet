<?php /* Smarty version Smarty-3.1.13, created on 2018-11-24 09:24:06
         compiled from "/var/www/ls.new/application/frontend/skin/synio/components/syn-create/create.tpl" */ ?>
<?php /*%%SmartyHeaderCode:18541387875bf8ee8685cd85-70913092%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '71cd4720f749d20826bf15a79b5d10c41baea611' => 
    array (
      0 => '/var/www/ls.new/application/frontend/skin/synio/components/syn-create/create.tpl',
      1 => 1543035802,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '18541387875bf8ee8685cd85-70913092',
  'function' => 
  array (
    'syn_create' => 
    array (
      'parameter' => 
      array (
        'item' => NULL,
      ),
      'compiled' => '',
    ),
  ),
  'variables' => 
  array (
    'item' => 0,
    'iUserCurrentCountTopicDraft' => 0,
    'LS' => 0,
    'type' => 0,
    '_menu' => 0,
  ),
  'has_nocache_code' => 0,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5bf8ee868d28f6_61834968',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5bf8ee868d28f6_61834968')) {function content_5bf8ee868d28f6_61834968($_smarty_tpl) {?><?php if (!is_callable('smarty_function_lang')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.lang.php';
if (!is_callable('smarty_function_router')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.router.php';
if (!is_callable('smarty_function_component')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component.php';
?><?php if (!function_exists('smarty_template_function_syn_create')) {
    function smarty_template_function_syn_create($_smarty_tpl,$params) {
    $saved_tpl_vars = $_smarty_tpl->tpl_vars;
    foreach ($_smarty_tpl->smarty->template_functions['syn_create']['parameter'] as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);};
    foreach ($params as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);}?>
    <a href="<?php echo $_smarty_tpl->tpl_vars['item']->value['url'];?>
" class="syn-create-item syn-create-item--<?php echo $_smarty_tpl->tpl_vars['item']->value['name'];?>
">
        <div class="syn-create-item-image <?php echo $_smarty_tpl->tpl_vars['item']->value['css_icon'];?>
"></div>
        <div class="syn-create-item-text"><?php echo $_smarty_tpl->tpl_vars['item']->value['text'];?>
</div>
    </a>
<?php $_smarty_tpl->tpl_vars = $saved_tpl_vars;
foreach (Smarty::$global_tpl_vars as $key => $value) if(!isset($_smarty_tpl->tpl_vars[$key])) $_smarty_tpl->tpl_vars[$key] = $value;}}?>


<?php $_smarty_tpl->_capture_stack[0][] = array('syn_create', null, null); ob_start(); ?>
    <?php ob_start();?><?php echo smarty_function_lang(array('_default_short'=>'modal_create.items.blog'),$_smarty_tpl);?>
<?php $_tmp1=ob_get_clean();?><?php ob_start();?><?php echo smarty_function_router(array('page'=>'blog/add'),$_smarty_tpl);?>
<?php $_tmp2=ob_get_clean();?><?php ob_start();?><?php echo smarty_function_lang(array('_default_short'=>'modal_create.items.talk'),$_smarty_tpl);?>
<?php $_tmp3=ob_get_clean();?><?php ob_start();?><?php echo smarty_function_router(array('page'=>'talk/add'),$_smarty_tpl);?>
<?php $_tmp4=ob_get_clean();?><?php $_smarty_tpl->tpl_vars['_menu'] = new Smarty_variable(array(array('name'=>'blog','text'=>$_tmp1,'url'=>$_tmp2,'css_icon'=>'fa fa-folder-o'),array('name'=>'message','text'=>$_tmp3,'url'=>$_tmp4,'css_icon'=>'fa fa-envelope-o')), null, 0);?>

    <div class="syn-create-items ls-clearfix">
        <?php if ($_smarty_tpl->tpl_vars['iUserCurrentCountTopicDraft']->value){?>
            <?php ob_start();?><?php echo smarty_function_lang(array('_default_short'=>'synio.drafts','count'=>$_smarty_tpl->tpl_vars['iUserCurrentCountTopicDraft']->value,'plural'=>true),$_smarty_tpl);?>
<?php $_tmp5=ob_get_clean();?><?php ob_start();?><?php echo smarty_function_router(array('page'=>'content'),$_smarty_tpl);?>
<?php $_tmp6=ob_get_clean();?><?php smarty_template_function_syn_create($_smarty_tpl,array('item'=>array('name'=>'draft','text'=>$_tmp5,'url'=>$_tmp6."drafts/",'css_icon'=>'fa fa-file-o')));?>

        <?php }?>

        <?php  $_smarty_tpl->tpl_vars['type'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['type']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['LS']->value->Topic_GetTopicTypes(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['type']->key => $_smarty_tpl->tpl_vars['type']->value){
$_smarty_tpl->tpl_vars['type']->_loop = true;
?>
            <?php smarty_template_function_syn_create($_smarty_tpl,array('item'=>array('name'=>$_smarty_tpl->tpl_vars['type']->value->getCode(),'css_icon'=>$_smarty_tpl->tpl_vars['type']->value->getParam('css_icon','fa fa-file-text-o'),'text'=>$_smarty_tpl->tpl_vars['type']->value->getName(),'url'=>$_smarty_tpl->tpl_vars['type']->value->getUrlForAdd())));?>

        <?php } ?>

        <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['_menu']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
$_smarty_tpl->tpl_vars['item']->_loop = true;
?>
            <?php smarty_template_function_syn_create($_smarty_tpl,array('item'=>$_smarty_tpl->tpl_vars['item']->value));?>

        <?php } ?>
    </div>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>

<?php echo smarty_function_component(array('_default_short'=>'modal','id'=>'syn-create-modal','showFooter'=>false,'classes'=>'syn-create-modal js-modal-default','content'=>Smarty::$_smarty_vars['capture']['syn_create']),$_smarty_tpl);?>
<?php }} ?>