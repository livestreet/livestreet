<?php /* Smarty version Smarty-3.1.13, created on 2018-11-24 09:24:24
         compiled from "/var/www/ls.new/application/plugins/admin/frontend/components/p-skin/skin.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2310589095bf8ee98d0a5a8-31234644%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9b959f81bf246d829e572850b332512d223aab4b' => 
    array (
      0 => '/var/www/ls.new/application/plugins/admin/frontend/components/p-skin/skin.tpl',
      1 => 1543039194,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2310589095bf8ee98d0a5a8-31234644',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'component' => 0,
    'mods' => 0,
    'classes' => 0,
    'attributes' => 0,
    'skin' => 0,
    'aLang' => 0,
    'aTheme' => 0,
    'theme' => 0,
    'LIVESTREET_SECURITY_KEY' => 0,
    'menu' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5bf8ee98e1f491_76436554',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5bf8ee98e1f491_76436554')) {function content_5bf8ee98e1f491_76436554($_smarty_tpl) {?><?php if (!is_callable('smarty_function_component_define_params')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component_define_params.php';
if (!is_callable('smarty_function_cmods')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.cmods.php';
if (!is_callable('smarty_function_cattr')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.cattr.php';
if (!is_callable('smarty_function_component')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component.php';
if (!is_callable('smarty_function_router')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.router.php';
?>

<?php $_smarty_tpl->tpl_vars['component'] = new Smarty_variable('ls-plugin', null, 0);?>
<?php echo smarty_function_component_define_params(array('params'=>array('skin','mods','classes','attributes')),$_smarty_tpl);?>


<?php $_smarty_tpl->tpl_vars['mods'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['mods']->value)." skin", null, 0);?>

<div class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
 <?php echo smarty_function_cmods(array('name'=>$_smarty_tpl->tpl_vars['component']->value,'mods'=>$_smarty_tpl->tpl_vars['mods']->value),$_smarty_tpl);?>
 <?php echo $_smarty_tpl->tpl_vars['classes']->value;?>
" <?php echo smarty_function_cattr(array('list'=>$_smarty_tpl->tpl_vars['attributes']->value),$_smarty_tpl);?>
>
    
    <img class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-image" src="<?php echo $_smarty_tpl->tpl_vars['skin']->value->getPreviewImage();?>
">

    <div class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-body">
        
        <h2 class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-title">
            <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['skin']->value->getViewName(), ENT_QUOTES, 'UTF-8', true);?>

        </h2>

        <div class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-info-main">
            
            <div class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-version">v<?php echo $_smarty_tpl->tpl_vars['skin']->value->getVersion();?>
</div>

            
            <span class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-author">
                от <span class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-author-name"><?php echo $_smarty_tpl->tpl_vars['skin']->value->getAuthor();?>
</span>
            </span>
        </div>

        
        <div class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-desc ls-text">
            <?php echo htmlspecialchars(preg_replace('!<[^>]*?>!', ' ', $_smarty_tpl->tpl_vars['skin']->value->getDescription()), ENT_QUOTES, 'UTF-8', true);?>

        </div>

        
        <?php if ($_smarty_tpl->tpl_vars['skin']->value->getXml()||!$_smarty_tpl->tpl_vars['skin']->value->getIsCurrent()){?>
            <ul class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-info">
                <?php if ($_smarty_tpl->tpl_vars['skin']->value->getHomepage()){?>
                    <li class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-info-item">
                        <?php echo smarty_function_component(array('_default_short'=>'icon','icon'=>'home'),$_smarty_tpl);?>
 <?php echo $_smarty_tpl->tpl_vars['skin']->value->getHomepage();?>

                    </li>
                <?php }?>

                <?php if (!$_smarty_tpl->tpl_vars['skin']->value->getIsCurrent()&&$_smarty_tpl->tpl_vars['skin']->value->getThemes()){?>
                    <li class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-info-item">
                        <?php echo $_smarty_tpl->tpl_vars['aLang']->value['plugin']['admin']['skin']['themes'];?>
:
                        <?php  $_smarty_tpl->tpl_vars['aTheme'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['aTheme']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['skin']->value->getThemes(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['aTheme']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['aTheme']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['aTheme']->key => $_smarty_tpl->tpl_vars['aTheme']->value){
$_smarty_tpl->tpl_vars['aTheme']->_loop = true;
 $_smarty_tpl->tpl_vars['aTheme']->iteration++;
 $_smarty_tpl->tpl_vars['aTheme']->last = $_smarty_tpl->tpl_vars['aTheme']->iteration === $_smarty_tpl->tpl_vars['aTheme']->total;
?>
                            <strong><?php echo $_smarty_tpl->tpl_vars['aTheme']->value['value'];?>
</strong>
                            (<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['aTheme']->value['description'], ENT_QUOTES, 'UTF-8', true);?>
)
                            <?php if (!$_smarty_tpl->tpl_vars['aTheme']->last){?>,<?php }?>
                        <?php } ?>
                    </li>
                <?php }?>
            </ul>
        <?php }?>
    </div>

    
    <div class="<?php echo $_smarty_tpl->tpl_vars['component']->value;?>
-actions">
        <?php if ($_smarty_tpl->tpl_vars['skin']->value->getIsCurrent()){?>
            
            <?php $_smarty_tpl->tpl_vars['menu'] = new Smarty_variable(array(), null, 0);?>

            <?php  $_smarty_tpl->tpl_vars['theme'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['theme']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['skin']->value->getThemes(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['theme']->key => $_smarty_tpl->tpl_vars['theme']->value){
$_smarty_tpl->tpl_vars['theme']->_loop = true;
?>
                <?php ob_start();?><?php echo smarty_function_router(array('page'=>('admin/skins/changetheme/').($_smarty_tpl->tpl_vars['skin']->value->getName())),$_smarty_tpl);?>
<?php $_tmp1=ob_get_clean();?><?php $_smarty_tpl->createLocalArrayVariable('menu', null, 0);
$_smarty_tpl->tpl_vars['menu']->value[] = array('name'=>$_smarty_tpl->tpl_vars['theme']->value['value'],'url'=>$_tmp1."?theme=".((string)$_smarty_tpl->tpl_vars['theme']->value['value'])."&security_ls_key=".((string)$_smarty_tpl->tpl_vars['LIVESTREET_SECURITY_KEY']->value),'text'=>$_smarty_tpl->tpl_vars['theme']->value['description']);?>
            <?php } ?>

            <?php echo smarty_function_component(array('_default_short'=>'dropdown','text'=>'...','classes'=>'js-admin-actionbar-dropdown','activeItem'=>Config::Get('view.theme'),'menu'=>$_smarty_tpl->tpl_vars['menu']->value),$_smarty_tpl);?>

        <?php }else{ ?>
            
            <?php echo smarty_function_component(array('_default_short'=>'admin:button','mods'=>'block primary','url'=>$_smarty_tpl->tpl_vars['skin']->value->getChangeSkinUrl(),'text'=>$_smarty_tpl->tpl_vars['aLang']->value['plugin']['admin']['skin']['use_skin']),$_smarty_tpl);?>


            
            <?php if ($_smarty_tpl->tpl_vars['skin']->value->getInPreview()){?>
                <?php echo smarty_function_component(array('_default_short'=>'admin:button','mods'=>'block','classes'=>'active','url'=>$_smarty_tpl->tpl_vars['skin']->value->getTurnOffPreviewUrl(),'text'=>$_smarty_tpl->tpl_vars['aLang']->value['plugin']['admin']['skin']['preview_skin']),$_smarty_tpl);?>

            <?php }else{ ?>
                <?php echo smarty_function_component(array('_default_short'=>'admin:button','mods'=>'block','url'=>$_smarty_tpl->tpl_vars['skin']->value->getTurnOnPreviewUrl(),'text'=>$_smarty_tpl->tpl_vars['aLang']->value['plugin']['admin']['skin']['preview_skin']),$_smarty_tpl);?>

            <?php }?>
        <?php }?>
    </div>
</div>
<?php }} ?>