{**
 * Блок с заметкой о пользователе
 *
 * @styles css/blocks.css
 *}

{extends 'components/block/block.tpl'}

{block 'block_options' append}
	{$mods = "{$mods} user-note nopadding"}

	{if ! $oUserCurrent or ( $oUserCurrent->getId() == $oUserProfile->getId() )}
		{$show = false}
	{/if}
{/block}

{block 'block_content_after'}
	{include 'components/note/note.tpl' sClasses='js-user-note' oObject=$oUserProfile->getUserNote() iUserId=$oUserProfile->getId()}
{/block}