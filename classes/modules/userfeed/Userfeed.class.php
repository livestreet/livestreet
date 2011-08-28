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
 * Модуль пользовательских лент контента
 *
 */
class ModuleUserfeed extends Module
{
	const SUBSCRIBE_TYPE_BLOG = 1; // Подписки на топики по блогу
	const SUBSCRIBE_TYPE_USER = 2;// Подписки на топики по юзеру

	protected $oMapper = null;

	public function Init() {
		$this->oMapper=Engine::GetMapper(__CLASS__);
	}

	/**
     * Подписать пользователя
     * @param type $iUserId Id подписываемого пользователя
     * @param type $iSubscribeType Тип подписки (см. константы класса)
     * @param type $iTargetId Id цели подписки
     */
	public function subscribeUser($iUserId, $iSubscribeType, $iTargetId) {
		return $this->oMapper->subscribeUser($iUserId, $iSubscribeType, $iTargetId);
	}

	/**
     * Отписать пользователя
     * @param type $iUserId Id подписываемого пользователя
     * @param type $iSubscribeType Тип подписки (см. константы класса)
     * @param type $iTargetId Id цели подписки
     */
	public function unsubscribeUser($iUserId, $iSubscribeType, $iTargetId) {
		return $this->oMapper->unsubscribeUser($iUserId, $iSubscribeType, $iTargetId);
	}

	/**
     * Получить ленту топиков по подписке
     * @param type $iUserId Id пользователя, для которого получаем ленту
     * @param type $iCount Число получаемых записей (если null, из конфига)
     * @param type $iFromId Получить записи, начиная с указанной
     * @return type
     */
	public function read($iUserId, $iCount = null, $iFromId = null) {
		if (!$iCount) $iCount = Config::Get('module.userfeed.count_default');
		$aUserSubscribes = $this->oMapper->getUserSubscribes($iUserId);
		$aTopicsIds = $this->oMapper->readFeed($aUserSubscribes, $iCount, $iFromId);
		return $this->Topic_getTopicsAdditionalData($aTopicsIds);
	}

	/**
     * Получить список подписок пользователя
     * @param type $iUserId Id пользователя, для которого загружаются подписки
     * @return type
     */
	public function getUserSubscribes($iUserId) {
		$aUserSubscribes = $this->oMapper->getUserSubscribes($iUserId);
		$aResult = array('blogs' => array(), 'users' => array());
		if (count($aUserSubscribes['blogs'])) {
			$aBlogs = $this->Blog_getBlogsByArrayId($aUserSubscribes['blogs']);
			foreach ($aBlogs as $oBlog) {
				$aResult['blogs'][$oBlog->getId()] = $oBlog;
			}
		}
		if (count($aUserSubscribes['users'])) {
			$aUsers = $this->User_getUsersByArrayId($aUserSubscribes['users']);
			foreach ($aUsers as $oUser) {
				$aResult['users'][$oUser->getId()] = $oUser;
			}
		}

		return $aResult;
	}
}