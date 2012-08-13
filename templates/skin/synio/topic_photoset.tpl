{extends file="topic.prototype.tpl"}

{block name="topic_body_wrap" prepend}
	<script type="text/javascript">
	(function($){
		$('#photoset-main-image-{$oTopic->getId()}').load(function(){
			ls.photoset.showMainPhoto({$oTopic->getId()});
		});
	})(jQuery);
	</script>
{/block}{*/topic_body_wrap*}


{block name="topic_content_more"}
	{$aLang.topic_photoset_show_all|ls_lang:"COUNT%%`$iPhotosCount`"} &rarr;
{/block}{*/topic_content_more*}


{block name="topic_content_wrap"}
	{assign var=oMainPhoto value=$oTopic->getPhotosetMainPhoto()}
	{if $oMainPhoto}
	<div class="topic-photo-preview"
		id="photoset-main-preview-{$oTopic->getId()}"
		{if $oTopic->getId()}onclick="window.location='{$oTopic->getUrl()}#photoset'"{/if}
		>
		<div class="topic-photo-count"
			id="photoset-photo-count-{$oTopic->getId()}"
			>{$oTopic->getPhotosetCount()} {$aLang.topic_photoset_photos}</div>
		
		{if $oMainPhoto->getDescription()}
			<div class="topic-photo-desc"
				id="photoset-photo-desc-{$oTopic->getId()}"
				>{$oMainPhoto->getDescription()}</div>
		{/if}
		
		<img src="{$oMainPhoto->getWebPath(500)}" alt="image" id="photoset-main-image-{$oTopic->getId()}" />
	</div>
	{/if}
	{assign var=iPhotosCount value=$oTopic->getPhotosetCount()}
	
	{$smarty.block.parent}
	
	{if !$bTopicList}
		<script type="text/javascript" src="{cfg name='path.root.engine_lib'}/external/prettyPhoto/js/prettyPhoto.js"></script>	
		<link rel='stylesheet' type='text/css' href="{cfg name='path.root.engine_lib'}/external/prettyPhoto/css/prettyPhoto.css" />
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
		
		
		<div class="topic-photo-images">
			<h2>{$oTopic->getPhotosetCount()} {$oTopic->getPhotosetCount()|declension:$aLang.topic_photoset_count_images}</h2>
			<a name="photoset"></a>
			
			<ul id="topic-photo-images">
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
			
			{if count($aPhotos)<$oTopic->getPhotosetCount()}
				<a href="javascript:ls.photoset.getMore({$oTopic->getId()})" id="topic-photo-more" class="topic-photo-more">{$aLang.topic_photoset_show_more} &darr;</a>
			{/if}
		</div>
	{/if}
{/block}{*/topic_content_wrap*}
