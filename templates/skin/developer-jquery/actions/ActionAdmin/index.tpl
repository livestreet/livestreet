{include file='header.tpl'}

<ul>
    <li><a href="{router page="admin"}plugins">{$aLang.admin_list_plugins}</a></li>
    <li><a href="{router page="admin"}userfields">{$aLang.admin_list_userfields}</a></li>
    <li><a href="{router page="admin"}restorecomment">{$aLang.admin_list_restorecomment}</a></li>
    <li><a href="{router page="admin"}recalcfavourite">{$aLang.admin_list_recalcfavourite}</a></li>
    {hook run='admin_action_item'}
</ul>

{include file='footer.tpl'}