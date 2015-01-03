{**
 * Навгиация создания топика
 *}

{$items = []}

{* Формируем список пунктов *}
{$topicTypes = $LS->Topic_GetTopicTypes()}

{foreach $topicTypes as $type}
    {$items[] = [ 'name' => $type->getCode(), 'url' => $type->getUrlForAdd(), 'text' => $type->getName() ]}
{/foreach}

{* Пункт "Черновики" *}
{$items[] = [
    'name'  => 'drafts',
    'url'   => "{router page='content'}drafts/",
    'text'  => $aLang.topic.drafts,
    'count' => $iUserCurrentCountTopicDraft
]}

{component 'nav'
    name       = 'create'
    activeItem = $sMenuSubItemSelect
    mods       = 'pills'
    items      = $items}