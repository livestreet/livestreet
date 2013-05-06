{**
 * Блок с заметкой о пользователе
 *
 * @styles css/blocks.css
 *}

{extends file='blocks/block.aside.base.tpl'}

{block name='block_options'}
	{assign var='noBlockHeader' value=true}
	{assign var='noBlockNav' value=true}
	{assign var='noBlockContent' value=true}
	{assign var='noBlockFooter' value=true}
{/block}

{block name='block_type'}profile-note{/block}

{block name='block_content_after'}
	{if $oUserNote}
		<script type="text/javascript">
			ls.usernote.sText = {json var = $oUserNote->getText()};
		</script>
	{/if}

	<div id="usernote-note" class="profile-note" {if !$oUserNote}style="display: none;"{/if}>
		<p id="usernote-note-text">
			{if $oUserNote}
				{$oUserNote->getText()}
			{/if}
		</p>
		
		<ul class="actions">
			<li><a href="#" onclick="return ls.usernote.showForm();" class="link-dotted">{$aLang.user_note_form_edit}</a></li>
			<li><a href="#" onclick="return ls.usernote.remove({$oUserProfile->getId()});" class="link-dotted">{$aLang.user_note_form_delete}</a></li>
		</ul>
	</div>
	
	<div id="usernote-form" style="display: none;">
		<p><textarea rows="4" cols="20" id="usernote-form-text" class="input-text input-width-full"></textarea></p>
		<button type="submit" onclick="return ls.usernote.save({$oUserProfile->getId()});" class="button button-primary">{$aLang.user_note_form_save}</button>
		<button type="submit" onclick="return ls.usernote.hideForm();" class="button">{$aLang.user_note_form_cancel}</button>
	</div>
	
	<a href="#" onclick="return ls.usernote.showForm();" id="usernote-button-add" class="link-dotted" {if $oUserNote}style="display:none;"{/if}>{$aLang.user_note_add}</a>
{/block}