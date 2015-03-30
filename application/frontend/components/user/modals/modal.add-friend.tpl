{**
 * Добавление в друзья
 *}

{capture 'modal_content'}
    {* TODO: Form validation (front-end / back-end) *}
    <form id="add_friend_form" class="js-user-friend-form">
        {component 'field' template='textarea'
            name     = 'add_friend_text'
            rules    = [ 'required' => true, 'length' => '[2,200]' ]
            rows     = 3
            noMargin = true
            label    = {lang name='user.friends.form.fields.text.label'}}
    </form>
{/capture}

{component 'modal'
    title         = {lang 'user.friends.form.title'}
    content       = $smarty.capture.modal_content
    classes       = 'js-modal-default'
    mods          = 'user-add-friend'
    id            = 'modal-add-friend'
    primaryButton  = [
        'text'    => {lang 'user.friends.form.fields.submit.text'},
        'form'    => 'add_friend_form'
    ]}