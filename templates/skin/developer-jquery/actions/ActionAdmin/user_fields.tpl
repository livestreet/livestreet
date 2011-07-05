{include file='header.tpl' showWhiteBack=true}

<div>
    <h3>{$aLang.user_field_admin_title}</h3>
    <div style="margin-left:300px;background-color:white;height:40px;width:600px;border: 1px solid black;display:none; position:absolute" id="userfield_form">
        <label for="user_fields_add_name">{$aLang.userfield_form_name}</label>
        <input type="text" id="user_fields_form_name" />
        <label for="user_fields_add_title">{$aLang.userfield_form_title}</label>
        <input type="text" id="user_fields_form_title" />
        <input type="hidden" id="user_fields_form_action" />
        <input type="hidden" id="user_fields_form_id" />
        <a href="javascript:userfieldApplyForm()">Ok</a>
     </div>
    <a href="javascript:userfieldShowAddForm()">{$aLang.user_field_add}</a>
    <br /><br />
    <ul id="user_field_list">
        {foreach from=$aUserFields item=aField}
            <li id="field_{$aField.id}"><span class="userfield_admin_name">{$aField.name}</span >("<span class="userfield_admin_title">{$aField.title}</span>")<a href="javascript:userfieldShowEditForm({$aField.id})">{$aLang.user_field_update}</a> <a href="javascript:deleteUserfield({$aField.id})">{$aLang.user_field_delete}</a></li>
        {/foreach}
    </ul>
</div>

{include file='footer.tpl'}