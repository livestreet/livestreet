<?php /* Smarty version Smarty-3.1.13, created on 2018-11-25 11:43:06
         compiled from "/var/www/ls.new/framework/frontend/components/editor/editor.tpl" */ ?>
<?php /*%%SmartyHeaderCode:15184232375bfa609ae43669-32553453%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e4987ed83da558a30a7997d2477e8b00224eb831' => 
    array (
      0 => '/var/www/ls.new/framework/frontend/components/editor/editor.tpl',
      1 => 1543037260,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '15184232375bfa609ae43669-32553453',
  'function' => 
  array (
    'editor_textarea' => 
    array (
      'parameter' => 
      array (
      ),
      'compiled' => '',
    ),
  ),
  'variables' => 
  array (
    'type' => 0,
    'set' => 0,
    'id' => 0,
    'component' => 0,
    '_uid' => 0,
    'inputAttributes' => 0,
    '_mediaUid' => 0,
    'rows' => 0,
    'params' => 0,
    'help' => 0,
    'mediaTargetType' => 0,
    'mediaTargetId' => 0,
  ),
  'has_nocache_code' => 0,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5bfa609b738fd4_61315299',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5bfa609b738fd4_61315299')) {function content_5bfa609b738fd4_61315299($_smarty_tpl) {?><?php if (!is_callable('smarty_function_component_define_params')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component_define_params.php';
if (!is_callable('smarty_block_hookb')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/block.hookb.php';
if (!is_callable('smarty_function_asset')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.asset.php';
if (!is_callable('smarty_function_lang_load')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.lang_load.php';
if (!is_callable('smarty_function_component')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component.php';
?>


<?php $_smarty_tpl->tpl_vars['component'] = new Smarty_variable('editor', null, 0);?>
<?php echo smarty_function_component_define_params(array('params'=>array('form','placeholder','isDisabled','entity','entityScenario','entityField','escape','data','label','name','rules','value','id','inputClasses','inputAttributes','inputData','mods','classes','attributes','note','rows','type','set','help','mediaTargetType','mediaTargetId')),$_smarty_tpl);?>



<?php $_smarty_tpl->tpl_vars['type'] = new Smarty_variable(($_smarty_tpl->tpl_vars['type']->value ? $_smarty_tpl->tpl_vars['type']->value : Config::Get('view.wysiwyg') ? 'visual' : 'markup'), null, 0);?>
<?php $_smarty_tpl->tpl_vars['set'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['set']->value)===null||$tmp==='' ? 'default' : $tmp), null, 0);?>


<?php $_smarty_tpl->tpl_vars['_uid'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['id']->value)===null||$tmp==='' ? (($_smarty_tpl->tpl_vars['component']->value).(mt_rand())) : $tmp), null, 0);?>


<?php $_smarty_tpl->tpl_vars['_mediaUid'] = new Smarty_variable("media".((string)$_smarty_tpl->tpl_vars['_uid']->value), null, 0);?>



<?php if (!is_callable('smarty_function_component')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component.php';
?><?php if (!function_exists('smarty_template_function_editor_textarea')) {
    function smarty_template_function_editor_textarea($_smarty_tpl,$params) {
    $saved_tpl_vars = $_smarty_tpl->tpl_vars;
    foreach ($_smarty_tpl->smarty->template_functions['editor_textarea']['parameter'] as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);};
    foreach ($params as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);}?>
    <?php echo smarty_function_component(array('_default_short'=>'field.textarea','id'=>$_smarty_tpl->tpl_vars['_uid']->value,'inputAttributes'=>array_merge((($tmp = @$_smarty_tpl->tpl_vars['inputAttributes']->value)===null||$tmp==='' ? array() : $tmp),array('data-editor-type'=>$_smarty_tpl->tpl_vars['type']->value,'data-editor-set'=>$_smarty_tpl->tpl_vars['set']->value,'data-editor-media'=>$_smarty_tpl->tpl_vars['_mediaUid']->value)),'rows'=>(($tmp = @$_smarty_tpl->tpl_vars['rows']->value)===null||$tmp==='' ? 10 : $tmp),'params'=>$_smarty_tpl->tpl_vars['params']->value),$_smarty_tpl);?>

<?php $_smarty_tpl->tpl_vars = $saved_tpl_vars;
foreach (Smarty::$global_tpl_vars as $key => $value) if(!isset($_smarty_tpl->tpl_vars[$key])) $_smarty_tpl->tpl_vars[$key] = $value;}}?>



<?php if ($_smarty_tpl->tpl_vars['type']->value=='visual'){?>
    <?php $_smarty_tpl->smarty->_tag_stack[] = array('hookb', array('run'=>'editor_visual','targetId'=>$_smarty_tpl->tpl_vars['_uid']->value)); $_block_repeat=true; echo smarty_block_hookb(array('run'=>'editor_visual','targetId'=>$_smarty_tpl->tpl_vars['_uid']->value), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>

        <?php echo smarty_function_asset(array('type'=>'js','defer'=>true,'file'=>"Component@editor.vendor/tinymce/js/tinymce/tinymce.min"),$_smarty_tpl);?>

        <?php echo smarty_function_asset(array('type'=>'js','defer'=>true,'file'=>"Component@editor.vendor/tinymce/js/tinymce/jquery.tinymce.min"),$_smarty_tpl);?>

        <?php echo smarty_function_asset(array('type'=>'js','defer'=>true,'file'=>"Component@editor.visual"),$_smarty_tpl);?>


        <?php smarty_template_function_editor_textarea($_smarty_tpl,array());?>

    <?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_hookb(array('run'=>'editor_visual','targetId'=>$_smarty_tpl->tpl_vars['_uid']->value), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>



<?php }else{ ?>
    <?php $_smarty_tpl->smarty->_tag_stack[] = array('hookb', array('run'=>'editor_markup','targetId'=>$_smarty_tpl->tpl_vars['_uid']->value)); $_block_repeat=true; echo smarty_block_hookb(array('run'=>'editor_markup','targetId'=>$_smarty_tpl->tpl_vars['_uid']->value), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>

        <?php echo smarty_function_lang_load(array('prepare'=>true,'name'=>"editor.markup.toolbar.b, editor.markup.toolbar.i, editor.markup.toolbar.u, editor.markup.toolbar.s, editor.markup.toolbar.url, editor.markup.toolbar.url_promt, editor.markup.toolbar.image_promt, editor.markup.toolbar.code, editor.markup.toolbar.video, editor.markup.toolbar.video_promt, editor.markup.toolbar.image, editor.markup.toolbar.cut, editor.markup.toolbar.quote, editor.markup.toolbar.list, editor.markup.toolbar.list_ul, editor.markup.toolbar.list_ol, editor.markup.toolbar.list_li, editor.markup.toolbar.title, editor.markup.toolbar.title_h4, editor.markup.toolbar.title_h5, editor.markup.toolbar.title_h6, editor.markup.toolbar.clear_tags, editor.markup.toolbar.user, editor.markup.toolbar.user_promt"),$_smarty_tpl);?>


        <?php echo smarty_function_asset(array('type'=>'js','defer'=>true,'file'=>"Component@editor.vendor/markitup/jquery.markitup"),$_smarty_tpl);?>

        <?php echo smarty_function_asset(array('type'=>'js','defer'=>true,'file'=>"Component@editor.markup"),$_smarty_tpl);?>


        <?php echo smarty_function_asset(array('type'=>'css','file'=>"Component@editor.vendor/markitup/skins/livestreet/style"),$_smarty_tpl);?>

        <?php echo smarty_function_asset(array('type'=>'css','file'=>"Component@editor.vendor/markitup/sets/livestreet/style"),$_smarty_tpl);?>

        <?php echo smarty_function_asset(array('type'=>'css','file'=>"Component@editor.editor"),$_smarty_tpl);?>


        <?php smarty_template_function_editor_textarea($_smarty_tpl,array());?>


        <?php if ((($tmp = @$_smarty_tpl->tpl_vars['help']->value)===null||$tmp==='' ? true : $tmp)){?>
            <?php echo smarty_function_component(array('_default_short'=>'editor','template'=>'markup-help','targetId'=>$_smarty_tpl->tpl_vars['_uid']->value),$_smarty_tpl);?>

        <?php }?>
    <?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_hookb(array('run'=>'editor_markup','targetId'=>$_smarty_tpl->tpl_vars['_uid']->value), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>

<?php }?>


<?php echo smarty_function_component(array('_default_short'=>'media','sMediaTargetType'=>$_smarty_tpl->tpl_vars['mediaTargetType']->value,'sMediaTargetId'=>$_smarty_tpl->tpl_vars['mediaTargetId']->value,'id'=>$_smarty_tpl->tpl_vars['_mediaUid']->value,'assign'=>'mediaModal'),$_smarty_tpl);?>



<?php $_smarty_tpl->tpl_vars['sLayoutAfter'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['sLayoutAfter']->value)." ".((string)$_smarty_tpl->tpl_vars['mediaModal']->value), null, 2);
$_ptr = $_smarty_tpl->parent; while ($_ptr != null) {$_ptr->tpl_vars['sLayoutAfter'] = clone $_smarty_tpl->tpl_vars['sLayoutAfter']; $_ptr = $_ptr->parent; }?>
<?php }} ?>