{**
 * Дерево комментариев
 *
 * @component comment
 * @styles    css/comments.css
 * @scripts   js/comments.js
 *
 * @param array    $aComments         Комментарии
 * @param string   $sClasses          Дополнительные классы
 * @param string   $sAttributes       Атрибуты
 * @param string   $sMods
 * @param boolean  $bShowVote         (true) Показывать или нет голосование
 * @param boolean  $bShowReply        (true) Показывать или нет кнопку Ответить
 * @param integer  $iAuthorId
 * @param string   $sDateReadLast
 *}

{* Текущая вложенность *}
{$iCurrentLevel = -1}

{* Максимальная вложенность *}
{$iMaxLevel = $smarty.local.iMaxLevel|default:Config::Get('module.comment.max_tree')}

{* Добавляем возможность переопределить стандартный шаблон комментария *}
{$sTemplate = $smarty.local.template|default:'./comment.tpl'}

{* Построение дерева комментариев *}
{foreach $smarty.local.aComments as $oComment}
	{* Ограничиваем вложенность комментария максимальным значением *}
	{$iCommentLevel = ( $oComment->getLevel() > $iMaxLevel ) ? $iMaxLevel : $oComment->getLevel()}

	{* Закрываем блоки-обертки *}
	{if $iCurrentLevel > $iCommentLevel}
		{section closewrappers1 loop=$iCurrentLevel - $iCommentLevel + 1}</div>{/section}
	{elseif $iCurrentLevel == $iCommentLevel && ! $oComment@first}
		</div>
	{/if}

	{* Устанавливаем текущий уровень вложенности *}
	{$iCurrentLevel = $iCommentLevel}

	{* Вспомогательный блок-обертка *}
	<div class="comment-wrapper js-comment-wrapper" data-id="{$oComment->getId()}">

	{* Комментарий *}
	{include "$sTemplate"
		oComment       = $oComment
		bShowVote      = $smarty.local.bShowVote
		bShowReply     = ! $smarty.local.bForbidAdd
		bShowFavourite = $smarty.local.bShowFavourite
		sDateReadLast  = $sDateReadLast
		bIsHidden      = $oComment->getDelete()
		bShowScroll    = $smarty.local.bShowScroll|default:true
		bShowEdit      = true}

	{* Закрываем блоки-обертки после последнего комментария *}
	{if $oComment@last}
		{section closewrappers2 loop=$iCurrentLevel + 1}</div>{/section}
	{/if}
{foreachelse}
	{include 'components/alert/alert.tpl' sMods='empty' mAlerts=$aLang.common.empty}
{/foreach}