{extends file='topics/topic_base.tpl'}


{* Preview Image *}
{block name='header_after'}
	{assign var=oMainPhoto value=$oTopic->getPhotosetMainPhoto()}

	{if $oMainPhoto}
		<div class="topic-preview-image">
			<div class="topic-preview-image-inner js-topic-preview-loader loading" onclick="window.location='{$oTopic->getUrl()}'">
				<div class="topic-preview-image-count" id="photoset-photo-count-{$oTopic->getId()}"><i class="icon-camera icon-white"></i> {$oTopic->getPhotosetCount()}</div>
				
				{if $oMainPhoto->getDescription()}
					<div class="topic-preview-image-desc" id="photoset-photo-desc-{$oTopic->getId()}">{$oMainPhoto->getDescription()}</div>
				{/if}

				<img class="js-topic-preview-image" src="{$oMainPhoto->getWebPath(1000)}" alt="Topic preview" />
			</div>
		</div>
	{/if}
{/block}


{* Content *}
{block name='content'}
	{if $bTopicList}
		{$oTopic->getTextShort()}

		{if $oTopic->getTextShort() != $oTopic->getText()}
			{assign var=iPhotosCount value=$oTopic->getPhotosetCount()}

			<br />
			<a href="{$oTopic->getUrl()}#cut" title="{$aLang.topic_read_more}">
				{if $oTopic->getCutText()}
					{$oTopic->getCutText()}
				{else}
					{$aLang.topic_photoset_show_all|ls_lang:"COUNT%%`$iPhotosCount`"} &rarr;
				{/if}                           
			</a>
		{/if}
	{else}
		{$oTopic->getText()}
	{/if}
{/block}


{* Photoset *}
{block name='content_after'}
	{if !$bTopicList}
		<script type="text/javascript">
			jQuery(document).ready(function($) {	
				$('.photoset-image').prettyPhoto({
					social_tools:'',
					show_title: false,
					slideshow:false,
					deeplinking: false
				});
			});
		</script>
		
		
		<div class="photoset photoset-type-default">
			<h2 class="photoset-title">{$oTopic->getPhotosetCount()} {$oTopic->getPhotosetCount()|declension:$aLang.topic_photoset_count_images}</h2>
			
			<ul class="photoset-images" id="topic-photo-images">
				{assign var=aPhotos value=$oTopic->getPhotosetPhotos(0, $oConfig->get('module.topic.photoset.per_page'))}
				{if count($aPhotos)}                                
					{foreach from=$aPhotos item=oPhoto}
						<li><a class="photoset-image" href="{$oPhoto->getWebPath(1000)}" rel="[photoset]"  title="{$oPhoto->getDescription()}"><img src="{$oPhoto->getWebPath('50crop')}" alt="{$oPhoto->getDescription()}" /></a></li>                                    
						{assign var=iLastPhotoId value=$oPhoto->getId()}
					{/foreach}
				{/if}
				<script type="text/javascript">
					ls.photoset.idLast='{$iLastPhotoId}';
				</script>
			</ul>
			
			{if count($aPhotos) < $oTopic->getPhotosetCount()}
				<a href="javascript:ls.photoset.getMore({$oTopic->getId()})" id="topic-photo-more" class="photoset-more">{$aLang.topic_photoset_show_more} &darr;</a>
			{/if}
		</div>
	{/if}
{/block}