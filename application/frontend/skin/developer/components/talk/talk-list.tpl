{**
 * Список диалогов
 *
 * @param array   $talks
 * @param boolean $selectable
 *}

{if $smarty.local.talks}
	<form action="{router page='talk'}" method="post" id="talk-form">
		{* Скрытые поля *}
		{include 'components/field/field.hidden.security_key.tpl'}
		{include 'components/field/field.hidden.tpl' sName='form_action' sId='talk-form-action'}

		{* Экшнбар *}
		{if $smarty.local.selectable}
			{include 'components/actionbar/actionbar-item.select.tpl'
				classes  = 'js-talk-actionbar-select'
				target   = '.js-message-list-item'
				assign   = select
				items    = [
					[ 'text' => $aLang.talk.actionbar.read, 'filter' => ":not('.message-unread')" ],
					[ 'text' => $aLang.talk.actionbar.unread, 'filter' => ".message-unread" ]
				]}

			{include 'components/actionbar/actionbar.tpl' items=[
				[ 'html' => $select ],
				[ 'icon' => 'icon-ok', 'classes' => 'js-talk-form-action', 'attributes' => 'data-action="mark_as_read"', 'text' => $aLang.talk.actionbar.mark_as_read ],
				[ 'icon' => 'icon-remove', 'classes' => 'js-talk-form-action', 'attributes' => 'data-action="remove"', 'text' => $aLang.common.remove ]
			]}
		{/if}

		{* Список сообщений *}
		<table class="table table-talk message-list">
			<tbody>
				{foreach $smarty.local.talks as $talk}
					{* Создатель диалога *}
					{$author = $talk->getTalkUser()}

					{* Все участники диалога *}
					{$users = $talk->getTalkUsers()}

					{* Кол-во участников диалога *}
					{$usersCount = count($users)}

					<tr class="message-list-item {if $author->getCommentCountNew() or ! $author->getDateLast()}message-unread{/if} js-message-list-item" data-id="{$talk->getId()}">
						{* Выделение *}
						{if $smarty.local.selectable}
							<td class="cell-checkbox">
								<input type="checkbox" name="talk_select[{$talk->getId()}]" data-id="{$talk->getId()}" />
							</td>
						{/if}

						{* Избранное *}
						<td class="cell-favourite">
							{include 'components/favourite/favourite.tpl' classes='js-favourite-talk' target=$talk}
						</td>

						{* Основная информация о диалоге *}
						<td class="cell-info">
							<div class="message-list-info">
								{* Участники диалога *}
								{if $usersCount > 2}
									<a href="{router page='talk'}read/{$talk->getId()}/" class="message-list-info-avatar">
										<img src="{cfg name="path.skin.web"}/assets/images/avatars/avatar_male_64x64.png" />
									</a>

									{lang name='talk.participants' count=$usersCount plural=true}
								{else}
									{* Если участников двое, то отображаем только собеседника *}
									{foreach $users as $user}
										{$user = $user->getUser()}

										{if $user->getUserId() != $oUserCurrent->getId()}
											<a href="{$user->getUserWebPath()}" class="message-list-info-avatar">
												<img src="{$user->getProfileAvatarPath(64)}" alt="{$user->getLogin()}" />
											</a>

											<a href="{$user->getUserWebPath()}" class="word-wrap">{$user->getDisplayName()}</a>
										{/if}
									{/foreach}
								{/if}

								{* Дата *}
								<time class="message-list-info-date" datetime="{date_format date=$talk->getDate() format='c'}" title="{date_format date=$talk->getDate() format='j F Y, H:i'}">
									{date_format date=$talk->getDate() hours_back="12" minutes_back="60" now="60" day="day H:i" format="j F Y, H:i"}
								</time>
							</div>
						</td>

						{* Заголовок и текст последнего сообщения *}
						<td>
							<div class="message-list-item-extra">
								{* Заголовок *}
								<h2 class="message-list-item-title">
									<a href="{router page='talk'}read/{$talk->getId()}/">
										{$talk->getTitle()|escape}
									</a>
								</h2>

								{* Текст последнего сообщения *}
								<div class="message-list-item-text">
									{(($talk->getCommentLast()) ? $talk->getCommentLast()->getText() : $talk->getText())|strip_tags|truncate:120:"..."|escape}
								</div>

								{* Кол-во сообщений *}
								{if $talk->getCountComment()}
									<div class="message-list-item-count">
										{$talk->getCountComment()}

										{if $author->getCommentCountNew()}
											<strong>+{$author->getCommentCountNew()}</strong>
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
	{include 'components/alert/alert.tpl' text=$aLang.talk.notices.empty mods='empty'}
{/if}

{include 'components/pagination/pagination.tpl' aPaging=$aPaging}