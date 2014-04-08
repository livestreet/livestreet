{**
 * Голосование
 *
 * @param object  $oObject     Объект сущности
 * @param string  $sClasses    Дополнительные классы
 * @param string  $sAttributes Атрибуты
 * @param boolean $bShowRating Показывать рейтинг или нет
 * @param boolean $bIsLocked   Блокировка голосования
 *
 * @styles assets/css/components/vote.css
 * @scripts <common>/js/vote.js
 *
 * TODO: Добавить смарти блоки
 *}

{* Название компонента *}
{$_sComponentName = 'vote'}

{* Установка дефолтных значений *}
{$_bShowRating = $smarty.local.bShowRating|default:true}
{$_oObject = $smarty.local.oObject}
{$_sMods = $smarty.local.sMods}

{* Рейтинг *}
{$_iRating = $_oObject->getRating()}

{* Получаем модификаторы *}
{if $_bShowRating}
	{if $_iRating > 0}
		{$_sMods = "$_sMods count-positive"}
	{elseif $_iRating < 0}
		{$_sMods = "$_sMods count-negative"}
	{else}
		{$_sMods = "$_sMods count-zero"}
	{/if}
{/if}

{if $oVote = $_oObject->getVote()}
	{$_sMods = "$_sMods voted"}

	{if $oVote->getDirection() > 0}
		{$_sMods = "$_sMods voted-up"}
	{elseif $oVote->getDirection() < 0}
		{$_sMods = "$_sMods voted-down"}
	{else}
		{$_sMods = "$_sMods voted-zero"}
	{/if}
{else}
	{$_sMods = "$_sMods not-voted"}
{/if}

{if ! $oUserCurrent || $smarty.local.bIsLocked}
	{$_sMods = "$_sMods locked"}
{/if}

{if ! $_bShowRating}
	{$_sMods = "$_sMods rating-hidden"}
{/if}


<div class="{$_sComponentName} {mod name=$_sComponentName mods=$_sMods} {$smarty.local.sClasses}" data-param-i-target-id="{$_oObject->getId()}" {$smarty.local.sAttributes}>
	{* Заголовок *}
	{if $bShowLabel}
		<h4 class="{$_sComponentName}-heading">{$aLang.$_sComponentName.rating}</h4>
	{/if}

	{* Основной блок *}
	<div class="{$_sComponentName}-body">
		{* Рейтинг *}
		<div class="{$_sComponentName}-rating js-{$_sComponentName}-rating">
			{if $_bShowRating}
				{$_iRating}
			{else}
				?
			{/if}
		</div>

		{* Воздержаться *}
		{if $smarty.local.bUseAbstain}
			<div class="{$_sComponentName}-item {$_sComponentName}-item-abstain js-{$_sComponentName}-item" {if ! $oVote}title="{$aLang.$_sComponentName.abstain}"{/if} data-vote-value="0"><i></i></div>
		{/if}

		{* Нравится *}
		<div class="{$_sComponentName}-item {$_sComponentName}-item-up js-{$_sComponentName}-item" {if ! $oVote}title="{$aLang.$_sComponentName.up}"{/if} data-vote-value="1"><i></i></div>

		{* Не нравится *}
		<div class="{$_sComponentName}-item {$_sComponentName}-item-down js-{$_sComponentName}-item" {if ! $oVote}title="{$aLang.$_sComponentName.down}"{/if} data-vote-value="-1"><i></i></div>
	</div>
</div>