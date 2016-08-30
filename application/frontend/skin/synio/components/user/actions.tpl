{**
 * Список действий
 *
 * @param object $user
 *}

{component_define_params params=[ 'user' ]}

{component 'nav'
    hook    = 'user_actions'
    hookParams = [ user => $user ]
    mods    = 'stacked'
    classes = 'profile-actions'
    items   = [
        [ 'html' => {component 'user' template='friend-item' friendship=$user->getUserFriend() userTarget=$oUserProfile classes='js-user-friend'} ],
        [ 'url' => "{router page='talk'}add/?talk_recepient_id={$user->getId()}", 'text' => {lang 'user.actions.send_message'} ],
        [
            'url' => "#",
            'classes' => "js-user-follow {if $user->isFollow()}active{/if}",
            'attributes' => [ 'data-id' => $user->getId(), 'data-login' => $user->getLogin() ],
            'text' => {lang name="user.actions.{( $user->isFollow() ) ? 'unfollow' : 'follow'}"}
        ],
        [ 'url' => "#", 'text' => {lang 'user.actions.report'}, classes => 'js-user-report', 'attributes' => [ 'data-param-target_id' => $user->getId() ] ]
    ]}