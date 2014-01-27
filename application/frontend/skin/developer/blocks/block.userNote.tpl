{**
 * Блок с заметкой о пользователе
 *
 * @styles css/blocks.css
 *}

{extends 'blocks/block.aside.base.tpl'}

{block 'block_options'}
	{if ! $oUserCurrent or ( $oUserCurrent and $oUserCurrent->getId() == $oUserProfile->getId() )}
		{$bBlockNotShow = true}
	{/if}
{/block}

{block 'block_type'}profile-note{/block}
{block 'block_class'}block-nopadding{/block}

{block 'block_content_after'}
	{include 'user_note.tpl' oUserNote=$oUserProfile->getUserNote() iUserNoteId=$oUserProfile->getId()}
{/block}