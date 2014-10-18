{**
 * Список пользователей на которых подписан текущий пользователь
 *
 * @param array $types
 * @param array $typesActive
 *}

{if $oUserCurrent}
    <div class="activity-settings js-activity-settings">
        <small class="note mb-15">
            {$aLang.activity.settings.note}
        </small>

        {foreach $smarty.local.types as $type => $data}
            {if ! (Config::Get('module.stream.disable_vote_events') && substr($type, 0, 4) == 'vote')}
                {include 'components/field/field.checkbox.tpl'
                    inputClasses    = 'js-activity-settings-type-checkbox'
                    inputAttributes = "data-type=\"{$type}\""
                    checked         = in_array( $type, $smarty.local.typesActive )
                    label           = $aLang.activity.settings.options[ $type ]}
            {/if}
        {/foreach}
    </div>
{/if}