{**
 * Заметка
 *
 * @param object   $oUserNote          Заметка
 * @param integer  $iUserNoteId        ID сущности
 * @param boolean  $bUserNoteEditable  Можно редактировать заметку или нет    
 * @param string   $sUserNoteClasses   Дополнительные классы  
 *
 * @styles <common>/css/common.css
 * @scripts <common>/js/usernote.js
 *}

{$bUserNoteEditable = $bUserNoteEditable|default:true}

<div class="user-note js-user-note {$sUserNoteClasses}" data-user-id="{$iUserNoteId}">
	<div class="user-note-content js-user-note-content">
		<p class="user-note-text js-user-note-text" {if ! $oUserNote}style="display: none"{/if}>
			{if $oUserNote}
				{$oUserNote->getText()}
			{/if}
		</p>
		
		{if $bUserNoteEditable}
			<ul class="actions user-note-actions js-user-note-actions" {if ! $oUserNote}style="display: none;"{/if}>
				<li><a href="#" class="link-dotted js-user-note-edit-button">{$aLang.common.edit}</a></li>
				<li><a href="#" class="link-dotted js-user-note-remove-button">{$aLang.common.remove}</a></li>
			</ul>

			<a href="#" class="link-dotted js-user-note-add-button" {if $oUserNote}style="display:none;"{/if}>{$aLang.user_note.add}</a>
		{/if}
	</div>

	{if $bUserNoteEditable}
		<div class="js-user-note-edit" style="display: none;">
			<textarea rows="4" cols="20" class="width-full mb-15 js-user-note-edit-text"></textarea>

			<button type="submit" class="button button-primary js-user-note-edit-save">{$aLang.common.save}</button>
			<button type="submit" class="button js-user-note-edit-cancel">{$aLang.common.cancel}</button>
		</div>
	{/if}
</div>