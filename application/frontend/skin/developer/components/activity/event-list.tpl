{**
 * События
 *
 * @param array  $events
 * @param string $dateLast Дата предыдущего сообщения
 *}

{* Дата последнего события *}
{$dateLast = ( $smarty.local.dateLast ) ? {date_format date=$smarty.local.dateLast format="Y-m-d" notz=1} : false}
{$dateNow = {date_format date=$smarty.now format="Y-m-d" notz=1}}

{foreach $smarty.local.events as $event}
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
    {include './event.tpl' event=$event}
{/foreach}