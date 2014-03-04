<ul class="alphanumeric js-search-alphabet" data-type="users">
	<li class="alphanumeric-item active js-search-alphabet-item" data-letter=""><a href="#">{$aLang.user_search_filter_all}</a></li>

	{foreach $aAlphaLetters as $sLetter}
		<li class="alphanumeric-item js-search-alphabet-item" data-letter="{$sLetter}"><a href="#">{$sLetter}</a></li>
	{/foreach}
</ul>