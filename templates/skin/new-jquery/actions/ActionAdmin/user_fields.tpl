{include file='header.tpl'}


<h1>{$aLang.user_field_admin_title}</h1>


<div class="userfield-form" id="userfield_form">
	<p><label for="user_fields_add_name">{$aLang.userfield_form_name}</label><br />
	<input type="text" id="user_fields_form_name" class="input-text" /></p>
	
	<p><label for="user_fields_add_title">{$aLang.userfield_form_title}</label><br />
	<input type="text" id="user_fields_form_title" class="input-text" /></p>
    
    <p><label for="user_fields_add_pattern">{$aLang.userfield_form_pattern}</label><br />
	<input type="text" id="user_fields_form_pattern" class="input-text" /></p>
	
	<input type="hidden" id="user_fields_form_action" />
	<input type="hidden" id="user_fields_form_id" />
	
	<input type="button" value="{$aLang.user_field_add}" onclick="ls.userfield.applyForm(); return false;" />
	<input type="button" value="{$aLang.user_field_cancel}" class="jqmClose" />
</div>

 
<a href="javascript:ls.userfield.showAddForm()" class="userfield-add" id="userfield_form_show">{$aLang.user_field_add}</a>
<br /><br />

<ul class="userfield-list" id="user_field_list">
	{foreach from=$aUserFields item=oField}
		<li id="field_{$oField->getId()}"><span class="userfield_admin_name">{$oField->getName()|escape:"html"}</span>
			/ <span class="userfield_admin_title">{$oField->getTitle()|escape:"html"}</span>
            / <span class="userfield_admin_pattern">{$oField->getPattern()|escape:"html"}</span>
			
			<div class="uf-actions">
				<a href="javascript:ls.userfield.showEditForm({$oField->getId()})" title="{$aLang.user_field_update}"><img src="{cfg name='path.static.skin'}/images/edit.png" alt="image" /></a> 
				<a href="javascript:ls.userfield.deleteUserfield({$oField->getId()})" title="{$aLang.user_field_delete}"><img src="{cfg name='path.static.skin'}/images/delete.png" alt="image" /></a>
			</div>
		</li>
	{/foreach}
</ul>
	

{include file='footer.tpl'}