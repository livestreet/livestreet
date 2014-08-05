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
	{include 'components/user/actions.tpl' user=$oUserProfile}
{/block}