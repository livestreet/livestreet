{include file='header.tpl' showWhiteBack=true}


<h1>{$aLang.user_field_admin_title}</h1>


<div class="userfield-form" id="userfield_form">
	<p><label for="user_fields_add_name">{$aLang.userfield_form_name}</label><br />
	<input type="text" id="user_fields_form_name" class="input-text" /></p>
	
	<p><label for="user_fields_add_title">{$aLang.userfield_form_title}</label><br />
	<input type="text" id="user_fields_form_title" class="input-text" /></p>
	
	<input type="hidden" id="user_fields_form_action" />
	<input type="hidden" id="user_fields_form_id" />
	
	<input type="button" value="{$aLang.user_field_add}" onclick="userfieldApplyForm(); return false;" />
	<input type="button" value="{$aLang.user_field_cancel}" onclick="userfieldCloseForm(); return false;" />
</div>

 
<a href="javascript:userfieldShowAddForm()" class="userfield-add">{$aLang.user_field_add}</a>
<br /><br />

<ul class="userfield-list" id="user_field_list">
	{foreach from=$aUserFields item=aField}
		<li id="field_{$aField.id}"><span class="userfield_admin_name">{$aField.name}</span>
			/ <span class="userfield_admin_title">{$aField.title}</span>
			
			<div class="uf-actions">
				<a href="javascript:userfieldShowEditForm({$aField.id})" title="{$aLang.user_field_update}"><img src="{cfg name='path.static.skin'}/images/edit.gif" alt="image" /></a> 
				<a href="javascript:deleteUserfield({$aField.id})" title="{$aLang.user_field_delete}"><img src="{cfg name='path.static.skin'}/images/delete.gif" alt="image" /></a>
			</div>
		</li>
	{/foreach}
</ul>
	

{include file='footer.tpl'}