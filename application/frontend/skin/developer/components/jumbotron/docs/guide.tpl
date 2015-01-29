{**
 * Jumbotron
 *}

{test_heading text='Использование'}

{capture 'test_example_content'}
    {component 'jumbotron'
        title    = 'Lorem ipsum'
        subtitle = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Reprehenderit omnis, error incidunt alias a animi'
        titleUrl = '/'}
{/capture}

{capture 'test_example_code'}
{ldelim}component 'jumbotron'
    title    = 'Lorem ipsum'
    subtitle = 'Lorem ipsum dolor sit amet ...'
    titleUrl = '/'{rdelim}
{/capture}

{test_example content=$smarty.capture.test_example_content code=$smarty.capture.test_example_code}