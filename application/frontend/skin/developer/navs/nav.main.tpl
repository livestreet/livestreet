{include 'components/nav/nav.tpl' sName='main' sActiveItem=$sMenuHeadItemSelect sMods='main' aItems=[
	[ 'text' => $aLang.topic_title, 'url' => {router page='/'},      'name' => 'blog' ],
	[ 'text' => $aLang.blog.blogs,  'url' => {router page='blogs'},  'name' => 'blogs' ],
	[ 'text' => $aLang.people,      'url' => {router page='people'}, 'name' => 'people' ],
	[ 'text' => $aLang.stream_menu, 'url' => {router page='stream'}, 'name' => 'stream' ]
]}