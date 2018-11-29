{**
 * Настройки активности
 *
 * @param array $types
 * @param array $typesActive
 *}

{component_define_params params=[ 'types', 'typesActive' ]}

{if $oUserCurrent}
    <div class="activity-settings js-activity-settings">
        <p class="text-help">
            {$aLang.activity.settings.note}
        </p>

        <div class="ls-field-checkbox-group">
            {foreach $types as $type => $data}
                    {component 'field' template='checkbox'
                        inputClasses    = 'js-activity-settings-type-checkbox'
                        inputAttributes = [ 'data-type' => $type ]
                        checked         = in_array( $type, $typesActive )
                        label           = $aLang.activity.settings.options[ $type ]}
            {/foreach}
        </div>
    </div>
{/if}