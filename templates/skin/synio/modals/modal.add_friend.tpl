{**
 * Добавление в друзья
 *
 * @styles css/modals.css
 *}

{extends file='modals/modal_base.tpl'}

{block name='modal_id'}modal-add-friend{/block}
{block name='modal_class'}add_friend_form js-modal-default{/block}
{block name='modal_title'}{$aLang.profile_add_friend}{/block}

{block name='modal_content'}
	<form id="add_friend_form" onsubmit="return ls.user.addFriend(this,{$oUserProfile->getId()},'add');">
		<label for="add_friend_text">{$aLang.user_friend_add_text_label}</label>
		<textarea id="add_friend_text" rows="3" class="input-text input-width-full"></textarea>
	</form>
{/block}

{block name='modal_footer_begin'}
	<button type="submit" class="button button-primary" onclick="jQuery('#add_friend_form').submit()">{$aLang.user_friend_add_submit}</button>
{/block}