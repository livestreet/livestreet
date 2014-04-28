{**
 * Блок с заметкой о пользователе
 *
 * @styles css/blocks.css
 *}

{extends 'blocks/block.aside.base.tpl'}

{block 'block_options'}
	{if ! $oUserCurrent or ( $oUserCurrent->getId() == $oUserProfile->getId() )}
		{$bBlockNotShow = true}
	{/if}
{/block}

{block 'block_type'}profile-note{/block}
{block 'block_class'}block-nopadding{/block}

{block 'block_content_after'}
	{include 'components/user_note/user_note.tpl' sClasses='js-user-note' oObject=$oUserProfile->getUserNote() iUserId=$oUserProfile->getId()}
{/block}