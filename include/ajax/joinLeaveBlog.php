<?php
/*-------------------------------------------------------
*
*   LiveStreet Engine Social Networking
*   Copyright © 2008 Mzhelskiy Maxim
*
*--------------------------------------------------------
*
*   Official site: www.livestreet.ru
*   Contact e-mail: rus.engine@gmail.com
*
*   GNU General Public License, version 2:
*   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*
---------------------------------------------------------
*/

/**
 * Подлючение/отключение от блога
 */

set_include_path(get_include_path().PATH_SEPARATOR.dirname(dirname(dirname(__FILE__))));
$sDirRoot=dirname(dirname(dirname(__FILE__)));
require_once($sDirRoot."/config/config.ajax.php");

$idBlog=getRequest('idBlog',null,'post');
$bStateError=true;
$sMsg='';
$sMsgTitle='';
$bState='';
$iCountUser=0;
if ($oEngine->User_IsAuthorization()) {
		if ($oBlog=$oEngine->Blog_GetBlogById($idBlog)) {
			/**
			 * Как только заработают другие виды блогов(кроме open) тут нужно внести коррективы, чтоб можно было покинуть блог по приглашениям
			 */
			$oUserCurrent=$oEngine->User_GetUserCurrent();
			if (in_array($oBlog->getType(),array('open','close'))) {
				$oBlogUser=$oEngine->Blog_GetBlogUserByBlogIdAndUserId($oBlog->getId(),$oUserCurrent->getId());				
				if (!$oBlogUser || ($oBlogUser->getUserRole()<ModuleBlog::BLOG_USER_ROLE_GUEST && $oBlog->getType()=='close')) {
					if ($oBlog->getOwnerId()!=$oUserCurrent->getId()) {
						/**
					 	* Присоединяем юзера к блогу
					 	*/
						if($oBlogUser) {
							$oBlogUser->setUserRole(ModuleBlog::BLOG_USER_ROLE_USER);
							$bResult = $oEngine->Blog_UpdateRelationBlogUser($oBlogUser);
						} elseif($oBlog->getType()=='open') {
							$oBlogUserNew=Engine::GetEntity('Blog_BlogUser');
							$oBlogUserNew->setBlogId($oBlog->getId());
							$oBlogUserNew->setUserId($oUserCurrent->getId());
							$oBlogUserNew->setUserRole(ModuleBlog::BLOG_USER_ROLE_USER);
							$bResult = $oEngine->Blog_AddRelationBlogUser($oBlogUserNew);
						} 
						if ($bResult) {
							$bStateError=false;
							$sMsgTitle=$oEngine->Lang_Get('attention');
							$sMsg=$oEngine->Lang_Get('blog_join_ok');
							$bState=true;
							/**
							 * Увеличиваем число читателей блога
							 */
							$oBlog->setCountUser($oBlog->getCountUser()+1);
							$oEngine->Blog_UpdateBlog($oBlog);
							$iCountUser=$oBlog->getCountUser();
						} else {
							$sMsgTitle=$oEngine->Lang_Get('error');
							$sMsg=($oBlog->getType()=='close') 
								? $oEngine->Lang_Get('blog_join_error_invite')
								: $oEngine->Lang_Get('system_error');
						}
					} else {
						$sMsgTitle=$oEngine->Lang_Get('attention');
						$sMsg=$oEngine->Lang_Get('blog_join_error_self');
					}
				}				
				if ($oBlogUser && $oBlogUser->getUserRole()>ModuleBlog::BLOG_USER_ROLE_GUEST) {
					/**
					 * Покидаем блог
					 */					
					if ($oEngine->Blog_DeleteRelationBlogUser($oBlogUser)) {
						$bStateError=false;
						$sMsgTitle=$oEngine->Lang_Get('attention');
						$sMsg=$oEngine->Lang_Get('blog_leave_ok');
						$bState=false;
						/**
						 * Уменьшаем число читателей блога
						 */
						$oBlog->setCountUser($oBlog->getCountUser()-1);
						$oEngine->Blog_UpdateBlog($oBlog);
						$iCountUser=$oBlog->getCountUser();
					} else {
						$sMsgTitle=$oEngine->Lang_Get('error');
						$sMsg=$oEngine->Lang_Get('system_error');
					}
				}				
			} else {
				$sMsgTitle=$oEngine->Lang_Get('error');
				$sMsg=$oEngine->Lang_Get('blog_join_error_invite');
			}
		} else {
			$sMsgTitle=$oEngine->Lang_Get('error');
			$sMsg=$oEngine->Lang_Get('system_error');
		}
	
} else {
	$sMsgTitle=$oEngine->Lang_Get('error');
	$sMsg=$oEngine->Lang_Get('need_authorization');
}


$GLOBALS['_RESULT'] = array(
"bStateError"     => $bStateError,
"bState"   => $bState,
"iCountUser" => $iCountUser,
"sMsgTitle"   => $sMsgTitle,
"sMsg"   => $sMsg,
);

?>