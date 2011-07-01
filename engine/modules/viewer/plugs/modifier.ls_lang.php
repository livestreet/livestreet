<?php

function smarty_modifier_ls_lang($sString)
{
    $aArgs = func_get_args();
    if (count($aArgs) ==1 || !$sString) return $sString;
    array_shift($aArgs);
    $aFrom = array();
    $aTo = array();
    foreach ($aArgs as $sPair) {
        if (!strpos($sPair,'%%')) continue;
        list ($sFrom, $sTo) = explode('%%', $sPair);
        $aFrom[] = '%%'.$sFrom.'%%';
        $aTo[] = $sTo;
    }
    return str_replace($aFrom, $aTo, $sString);
} 