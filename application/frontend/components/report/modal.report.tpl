{**
 * Жалоба на пользователя
 *
 * @param array $types
 *}

{capture 'modal_content'}
    <form action="" method="post" id="form-complaint-user">
        {component 'field' template='hidden' name='target_id' value=$_aRequest.target_id}

        {component 'field' template='select'
            name    = 'type'
            label   = {lang 'report.form.fields.type.label'}
            classes = 'width-full'
            items   = $smarty.local.types}

        {component 'field' template='textarea'
            name    = 'text'
            rows    = 5
            label   = {lang 'report.form.fields.text.label'}
            classes = 'width-full'}

        {* Каптча *}
        {if Config::Get('module.user.complaint_captcha')}
            {component 'field' template='captcha'
                type        = Config::Get('sys.captcha.type')
                captchaName ='complaint_user'
                name        ='captcha'}
        {/if}
    </form>
{/capture}

{component 'modal'
    title         = {lang 'report.form.title'}
    content       = $smarty.capture.modal_content
    classes       = 'js-modal-default'
    mods          = 'report'
    id            = 'modal-complaint-user'
    primaryButton  = [
        'text'    => {lang 'report.form.submit'},
        'form'    => 'form-complaint-user'
    ]}