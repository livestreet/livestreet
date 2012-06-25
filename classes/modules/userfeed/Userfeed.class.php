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
 * Модуль пользовательских лент контента (топиков)
 *
 * @package modules.userfeed
 * @since 1.0
 */
class ModuleUserfeed extends Module {
	/**
	 * Подписки на топики по блогу
	 */
	const SUBSCRIBE_TYPE_BLOG = 1;
	/**
	 * Подписки на топики по юзеру
	 */
	const SUBSCRIBE_TYPE_USER = 2;
	/**
	 * Объект маппера
	 *
	 * @var ModuleUserfeed_MapperUserfeed|null
	 */
	protected $oMapper = null;

	/**
	 * Инициализация модуля
	 */
	public function Init() {
		$this->oMapper=Engine::GetMapper(__CLASS__);
	}
	/**
	 * Подписать пользователя
	 *
	 * @param int $iUserId ID подписываемого пользователя
	 * @param int $iSubscribeType Тип подписки (см. константы класса)
	 * @param int $iTargetId ID цели подписки
	 * @return bool
	 */
	public function subscribeUser($iUserId, $iSubscribeType, $iTargetId) {
		return $this->oMapper->subscribeUser($iUserId, $iSubscribeType, $iTargetId);
	}
	/**
	 * Отписать пользователя
	 *
	 * @param int $iUserId ID подписываемого пользователя
	 * @param int $iSubscribeType Тип подписки (см. константы класса)
	 * @param int $iTargetId ID цели подписки
	 * @return bool
	 */
	public function unsubscribeUser($iUserId, $iSubscribeType, $iTargetId) {
		return $this->oMapper->unsubscribeUser($iUserId, $iSubscribeType, $iTargetId);
	}
	/**
	 * Получить ленту топиков по подписке
	 *
	 * @param int $iUserId ID пользователя, для которого получаем ленту
	 * @param int $iCount Число получаемых записей (если null, из конфига)
	 * @param int $iFromId Получить записи, начиная с указанной
	 * @return array
	 */
	public function read($iUserId, $iCount = null, $iFromId = null) {
		if (!$iCount) $iCount = Config::Get('module.userfeed.count_default');
		$aUserSubscribes = $this->oMapper->getUserSubscribes($iUserId);
		$aTopicsIds = $this->oMapper->readFeed($aUserSubscribes, $iCount, $iFromId);
		return $this->Topic_getTopicsAdditionalData($aTopicsIds);
	}
	/**
	 * Получить список подписок пользователя
	 *
	 * @param int $iUserId ID пользователя, для которого загружаются подписки
	 * @return array
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