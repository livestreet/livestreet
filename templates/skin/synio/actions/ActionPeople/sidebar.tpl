{hook run='people_sidebar_begin'}

{include file='blocks/block.usersStatistics.tpl'}
{insert name="block" block='tagsCountry'}
{insert name="block" block='tagsCity'}

{hook run='people_sidebar_end'}