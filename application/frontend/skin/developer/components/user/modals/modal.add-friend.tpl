{**
 * Добавление в друзья
 *}

{extends 'components/modal/modal.tpl'}

{block 'modal_options' append}
    {$id = "modal-add-friend"}
    {$classes = "$classes js-modal-default"}
    {$title = {lang 'user.friends.form.title'}}
{/block}

{block 'modal_content'}
	{* TODO: Form validation (front-end / back-end) *}
	<form id="add_friend_form" class="js-user-friend-form">
		{component 'field' template='textarea'
				 name     = 'add_friend_text'
				 rules    = [ 'required' => true, 'rangelength' => '[2,200]' ]
				 rows     = 3
				 noMargin = true
				 label    = {lang name='user.friends.form.fields.text.label'}}
	</form>
{/block}

{block 'modal_footer_begin'}
	 {component 'button' mods='primary' form='add_friend_form' text={lang name='user.friends.form.fields.submit.text'}}
{/block}