{component 'nav' name='main' activeItem=$sMenuHeadItemSelect mods='main' items=[
	[ 'text' => $aLang.topic.topics,   'url' => {router page='/'},      'name' => 'blog' ],
	[ 'text' => $aLang.blog.blogs,     'url' => {router page='blogs'},  'name' => 'blogs' ],
	[ 'text' => $aLang.user.users,     'url' => {router page='people'}, 'name' => 'people' ],
	[ 'text' => $aLang.activity.title, 'url' => {router page='stream'}, 'name' => 'stream' ]
]}