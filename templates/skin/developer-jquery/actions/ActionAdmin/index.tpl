{include file='header.tpl'}



<h2 class="page-header">{$aLang.admin_header}</h2>

<ul>
    <li><a href="{router page="admin"}plugins">{$aLang.admin_list_plugins}</a></li>
    <li><a href="{router page="admin"}userfields">{$aLang.admin_list_userfields}</a></li>
    <li><a href="{router page="admin"}restorecomment">{$aLang.admin_list_restorecomment}</a></li>
    <li><a href="{router page="admin"}recalcfavourite">{$aLang.admin_list_recalcfavourite}</a></li>
    <li><a href="{router page="admin"}recalcvote">{$aLang.admin_list_recalcvote}</a></li>
    {hook run='admin_action_item'}
</ul>



{include file='footer.tpl'}