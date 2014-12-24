{foreach $imagePreviewItems as $oTarget}
    {$aPreview = $oTarget->getPreviewImageItemsWebPath()}

    {foreach $aPreview as $sPreviewFile}
        <img src="{$sPreviewFile}" alt="">
    {/foreach}
{/foreach}