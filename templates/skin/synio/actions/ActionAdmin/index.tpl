{include file='header.tpl'}



<h2 class="page-header">{$aLang.admin_header}</h2>

<ul>
    <li><a href="{router page="admin"}plugins/">{$aLang.admin_list_plugins}</a></li>
    <li><a href="{router page="admin"}userfields/">{$aLang.admin_list_userfields}</a></li>
    <li><a href="{router page="admin"}restorecomment/?security_ls_key={$LIVESTREET_SECURITY_KEY}">{$aLang.admin_list_restorecomment}</a></li>
    <li><a href="{router page="admin"}recalcfavourite/?security_ls_key={$LIVESTREET_SECURITY_KEY}">{$aLang.admin_list_recalcfavourite}</a></li>
    <li><a href="{router page="admin"}recalcvote/?security_ls_key={$LIVESTREET_SECURITY_KEY}">{$aLang.admin_list_recalcvote}</a></li>
    <li><a href="{router page="admin"}recalctopic/?security_ls_key={$LIVESTREET_SECURITY_KEY}">{$aLang.admin_list_recalctopic}</a></li>
    {hook run='admin_action_item'}
</ul>

{hook run='admin_action'}



{include file='footer.tpl'}