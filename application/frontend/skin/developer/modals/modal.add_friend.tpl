{**
 * Добавление в друзья
 *
 * @styles css/modals.css
 *}

{extends 'components/modal/modal.tpl'}

{block name='modal_id'}modal-add-friend{/block}
{block name='modal_class'}js-modal-default{/block}
{block name='modal_title'}{$aLang.profile_add_friend}{/block}

{block name='modal_content'}
	<form id="add_friend_form" onsubmit="return ls.user.addFriend(this,{$oUserProfile->getId()},'add');">
		{include file='forms/fields/form.field.textarea.tpl'
				 sFieldName     = 'add_friend_text'
				 sFieldRules    = 'required="true" rangelength="[2,200]"'
				 iFieldRows     = 3
				 bFieldNoMargin = true
				 sFieldLabel    = $aLang.user_friend_add_text_label}
	</form>
{/block}

{block name='modal_footer_begin'}
	<button type="submit" class="button button-primary" onclick="jQuery('#add_friend_form').submit()">{$aLang.user_friend_add_submit}</button>
{/block}