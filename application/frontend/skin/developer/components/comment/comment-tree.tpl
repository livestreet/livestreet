{**
 * Дерево комментариев
 *
 * @param array    $comments         Комментарии
 * @param integer  $maxLevel
 *
 * @param boolean  $useVote         Показывать или нет голосование
 * @param boolean  $showReply       Показывать или нет кнопку Ответить
 * @param boolean  $useScroll
 * @param integer  $authorId
 * @param string   $dateReadLast
 * @param boolean  $forbidAdd
 * @param string   $template
 *}

{* Текущая вложенность *}
{$currentLevel = -1}

{* Максимальная вложенность *}
{$maxLevel = $smarty.local.maxLevel|default:Config::Get('module.comment.max_tree')}

{* Добавляем возможность переопределить стандартный шаблон комментария *}
{$template = $smarty.local.template|default:'./comment.tpl'}

{* Построение дерева комментариев *}
{foreach $smarty.local.comments as $comment}
    {* Ограничиваем вложенность комментария максимальным значением *}
    {$commentLevel = ( $comment->getLevel() > $maxLevel ) ? $maxLevel : $comment->getLevel()}

    {* Закрываем блоки-обертки *}
    {if $currentLevel > $commentLevel}
        {section closewrappers1 loop=$currentLevel - $commentLevel + 1}</div>{/section}
    {elseif $currentLevel == $commentLevel && ! $comment@first}
        </div>
    {/if}

    {* Устанавливаем текущий уровень вложенности *}
    {$currentLevel = $commentLevel}

    {* Вспомогательный блок-обертка *}
    <div class="comment-wrapper js-comment-wrapper" data-id="{$comment->getId()}">

    {* Комментарий *}
    {include "$template"
        comment      = $comment
        dateReadLast = $smarty.local.dateReadLast
        useScroll    = $smarty.local.useScroll|default:true
        useVote      = $smarty.local.useVote
        authorId     = $smarty.local.authorId
        authorText   = $smarty.local.authorText
        showReply    = ! $smarty.local.forbidAdd || $smarty.local.showReply
        useFavourite = $smarty.local.useFavourite
        useEdit      = true}

    {* Закрываем блоки-обертки после последнего комментария *}
    {if $comment@last}
        {section closewrappers2 loop=$currentLevel + 1}
            </div>
        {/section}
    {/if}
{foreachelse}
    {component 'alert' mods='empty' classes='js-comments-empty' text=$aLang.common.empty}
{/foreach}