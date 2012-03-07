{include file='header.tpl'}


<h2 class="page-header">{$aLang.user_field_admin_title}</h2>


<div class="jqmWindow modal-userfields" id="userfield_form">
	<header>
		<h3>{$aLang.user_field_admin_title_add}</h3>
		<a href="#" class="close jqmClose"></a>
	</header>

	<form>
		<p><label for="user_fields_add_name">{$aLang.userfield_form_name}:</label>
		<input type="text" id="user_fields_form_name" class="input-text input-width-full" /></p>
		
		<p><label for="user_fields_add_title">{$aLang.userfield_form_title}:</label>
		<input type="text" id="user_fields_form_title" class="input-text input-width-full" /></p>
		
		<p><label for="user_fields_add_pattern">{$aLang.userfield_form_pattern}:</label>
		<input type="text" id="user_fields_form_pattern" class="input-text input-width-full" /></p>
		
		<input type="hidden" id="user_fields_form_action" />
		<input type="hidden" id="user_fields_form_id" />
		
		<input type="button" value="{$aLang.user_field_add}" onclick="ls.userfield.applyForm(); return false;" class="button" />
	</form>
</div>

 
<a href="javascript:ls.userfield.showAddForm()" class="link-dashed" id="userfield_form_show">{$aLang.user_field_add}</a>
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