<section class="block block-type-profile">
	{if $oUserProfile->getProfileFoto()}
		<a href="{router page='profile'}{$oUserProfile->getLogin()}/">
			<img src="{$oUserProfile->getProfileFoto()}" alt="photo" class="profile-photo" />
		</a>
	{else}
		<a href="{router page='profile'}{$oUserProfile->getLogin()}/">
			<img src="{cfg name='path.static.skin'}/images/no_photo.png" alt="photo" class="profile-photo" />
		</a>
	{/if}

	<ul class="nav nav-profile">
		<li class="active"><a href="#">Стена</a></li>
		<li><a href="#">Информация</a></li>
		<li><a href="#">Публикации</a></li>
		<li><a href="#">Избранное</a></li>
	</ul>
</section>
