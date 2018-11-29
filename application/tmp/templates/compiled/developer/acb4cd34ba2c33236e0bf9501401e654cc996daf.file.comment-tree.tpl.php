<?php /* Smarty version Smarty-3.1.13, created on 2018-11-25 11:43:23
         compiled from "/var/www/ls.new/application/frontend/components/comment/comment-tree.tpl" */ ?>
<?php /*%%SmartyHeaderCode:7815204365bfa60ab77f056-26496880%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'acb4cd34ba2c33236e0bf9501401e654cc996daf' => 
    array (
      0 => '/var/www/ls.new/application/frontend/components/comment/comment-tree.tpl',
      1 => 1543035802,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '7815204365bfa60ab77f056-26496880',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'comments' => 0,
    'comment' => 0,
    'maxLevel' => 0,
    'currentLevel' => 0,
    'commentLevel' => 0,
    'hookPrefixComment' => 0,
    'dateReadLast' => 0,
    'authorId' => 0,
    'authorText' => 0,
    'forbidAdd' => 0,
    'showReply' => 0,
    'commentParams' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5bfa60ab993998_28494029',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5bfa60ab993998_28494029')) {function content_5bfa60ab993998_28494029($_smarty_tpl) {?><?php if (!is_callable('smarty_function_component_define_params')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component_define_params.php';
if (!is_callable('smarty_function_component')) include '/var/www/ls.new/framework/classes/modules/viewer/plugs/function.component.php';
?>

<?php echo smarty_function_component_define_params(array('params'=>array('hookPrefixComment','authorId','authorText','commentParams','comments','dateReadLast','forbidAdd','maxLevel','showReply')),$_smarty_tpl);?>



<?php $_smarty_tpl->tpl_vars['currentLevel'] = new Smarty_variable(-1, null, 0);?>


<?php  $_smarty_tpl->tpl_vars['comment'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['comment']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['comments']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['comment']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['comment']->iteration=0;
 $_smarty_tpl->tpl_vars['comment']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['comment']->key => $_smarty_tpl->tpl_vars['comment']->value){
$_smarty_tpl->tpl_vars['comment']->_loop = true;
 $_smarty_tpl->tpl_vars['comment']->iteration++;
 $_smarty_tpl->tpl_vars['comment']->index++;
 $_smarty_tpl->tpl_vars['comment']->first = $_smarty_tpl->tpl_vars['comment']->index === 0;
 $_smarty_tpl->tpl_vars['comment']->last = $_smarty_tpl->tpl_vars['comment']->iteration === $_smarty_tpl->tpl_vars['comment']->total;
?>
    
    <?php $_smarty_tpl->tpl_vars['commentLevel'] = new Smarty_variable($_smarty_tpl->tpl_vars['comment']->value->getLevel()>$_smarty_tpl->tpl_vars['maxLevel']->value ? $_smarty_tpl->tpl_vars['maxLevel']->value : $_smarty_tpl->tpl_vars['comment']->value->getLevel(), null, 0);?>

    
    <?php if ($_smarty_tpl->tpl_vars['currentLevel']->value>$_smarty_tpl->tpl_vars['commentLevel']->value){?>
        <?php if (isset($_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers1'])) unset($_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers1']);
$_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers1']['name'] = 'closewrappers1';
$_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers1']['loop'] = is_array($_loop=$_smarty_tpl->tpl_vars['currentLevel']->value-$_smarty_tpl->tpl_vars['commentLevel']->value+1) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers1']['show'] = true;
$_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers1']['max'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers1']['loop'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers1']['step'] = 1;
$_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers1']['start'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers1']['step'] > 0 ? 0 : $_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers1']['loop']-1;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers1']['show']) {
    $_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers1']['total'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers1']['loop'];
    if ($_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers1']['total'] == 0)
        $_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers1']['show'] = false;
} else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers1']['total'] = 0;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers1']['show']):

            for ($_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers1']['index'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers1']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers1']['iteration'] = 1;
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers1']['iteration'] <= $_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers1']['total'];
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers1']['index'] += $_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers1']['step'], $_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers1']['iteration']++):
$_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers1']['rownum'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers1']['iteration'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers1']['index_prev'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers1']['index'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers1']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers1']['index_next'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers1']['index'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers1']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers1']['first']      = ($_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers1']['iteration'] == 1);
$_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers1']['last']       = ($_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers1']['iteration'] == $_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers1']['total']);
?></div><?php endfor; endif; ?>
    <?php }elseif($_smarty_tpl->tpl_vars['currentLevel']->value==$_smarty_tpl->tpl_vars['commentLevel']->value&&!$_smarty_tpl->tpl_vars['comment']->first){?>
        </div>
    <?php }?>

    
    <?php $_smarty_tpl->tpl_vars['currentLevel'] = new Smarty_variable($_smarty_tpl->tpl_vars['commentLevel']->value, null, 0);?>

    
    <div class="ls-comment-wrapper js-comment-wrapper" data-id="<?php echo $_smarty_tpl->tpl_vars['comment']->value->getId();?>
">

    
    
        <?php echo smarty_function_component(array('_default_short'=>'comment','hookPrefix'=>$_smarty_tpl->tpl_vars['hookPrefixComment']->value,'comment'=>$_smarty_tpl->tpl_vars['comment']->value,'dateReadLast'=>$_smarty_tpl->tpl_vars['dateReadLast']->value,'authorId'=>$_smarty_tpl->tpl_vars['authorId']->value,'authorText'=>$_smarty_tpl->tpl_vars['authorText']->value,'showReply'=>!$_smarty_tpl->tpl_vars['forbidAdd']->value||$_smarty_tpl->tpl_vars['showReply']->value,'params'=>$_smarty_tpl->tpl_vars['commentParams']->value),$_smarty_tpl);?>

    

    
    <?php if ($_smarty_tpl->tpl_vars['comment']->last){?>
        <?php if (isset($_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers2'])) unset($_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers2']);
$_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers2']['name'] = 'closewrappers2';
$_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers2']['loop'] = is_array($_loop=$_smarty_tpl->tpl_vars['currentLevel']->value+1) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers2']['show'] = true;
$_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers2']['max'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers2']['loop'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers2']['step'] = 1;
$_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers2']['start'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers2']['step'] > 0 ? 0 : $_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers2']['loop']-1;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers2']['show']) {
    $_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers2']['total'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers2']['loop'];
    if ($_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers2']['total'] == 0)
        $_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers2']['show'] = false;
} else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers2']['total'] = 0;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers2']['show']):

            for ($_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers2']['index'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers2']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers2']['iteration'] = 1;
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers2']['iteration'] <= $_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers2']['total'];
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers2']['index'] += $_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers2']['step'], $_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers2']['iteration']++):
$_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers2']['rownum'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers2']['iteration'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers2']['index_prev'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers2']['index'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers2']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers2']['index_next'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers2']['index'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers2']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers2']['first']      = ($_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers2']['iteration'] == 1);
$_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers2']['last']       = ($_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers2']['iteration'] == $_smarty_tpl->tpl_vars['smarty']->value['section']['closewrappers2']['total']);
?>
            </div>
        <?php endfor; endif; ?>
    <?php }?>
<?php } ?><?php }} ?>