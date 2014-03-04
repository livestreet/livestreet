{**
 * Уведомления
 *
 * @param string  $sLoadText
 * @param string  $sLoadTarget
 * @param integer $iLoadCount
 * @param integer $iLoadTargetId
 * @param integer $iLoadLastId
 * @param bool    $bLoadAppend
 *
 * @styles <framework>/css/common.css
 *}

<div class="more {$sLoadClasses}"
	 data-more-append="{$bLoadAppend|default:'true'}"
	 {if $iLoadLastId}data-param-i-last-id="{$iLoadLastId}"{/if}
	 data-param-i-target-id="{$iLoadTargetId|default:0}"
	 {$sLoadAttributes}>

	{$sLoadText|default:'Подгрузить еще'}

	{if isset($iLoadCount)}
		(<span class="js-more-count">{$iLoadCount}</span>)
	{/if}
</div>