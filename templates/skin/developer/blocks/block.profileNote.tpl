{**
 * Блок с заметкой о пользователе
 *
 * @styles css/blocks.css
 *}

{extends file='blocks/block.aside.base.tpl'}

{block name='block_options'}
	{if ! $oUserCurrent or ( $oUserCurrent and $oUserCurrent->getId() == $oUserProfile->getId() )}
		{$bBlockNotShow = true}
	{/if}
{/block}

{block name='block_type'}profile-note{/block}

{block name='block_content_after'}
	<div class="user-note js-user-note" data-user-id="{$oUserProfile->getId()}">
		<div class="user-note-content js-user-note-content">
			<p class="user-note-text js-user-note-text" {if ! $oUserNote}style="display: none"{/if}>
				{if $oUserNote}
					{$oUserNote->getText()}
				{/if}
			</p>
			
			<ul class="actions user-note-actions js-user-note-actions" {if ! $oUserNote}style="display: none;"{/if}>
				<li><a href="#" class="link-dotted js-user-note-edit-button">{$aLang.user_note_form_edit}</a></li>
				<li><a href="#" class="link-dotted js-user-note-remove-button">{$aLang.user_note_form_delete}</a></li>
			</ul>

			<a href="#" class="link-dotted js-user-note-add-button" {if $oUserNote}style="display:none;"{/if}>{$aLang.user_note_add}</a>
		</div>
	
		<div class="js-user-note-edit" style="display: none;">
			<textarea rows="4" cols="20" class="width-full mb-15 js-user-note-edit-text"></textarea>

			<button type="submit" class="button button-primary js-user-note-edit-save">{$aLang.user_note_form_save}</button>
			<button type="submit" class="button js-user-note-edit-cancel">{$aLang.user_note_form_cancel}</button>
		</div>
	</div>
{/block}