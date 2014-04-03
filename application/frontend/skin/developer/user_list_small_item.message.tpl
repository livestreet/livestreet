{**
 * Список пользователей с элементами управления / Пользователь
 * Расширяет основной шаблон с пользователем добавляя кнопку "Повторно отправить приглашение" в блоке "Пригласить пользователей в блог"
 *}

{extends 'user_list_small_item.tpl'}

{block 'user_list_small_item_classes'}
	{if $oUserContainer && $oUserContainer->getUserActive() != $TALK_USER_ACTIVE}inactive{/if}
{/block}

{block 'user_list_small_item_attributes'}
	{if $oUserContainer && $oUserContainer->getUserActive() != $TALK_USER_ACTIVE}title="Пользователь не участвует в разговоре"{/if}
{/block}

{block 'user_list_small_item_actions'}
	<li class="icon-minus js-message-users-user-inactivate" title="{$aLang.common.remove}" data-user-id="{$iUserId}"></li>
	<li class="icon-plus js-message-users-user-activate" title="{$aLang.common.add}" data-user-id="{$iUserId}" data-user-login="{$oUser->getLogin()}"></li>
{/block}