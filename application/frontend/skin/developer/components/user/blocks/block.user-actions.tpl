{**
 * Меню пользователя ("Добавить в друзья", "Написать письмо" и т.д.)
 *
 * @styles css/blocks.css
 *}

{extends 'components/block/block.tpl'}

{block 'block_options' append}
	{$mods = "{$mods} nopadding transparent user-actions"}

	{if ! $oUserCurrent or ( $oUserCurrent && $oUserCurrent->getId() == $oUserProfile->getId() )}
		{$show = false}
	{/if}
{/block}

{block 'block_content'}
	{component 'user' template='actions' user=$oUserProfile}
{/block}