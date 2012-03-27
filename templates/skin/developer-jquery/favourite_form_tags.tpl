{if $oUserCurrent}
	<div id="favourite-form-tags" style="display:none;">
		<form onsubmit="return ls.favourite.saveTags(this);">
			<input type="hidden" name="target_type" value="" id="favourite-form-tags-target-type">
			<input type="hidden" name="target_id" value="" id="favourite-form-tags-target-id">

			<input type="text" name="tags" value="" id="favourite-form-tags-tags" class="autocomplete-tags-sep"><br/>
			<button name="" class="button button-primary" />{$aLang.favourite_form_tags_button_save}</button>
			<button name="" class="button" onclick="return ls.favourite.hideEditTags();" />{$aLang.favourite_form_tags_button_cancel}</button>
		</form>
	</div>
{/if}