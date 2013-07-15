jQuery(document).ready(function($){
	// Хук начала инициализации javascript-составляющих шаблона
	ls.hook.run('ls_template_init_start',[],window);

	/**
	 * Popovers
	 */
	$(document).popover({ selector: '.js-popover-default' });


	/**
	 * Modals
	 */
	$('.js-modal-default').modal();


	/**
	 * Datepicker
	 */
	$('.date-picker').datepicker();


	/**
	 * Dropdowns
	 */
	$('.js-dropdown-default').dropdown();


	/**
	 * Tooltips
	 */
	$(document).tooltip({
		selector: '.js-tooltip, .js-tooltip-vote-topic'
	});

	$('.js-title-talk').tooltip({
		alignX: 'left',
		alignY: 'center'
	});

	$('.js-tip-help').tooltip({
		alignX: 'right',
		alignY: 'center'
	});

	if (ls.registry.get('block_stream_show_tip')) {
		$(document).tooltip({
			selector: '.js-title-comment, .js-title-topic',
			alignX: 'left',
			alignY: 'center',
			delay: 1500
		});
	}


	/**
	 * Autocomplete
	 */
	ls.autocomplete.add($(".autocomplete-tags-sep"), aRouter['ajax']+'autocompleter/tag/', true);
	ls.autocomplete.add($(".autocomplete-tags"), aRouter['ajax']+'autocompleter/tag/', false);
	ls.autocomplete.add($(".autocomplete-users-sep"), aRouter['ajax']+'autocompleter/user/', true);
	ls.autocomplete.add($(".autocomplete-users"), aRouter['ajax']+'autocompleter/user/', false);


	/**
	 * Scroll
	 */
	$(window)._scrollable();


	/**
	 * Toolbar
	 */
	$('#toolbar').toolbar({
		alignTo: '#wrapper',
		align: 'right',
		offsetX: 10,
		offsetY: 0
	});

	ls.toolbar.topic.init(); // Тул-бар топиков
	ls.toolbar.up.init();    // Кнопка "UP"


	/**
	 * Code highlight
	 */
	prettyPrint();


	/**
	 * Blocks
	 */
	ls.blocks.init();


	/**
	 * Activity
	 */
	ls.stream.init();


	/**
	 * Userfeed
	 */
	ls.userfeed.init();


	/**
	 * Comments
	 */
	ls.comments.init({
		folding: false
	});


	/**
	 * User
	 */
	ls.user.init();


	/**
	 * Captcha
	 */
	ls.captcha.init();


	/**
	 * Talk
	 */
	ls.talk.init();


	/**
	 * Poll
	 */
	ls.poll.init();


	/**
	 * User Note
	 */
	ls.usernote.init();


	/**
	 * Editor
	 */
	ls.editor.init();


	/**
	 * Topic
	 */
	ls.topic.init();


	/**
	 * Vote
	 */
	ls.vote.init();


	/**
	 * Pagination
	 */
	ls.pagination.init();


	/**
	 * Photoset
	 */
	$('.js-photoset-type-default-image').prettyPhoto({
		social_tools: '',
		show_title:   false,
		slideshow:    false,
		deeplinking:  false
	});
	

	// Хук конца инициализации javascript-составляющих шаблона
	ls.hook.run('ls_template_init_end',[],window);
});