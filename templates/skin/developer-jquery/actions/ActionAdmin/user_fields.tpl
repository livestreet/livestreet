{include file='header.tpl' showWhiteBack=true}

<div>
    <h3>{$aLang.user_field_admin_title}</h3>
    <input type="text" id="user_fields_add_name" />
    <a href="javascript:addUserfield();">{$aLang.user_field_add}</a>
    <br /><br />
    <ul id="user_field_list">
        {foreach from=$aUserFields item=aField}
            <li id="field_{$aField.id}">{$aField.name} <a href="javascript:deleteUserfield({$aField.id})">{$aLang.user_field_delete}</a></li>
        {/foreach}
    </ul>
</div>

{include file='footer.tpl'}