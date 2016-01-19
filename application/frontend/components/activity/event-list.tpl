{**
 * События
 *
 * @param array  $events
 * @param string $dateLast Дата предыдущего сообщения
 *}

{component_define_params params=[ 'dateLast', 'events' ]}

{* Дата последнего события *}
{$dateLast = ( $dateLast ) ? {date_format date=$dateLast format="Y-m-d" notz=1} : false}
{$dateNow = {date_format date=$smarty.now format="Y-m-d" notz=1}}

{foreach $events as $event}
    {$dateAdded = {date_format date=$event->getDateAdded() format="Y-m-d" notz=1}}

    {* Дата группы событий *}
    {if $dateAdded != $dateLast}
        {$dateLast = $dateAdded}

        <li class="activity-date">
            {if $dateNow == $dateLast}
                {$aLang.date.today}
            {else}
                {date_format date=$event->getDateAdded() format="j F Y"}
            {/if}
        </li>
    {/if}

    {* Событие *}
    {component 'activity' template='event' event=$event}
{/foreach}