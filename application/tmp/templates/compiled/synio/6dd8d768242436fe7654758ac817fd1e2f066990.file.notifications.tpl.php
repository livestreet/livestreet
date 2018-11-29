<?php /* Smarty version Smarty-3.1.13, created on 2018-11-24 09:24:18
         compiled from "/var/www/ls.new/application/plugins/admin/frontend/components/p-dashboard/notifications.tpl" */ ?>
<?php /*%%SmartyHeaderCode:17786546205bf8ee928b3b36-93773705%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6dd8d768242436fe7654758ac817fd1e2f066990' => 
    array (
      0 => '/var/www/ls.new/application/plugins/admin/frontend/components/p-dashboard/notifications.tpl',
      1 => 1543039194,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '17786546205bf8ee928b3b36-93773705',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'iPluginUpdates' => 0,
    'iUsersComplaintsCountNew' => 0,
    'items' => 0,
    'hookItems' => 0,
    'component' => 0,
    'item' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5bf8ee92970782_66527606',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5bf8ee92970782_66527606')) {function content_5bf8ee92970782_66527606($_smarty_tpl) {?><?php if (!is_callable('smarty_function_router')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.router.php';
if (!is_callable('smarty_function_lang')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.lang.php';
if (!is_callable('smarty_function_hook')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.hook.php';
?>

<?php $_smarty_tpl->tpl_vars['component'] = new Smarty_variable('p-notifications', null, 0);?>

<?php ob_start();?><?php echo smarty_function_router(array('page'=>'admin/plugins/list'),$_smarty_tpl);?>
<?php $_tmp1=ob_get_clean();?><?php ob_start();?><?php echo smarty_function_lang(array('_default_short'=>'plugin.admin.index.updates.plugins.title'),$_smarty_tpl);?>
<?php $_tmp2=ob_get_clean();?><?php ob_start();?><?php echo smarty_function_lang(array('_default_short'=>'plugin.admin.index.updates.plugins.there_are_n_updates','count'=>$_smarty_tpl->tpl_vars['iPluginUpdates']->value,'plural'=>true),$_smarty_tpl);?>
<?php $_tmp3=ob_get_clean();?><?php ob_start();?><?php echo smarty_function_lang(array('_default_short'=>'plugin.admin.index.updates.plugins.no_updates'),$_smarty_tpl);?>
<?php $_tmp4=ob_get_clean();?><?php ob_start();?><?php echo smarty_function_router(array('page'=>'admin/users/complaints'),$_smarty_tpl);?>
<?php $_tmp5=ob_get_clean();?><?php ob_start();?><?php echo smarty_function_lang(array('_default_short'=>'plugin.admin.index.updates.complaints.title'),$_smarty_tpl);?>
<?php $_tmp6=ob_get_clean();?><?php ob_start();?><?php echo smarty_function_lang(array('_default_short'=>'plugin.admin.index.updates.complaints.there_are_n_complaints','count'=>$_smarty_tpl->tpl_vars['iUsersComplaintsCountNew']->value,'plural'=>true),$_smarty_tpl);?>
<?php $_tmp7=ob_get_clean();?><?php ob_start();?><?php echo smarty_function_lang(array('_default_short'=>'plugin.admin.index.updates.complaints.no_complaints'),$_smarty_tpl);?>
<?php $_tmp8=ob_get_clean();?><?php $_smarty_tpl->tpl_vars['items'] = new Smarty_variable(array(array('name'=>'plugins','icon'=>'plug','url'=>$_tmp1,'title'=>$_tmp2,'text'=>$_tmp3,'text_no'=>$_tmp4,'count'=>$_smarty_tpl->tpl_vars['iPluginUpdates']->value),array('name'=>'reports','icon'=>'flag','url'=>$_tmp5,'title'=>$_tmp6,'text'=>$_tmp7,'text_no'=>$_tmp8,'count'=>$_smarty_tpl->tpl_vars['iUsersComplaintsCountNew']->value)), null, 0);?>

<?php echo smarty_function_hook(array('run'=>"dashboard_notifications_items",'assign'=>'hookItems','items'=>$_smarty_tpl->tpl_vars['items']->value,'array'=>true),$_smarty_tpl);?>

<?php $_smarty_tpl->tpl_vars['items'] = new Smarty_variable($_smarty_tpl->tpl_vars['hookItems']->value ? $_smarty_tpl->tpl_vars['hookItems']->value : $_smarty_tpl->tpl_vars['items']->value, null, 0);?>

<div class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
">
    <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['items']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
$_smarty_tpl->tpl_vars['item']->_loop = true;
?>
        <div class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-item <?php if ($_smarty_tpl->tpl_vars['item']->value['count']){?>active<?php }?>">
            <div class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-item-image">
                <a href="<?php echo $_smarty_tpl->tpl_vars['item']->value['url'];?>
" class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-item-image-icon fa fa-<?php echo $_smarty_tpl->tpl_vars['item']->value['icon'];?>
"></a>

                <?php if ($_smarty_tpl->tpl_vars['item']->value['count']){?>
                    <div class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-item-count">
                        <?php echo $_smarty_tpl->tpl_vars['item']->value['count']<1000 ? $_smarty_tpl->tpl_vars['item']->value['count'] : '999+';?>

                    </div>
                <?php }?>
            </div>

            <div class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-item-body">
                <h2 class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-item-title">
                    <a href="<?php echo $_smarty_tpl->tpl_vars['item']->value['url'];?>
"><?php echo $_smarty_tpl->tpl_vars['item']->value['title'];?>
</a>
                </h2>
                <div class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-item-text"><?php echo $_smarty_tpl->tpl_vars['item']->value['count'] ? $_smarty_tpl->tpl_vars['item']->value['text'] : $_smarty_tpl->tpl_vars['item']->value['text_no'];?>
</div>
            </div>
        </div>
    <?php } ?>
</div>

<?php echo smarty_function_hook(array('run'=>'admin_stats_notification_item'),$_smarty_tpl);?>
<?php }} ?>