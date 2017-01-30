{**
 * Дерево комментариев
 *
 * @param array    $comments         Комментарии
 * @param integer  $maxLevel
 *
 * @param array    $commentParams
 * @param boolean  $useVote         Показывать или нет голосование
 * @param boolean  $showReply       Показывать или нет кнопку Ответить
 * @param boolean  $useScroll
 * @param integer  $authorId
 * @param string   $dateReadLast
 * @param boolean  $forbidAdd
 *}

{component_define_params params=[ 'hookPrefixComment', 'authorId', 'authorText', 'commentParams', 'comments', 'dateReadLast', 'forbidAdd', 'maxLevel', 'showReply' ]}

{* Текущая вложенность *}
{$currentLevel = -1}

{* Построение дерева комментариев *}
{foreach $comments as $comment}
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
    <div class="ls-comment-wrapper js-comment-wrapper" data-id="{$comment->getId()}">

    {* Комментарий *}
    {block 'comment_tree_comment'}
        {component 'comment'
            hookPrefix   = $hookPrefixComment
            comment      = $comment
            dateReadLast = $dateReadLast
            authorId     = $authorId
            authorText   = $authorText
            showReply    = ! $forbidAdd || $showReply
            params       = $commentParams}
    {/block}

    {* Закрываем блоки-обертки после последнего комментария *}
    {if $comment@last}
        {section closewrappers2 loop=$currentLevel + 1}
            </div>
        {/section}
    {/if}
{/foreach}