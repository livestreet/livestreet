{hook run='profile_sidebar_begin' oUserProfile=$oUserProfile}

{include file='modals/modal.profile_photo_upload.tpl'}
{include file='blocks/block.profilePhoto.tpl'}

{hook run='profile_sidebar_menu_before' oUserProfile=$oUserProfile}

{include file='blocks/block.profileNav.tpl'}

{hook run='profile_sidebar_end' oUserProfile=$oUserProfile}

{if $oUserCurrent && $oUserCurrent->getId() != $oUserProfile->getId()}
	{include file='blocks/block.profileNote.tpl'}
{/if}

{if $oUserCurrent && $oUserCurrent->getId() != $oUserProfile->getId()}
	{include file='blocks/block.profileActions.tpl'}
{/if}