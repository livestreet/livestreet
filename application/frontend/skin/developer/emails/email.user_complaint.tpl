{**
 * Жалоба
 *}

{extends 'Component@email.email'}

{block 'content'}
	{lang name='emails.user_complaint.text' params=[
		'user_url'         => $oUserFrom->getUserWebPath(),
		'user_name'        => $oUserFrom->getDisplayName(),
		'user_target_url'  => $oUserTarget->getUserWebPath(),
		'user_target_name' => $oUserTarget->getDisplayName(),
		'complaint_title'  => $oComplaint->getTypeTitle(),
		'complaint_text'   => "{if $oComplaint->getText()}{lang name='emails.user_changemail.more'}:<br>{$oComplaint->getText()}{/if}"
	]}
{/block}