{extends file='layouts/layout.base.tpl'}

{block name='layout_options'}
	{$bNoSidebar = true}
{/block}

{block name='layout_page_title'}<a href="{router page='admin'}">{$aLang.admin_header}</a> <span>&raquo;</span> {$aLang.user_field_admin_title}{/block}

{block name='layout_content'}
	{include file='actions/ActionAdmin/modal.userfields.tpl'}

	<button onclick="ls.userfield.showAddForm()" class="button button-primary">{$aLang.user_field_add}</button>
	<br /><br />

	<ul class="userfield-list" id="user_field_list">
		{foreach $aUserFields as $oField}
			<li id="field_{$oField->getId()}">
				<strong>{$oField->getName()|escape:"html"}</strong>

				/ <span class="userfield_admin_title">{$oField->getTitle()|escape:"html"}</span>
	            / <span class="userfield_admin_type">{$oField->getType()|escape:"html"}</span>
	            / <span class="userfield_admin_pattern">{$oField->getPattern()|escape:"html"}</span>

				<div class="userfield-actions">
					<a href="javascript:ls.userfield.showEditForm({$oField->getId()})" title="{$aLang.user_field_update}" class="icon-edit"></a>
					<a href="javascript:ls.userfield.deleteUserfield({$oField->getId()})" title="{$aLang.user_field_delete}" class="icon-remove"></a>
				</div>
			</li>
		{/foreach}
	</ul>
{/block}