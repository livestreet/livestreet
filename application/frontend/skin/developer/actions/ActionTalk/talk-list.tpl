{**
 * Список диалогов
 *}

{if $aTalks}
	<form action="{router page='talk'}" method="post" id="talk-form">
		{* Скрытые поля *}
		{include 'components/field/field.hidden.security_key.tpl'}
		{include 'components/field/field.hidden.tpl' sName='form_action' sId='talk-form-action'}

		{* Экшнбар *}
		{include 'components/actionbar/actionbar.item.select.tpl' sItemSelector='.js-message-list-item' assign=sMessagesSelect aItems=[
			[ 'text' => 'Прочитанные', 'filter' => ":not('.message-unread')" ],
			[ 'text' => 'Не прочитанные', 'filter' => ".message-unread" ]
		]}

		{include 'components/actionbar/actionbar.tpl' aItems=[
			[ 'html' => $sMessagesSelect ],
			[ 'icon' => 'icon-ok', 'classes' => 'js-talk-form-action', 'attributes' => 'data-action="mark_as_read"', 'text' => $aLang.talk_inbox_make_read ],
			[ 'icon' => 'icon-remove', 'classes' => 'js-talk-form-action', 'attributes' => 'data-action="remove"', 'text' => $aLang.common.remove ]
		]}

		{* Список сообщений *}
		<table class="table table-talk message-list">
			<tbody>
				{foreach $aTalks as $oTalk}
					{* Создатель диалога *}
					{$oAuthor = $oTalk->getTalkUser()}

					{* Все участники диалога *}
					{$aUsers = $oTalk->getTalkUsers()}

					{* Кол-во участников диалога *}
					{$iUsersCount = count($aUsers)}

					<tr class="message-list-item {if $oAuthor->getCommentCountNew() or ! $oAuthor->getDateLast()}message-unread{/if} js-message-list-item" data-id="{$oTalk->getId()}">
						{* Выделение *}
						<td class="cell-checkbox">
							<input type="checkbox" name="talk_select[{$oTalk->getId()}]" data-id="{$oTalk->getId()}" />
						</td>

						{* Избранное *}
						<td class="cell-favourite">
							{include 'components/favourite/favourite.tpl' sClasses='js-favourite-talk' oObject=$oTalk}
						</td>

						{* Основная информация о диалоге *}
						<td class="cell-info">
							<div class="message-list-info">
								{* Участники диалога *}
								{if $iUsersCount > 2}
									<a href="{router page='talk'}read/{$oTalk->getId()}/" class="message-list-info-avatar">
										<img src="{cfg name="path.skin.web"}/assets/images/avatars/avatar_male_64x64.png" />
									</a>

									{$iUsersCount} участника
								{else}
									{* Если участников двое, то отображаем только собеседника *}
									{foreach $aUsers as $oUser}
										{$oUser = $oUser->getUser()}

										{if $oUser->getUserId() != $oUserCurrent->getId()}
											<a href="{$oUser->getUserWebPath()}" class="message-list-info-avatar">
												<img src="{$oUser->getProfileAvatarPath(64)}" alt="{$oUser->getLogin()}" />
											</a>

											<a href="{$oUser->getUserWebPath()}" class="word-wrap">{$oUser->getDisplayName()}</a>
										{/if}
									{/foreach}
								{/if}

								{* Дата *}
								<time class="message-list-info-date" datetime="{date_format date=$oTalk->getDate() format='c'}" title="{date_format date=$oTalk->getDate() format='j F Y, H:i'}">
									{date_format date=$oTalk->getDate() hours_back="12" minutes_back="60" now="60" day="day H:i" format="j F Y, H:i"}
								</time>
							</div>
						</td>

						{* Заголовок и текст последнего сообщения *}
						<td>
							<div class="message-list-item-extra">
								{* Заголовок *}
								<h2 class="message-list-item-title">
									<a href="{router page='talk'}read/{$oTalk->getId()}/">
										{$oTalk->getTitle()|escape:'html'}
									</a>
								</h2>

								{* Текст последнего сообщения *}
								<div class="message-list-item-text">
									{(($oTalk->getCommentLast()) ? $oTalk->getCommentLast()->getText() : $oTalk->getText())|strip_tags|truncate:120:"..."|escape}
								</div>

								{* Кол-во сообщений *}
								{if $oTalk->getCountComment()}
									<div class="message-list-item-count">
										{$oTalk->getCountComment()}

										{if $oAuthor->getCommentCountNew()}
											<strong>+{$oAuthor->getCommentCountNew()}</strong>
										{/if}
									</div>
								{/if}
							</div>
						</td>
					</tr>
				{/foreach}
			</tbody>
		</table>
	</form>
{else}
	{include 'components/alert/alert.tpl' mAlerts=$aLang.messages.alerts.empty sMods='empty'}
{/if}