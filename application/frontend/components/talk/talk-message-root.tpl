{**
 * Первое сообщение в диалоге
 *}

{$component = 'ls-talk-message-root'}

{* Генерируем копии локальных переменных, *}
{* чтобы их можно было изменять в дочерних шаблонах *}
{foreach [ 'talk', 'mods', 'classes', 'attributes' ] as $param}
    {assign var="$param" value=$smarty.local.$param}
{/foreach}


<div class="{$component} {cmods name=$component mods=$mods} {$classes}" {cattr list=$attributes}>
    {* Заголовок *}
    <h2 class="{$component}-title">
        {$talk->getTitle()}
    </h2>

    {* Информация *}
    <ul class="{$component}-info">
        {* Автор *}
        <li class="{$component}-info-item {$component}-info-item--author">
            {component 'user' template='avatar' user=$talk->getUser() size='xxsmall' mods='inline'}
        </li>

        <li class="{$component}-info-item {$component}-info-item--date">
            <time datetime="{date_format date=$talk->getDate() format='c'}" title="{date_format date=$talk->getDate() format='j F Y, H:i'}">
                {date_format date=$talk->getDate() hours_back="12" minutes_back="60" now="60" day="day H:i" format="j F Y, H:i"}
            </time>
        </li>
    </ul>

    {* Содержимое *}
    <div class="{$component}-text ls-text">
        {$talk->getText()}
    </div>

    {* Действия *}
    {component 'actionbar' classes="{$component}-actionbar" items=[
        [ 'buttons' => [
            [ 'text' => {component 'favourite' classes="js-favourite-talk" target=$talk}, 'mods' => 'icon', 'classes' => 'js-talk-message-root-favourite' ]
        ]],
        [ 'buttons' => [
            [ 'icon' => 'trash', 'url' => "{$talk->getUrlDelete()}?security_ls_key={$LIVESTREET_SECURITY_KEY}", 'text' => {lang 'common.remove'}, 'show' => $oUserCurrent->getId() == $talk->getUser()->getId() || $oUserCurrent->isAdministrator() ]
        ]]
    ]}
</div>