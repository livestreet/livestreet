{**
 * Меню пользователя ("Добавить в друзья", "Написать письмо" и т.д.)
 *
 * @styles css/blocks.css
 *}

{extends file='blocks/block.aside.base.tpl'}

{block name='block_options'}
	{if ! $oUserCurrent or ( $oUserCurrent and $oUserCurrent->getId() == $oUserProfile->getId() )}
		{$bBlockNotShow = true}
	{/if}
{/block}

{block name='block_type'}profile-actions{/block}

{block name='block_content'}
	<script type="text/javascript">
		jQuery(function($){
			ls.lang.load({lang_load name="profile_user_unfollow,profile_user_follow"});
		});
	</script>

	<ul class="profile-actions" id="profile_actions">
		{include file='actions/ActionProfile/friend_item.tpl' oUserFriend=$oUserProfile->getUserFriend()}
		
		<li><a href="{router page='talk'}add/?talk_users={$oUserProfile->getLogin()}">{$aLang.user_write_prvmsg}</a></li>						
		<li>
			<a href="#" class="js-user-follow {if $oUserProfile->isFollow()}active{/if}" data-user-id="{$oUserProfile->getId()}">
				{if $oUserProfile->isFollow()}{$aLang.profile_user_unfollow}{else}{$aLang.profile_user_follow}{/if}
			</a>
		</li>
		<li>
			<a href="#" data-type="modal-toggle" data-modal-url="{router page='profile/ajax-modal-complaint'}" data-param-user_id="{$oUserProfile->getId()}">{$aLang.user_complaint_title}</a>
		</li>
	</ul>
{/block}