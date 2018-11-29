<?php /* Smarty version Smarty-3.1.13, created on 2018-11-29 11:18:23
         compiled from "/var/www/ls.new/application/plugins/admin/frontend/components/p-user/search.tpl" */ ?>
<?php /*%%SmartyHeaderCode:14296419495bffa0cf652590-77164486%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'baa241990cafe5e87acde5398e54db5be9378a18' => 
    array (
      0 => '/var/www/ls.new/application/plugins/admin/frontend/components/p-user/search.tpl',
      1 => 1543039194,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '14296419495bffa0cf652590-77164486',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'action' => 0,
    'queries' => 0,
    'searchValueItems' => 0,
    'searchFieldItems' => 0,
    'searchValue' => 0,
    'aLang' => 0,
    'searchIn' => 0,
    'searchField' => 0,
    'sFullPagePathToEvent' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5bffa0cf6b6f84_09582828',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5bffa0cf6b6f84_09582828')) {function content_5bffa0cf6b6f84_09582828($_smarty_tpl) {?><?php if (!is_callable('smarty_function_component_define_params')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component_define_params.php';
if (!is_callable('smarty_function_json')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.json.php';
if (!is_callable('smarty_function_request_filter')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.request_filter.php';
if (!is_callable('smarty_function_component')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component.php';
?><?php $_smarty_tpl->tpl_vars['component'] = new Smarty_variable('p-user-profile', null, 0);?>
<?php echo smarty_function_component_define_params(array('params'=>array('action','queries')),$_smarty_tpl);?>


<form action="<?php echo $_smarty_tpl->tpl_vars['action']->value;?>
" id="js-admin-users-list-search-form-id">
    
    <?php $_smarty_tpl->tpl_vars['searchValueItems'] = new Smarty_variable(array_values($_smarty_tpl->tpl_vars['queries']->value), null, 0);?>
    <?php $_smarty_tpl->tpl_vars['searchFieldItems'] = new Smarty_variable(array_keys($_smarty_tpl->tpl_vars['queries']->value), null, 0);?>
    <?php $_smarty_tpl->tpl_vars['searchValue'] = new Smarty_variable(array_shift($_smarty_tpl->tpl_vars['searchValueItems']->value), null, 0);?>
    <?php $_smarty_tpl->tpl_vars['searchField'] = new Smarty_variable(array_shift($_smarty_tpl->tpl_vars['searchFieldItems']->value), null, 0);?>

    <script>
        var aAdminUsersSearchRules = <?php echo smarty_function_json(array('var'=>Config::Get('plugin.admin.users.search_allowed_types')),$_smarty_tpl);?>
;
    </script>

    <span id="js-admin-users-list-search-form-q-wrapper">
        <input type="text" class="width-200" value="<?php echo $_smarty_tpl->tpl_vars['searchValue']->value;?>
" placeholder="<?php echo $_smarty_tpl->tpl_vars['aLang']->value['plugin']['admin']['users']['search'];?>
" />
    </span>

    <select class="width-200" id="js-admin-users-list-search-form-field-name">
        <?php  $_smarty_tpl->tpl_vars['searchIn'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['searchIn']->_loop = false;
 $_from = array_keys(Config::Get('plugin.admin.users.search_allowed_types')); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['searchIn']->key => $_smarty_tpl->tpl_vars['searchIn']->value){
$_smarty_tpl->tpl_vars['searchIn']->_loop = true;
?>
            <option value="<?php echo $_smarty_tpl->tpl_vars['searchIn']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['searchIn']->value==$_smarty_tpl->tpl_vars['searchField']->value){?>selected="selected"<?php }?>>
                <?php echo $_smarty_tpl->tpl_vars['aLang']->value['plugin']['admin']['users']['search_allowed_in'][$_smarty_tpl->tpl_vars['searchIn']->value];?>

            </option>
        <?php } ?>
    </select>

    
    <?php if ($_smarty_tpl->tpl_vars['searchField']->value){?>
        <?php ob_start();?><?php echo smarty_function_request_filter(array('name'=>array($_smarty_tpl->tpl_vars['searchField']->value),'value'=>array(null)),$_smarty_tpl);?>
<?php $_tmp1=ob_get_clean();?><?php echo smarty_function_component(array('_default_short'=>'admin:button','url'=>((string)$_smarty_tpl->tpl_vars['sFullPagePathToEvent']->value).$_tmp1,'icon'=>'trash-o','mods'=>'danger'),$_smarty_tpl);?>

    <?php }?>

    <?php echo smarty_function_component(array('_default_short'=>'admin:button','text'=>$_smarty_tpl->tpl_vars['aLang']->value['plugin']['admin']['users']['search'],'mods'=>'primary'),$_smarty_tpl);?>

</form><?php }} ?>