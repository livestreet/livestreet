<?php /* Smarty version Smarty-3.1.13, created on 2018-11-24 09:24:06
         compiled from "/var/www/ls.new/application/frontend/skin/synio/components/search/hideable/search-hideable.tpl" */ ?>
<?php /*%%SmartyHeaderCode:15289390165bf8ee8661c331-97105924%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4c0faa0ddcc014d15837af21d2b4936c9ae254af' => 
    array (
      0 => '/var/www/ls.new/application/frontend/skin/synio/components/search/hideable/search-hideable.tpl',
      1 => 1543035802,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '15289390165bf8ee8661c331-97105924',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5bf8ee86625a54_30241163',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5bf8ee86625a54_30241163')) {function content_5bf8ee86625a54_30241163($_smarty_tpl) {?><?php if (!is_callable('smarty_function_lang')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.lang.php';
if (!is_callable('smarty_function_component')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component.php';
?><div class="ls-search-hideable js-search-hideable">
    <div class="ls-search-hideable-toggle js-search-hideable-toggle">
        <i class="ls-search-hideable-toggle-icon"></i>
        <a href="#" class="ls-search-hideable-toggle-text"><?php echo smarty_function_lang(array('_default_short'=>'search.find'),$_smarty_tpl);?>
</a>
    </div>

    <?php echo smarty_function_component(array('_default_short'=>'search.main','classes'=>'ls-search-hideable-search js-search-hideable-search','mods'=>'light'),$_smarty_tpl);?>

</div><?php }} ?>