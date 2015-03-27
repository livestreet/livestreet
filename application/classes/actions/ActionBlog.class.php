<?php
/*
 * LiveStreet CMS
 * Copyright © 2013 OOO "ЛС-СОФТ"
 *
 * ------------------------------------------------------
 *
 * Official site: www.livestreetcms.com
 * Contact e-mail: office@livestreetcms.com
 *
 * GNU General Public License, version 2:
 * http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * ------------------------------------------------------
 *
 * @link http://www.livestreetcms.com
 * @copyright 2013 OOO "ЛС-СОФТ"
 * @author Maxim Mzhelskiy <rus.engine@gmail.com>
 *
 */

/**
 * Экшен обработки URL'ов вида /blog/
 *
 * @package application.actions
 * @since 1.0
 */
class ActionBlog extends Action
{
    /**
     * Главное меню
     *
     * @var string
     */
    protected $sMenuHeadItemSelect = 'blog';
    /**
     * Какое меню активно
     *
     * @var string
     */
    protected $sMenuItemSelect = 'blog';
    /**
     * Какое подменю активно
     *
     * @var string
     */
    protected $sMenuSubItemSelect = 'good';
    /**
     * УРЛ блога который подставляется в меню
     *
     * @var string
     */
    protected $sMenuSubBlogUrl;
    /**
     * Текущий пользователь
     *
     * @var ModuleUser_EntityUser|null
     */
    protected $oUserCurrent = null;
    /**
     * Число новых топиков в коллективных блогах
     *
     * @var int
     */
    protected $iCountTopicsCollectiveNew = 0;
    /**
     * Число новых топиков в персональных блогах
     *
     * @var int
     */
    protected $iCountTopicsPersonalNew = 0;
    /**
     * Число новых топиков в конкретном блоге
     *
     * @var int
     */
    protected $iCountTopicsBlogNew = 0;
    /**
     * Общее число новых топиков
     *
     * @var int
     */
    protected $iCountTopicsNew = 0;
    /**
     * Число новых топиков в выбранном разделе
     *
     * @var int
     */
    protected $iCountTopicsSubNew = 0;
    /**
     * URL-префикс для навигации по топикам
     *
     * @var string
     */
    protected $sNavTopicsSubUrl = '';
    /**
     * Список URL с котрыми запрещено создавать блог
     *
     * @var array
     */
    protected $aBadBlogUrl = array(
        'new',
        'good',
        'bad',
        'discussed',
        'top',
        'edit',
        'add',
        'admin',
        'delete',
        'invite',
        'ajaxaddcomment',
        'ajaxaddbloginvite',
        'ajaxresponsecomment',
        'ajaxrebloginvite',
        'ajaxbloginfo',
        'ajaxblogjoin',
        'ajax',
        '_show_topic_url',
    );

    /**
     * Инизиализация экшена
     *
     */
    public function Init()
    {
        /**
         * Устанавливаем евент по дефолту, т.е. будем показывать хорошие топики из коллективных блогов
         */
        $this->SetDefaultEvent('good');
        $this->sMenuSubBlogUrl = Router::GetPath('blog');
        /**
         * Достаём текущего пользователя
         */
        $this->oUserCurrent = $this->User_GetUserCurrent();
        /**
         * Подсчитываем новые топики
         */
        $this->iCountTopicsCollectiveNew = $this->Topic_GetCountTopicsCollectiveNew();
        $this->iCountTopicsPersonalNew = $this->Topic_GetCountTopicsPersonalNew();
        $this->iCountTopicsBlogNew = $this->iCountTopicsCollectiveNew;
        $this->iCountTopicsNew = $this->iCountTopicsCollectiveNew + $this->iCountTopicsPersonalNew;
        $this->iCountTopicsSubNew = $this->iCountTopicsCollectiveNew;
        $this->sNavTopicsSubUrl = Router::GetPath('blog');
        /**
         * Загружаем в шаблон JS текстовки
         */
        $this->Lang_AddLangJs(array(
            'blog.join.join',
            'blog.join.leave'
        ));
    }

    /**
     * Регистрируем евенты, по сути определяем УРЛы вида /blog/.../
     *
     */
    protected function RegisterEvent()
    {
        $this->AddEventPreg('/^good$/i', '/^(page([1-9]\d{0,5}))?$/i', array('EventTopics', 'topics'));
        $this->AddEvent('good', array('EventTopics', 'topics'));
        $this->AddEventPreg('/^bad$/i', '/^(page([1-9]\d{0,5}))?$/i', array('EventTopics', 'topics'));
        $this->AddEventPreg('/^new$/i', '/^(page([1-9]\d{0,5}))?$/i', array('EventTopics', 'topics'));
        $this->AddEventPreg('/^newall$/i', '/^(page([1-9]\d{0,5}))?$/i', array('EventTopics', 'topics'));
        $this->AddEventPreg('/^discussed$/i', '/^(page([1-9]\d{0,5}))?$/i', array('EventTopics', 'topics'));
        $this->AddEventPreg('/^top$/i', '/^(page([1-9]\d{0,5}))?$/i', array('EventTopics', 'topics'));

        $this->AddEvent('add', 'EventAddBlog');
        $this->AddEvent('edit', 'EventEditBlog');
        $this->AddEvent('delete', 'EventDeleteBlog');
        $this->AddEventPreg('/^admin$/i', '/^\d+$/i', '/^(page([1-9]\d{0,5}))?$/i', 'EventAdminBlog');
        $this->AddEvent('invite', 'EventInviteBlog');

        $this->AddEvent('ajaxaddcomment', 'AjaxAddComment');
        $this->AddEvent('ajaxresponsecomment', 'AjaxResponseComment');
        $this->AddEvent('ajaxaddbloginvite', 'AjaxAddBlogInvite');
        $this->AddEvent('ajaxrebloginvite', 'AjaxReBlogInvite');
        $this->AddEvent('ajaxremovebloginvite', 'AjaxRemoveBlogInvite');
        $this->AddEvent('ajaxbloginfo', 'AjaxBlogInfo');
        $this->AddEvent('ajaxblogjoin', 'AjaxBlogJoin');
        $this->AddEventPreg('/^ajax$/i', '/^upload-avatar$/i', '/^$/i', 'EventAjaxUploadAvatar');
        $this->AddEventPreg('/^ajax$/i', '/^crop-avatar$/i', '/^$/i', 'EventAjaxCropAvatar');
        $this->AddEventPreg('/^ajax$/i', '/^crop-cancel-avatar$/i', '/^$/i', 'EventAjaxCropCancelAvatar');
        $this->AddEventPreg('/^ajax$/i', '/^remove-avatar$/i', '/^$/i', 'EventAjaxRemoveAvatar');
        $this->AddEventPreg('/^ajax$/i', '/^modal-crop-avatar$/i', '/^$/i', 'EventAjaxModalCropAvatar');

        $this->AddEventPreg('/^_show_topic_url$/i', '/^$/i', 'EventInternalShowTopicByUrl');
        $this->AddEventPreg('/^(\d+)\.html$/i', '/^$/i', array('EventShowTopic', 'topic'));

        $this->AddEventPreg('/^[\w\-\_]+$/i', '/^(page([1-9]\d{0,5}))?$/i', array('EventShowBlog', 'blog'));
        $this->AddEventPreg('/^[\w\-\_]+$/i', '/^bad$/i', '/^(page([1-9]\d{0,5}))?$/i', array('EventShowBlog', 'blog'));
        $this->AddEventPreg('/^[\w\-\_]+$/i', '/^new$/i', '/^(page([1-9]\d{0,5}))?$/i', array('EventShowBlog', 'blog'));
        $this->AddEventPreg('/^[\w\-\_]+$/i', '/^newall$/i', '/^(page([1-9]\d{0,5}))?$/i',
            array('EventShowBlog', 'blog'));
        $this->AddEventPreg('/^[\w\-\_]+$/i', '/^discussed$/i', '/^(page([1-9]\d{0,5}))?$/i',
            array('EventShowBlog', 'blog'));
        $this->AddEventPreg('/^[\w\-\_]+$/i', '/^top$/i', '/^(page([1-9]\d{0,5}))?$/i', array('EventShowBlog', 'blog'));

        $this->AddEventPreg('/^[\w\-\_]+$/i', '/^users$/i', '/^(page([1-9]\d{0,5}))?$/i', 'EventShowUsers');
    }


    /**********************************************************************************
     ************************ РЕАЛИЗАЦИЯ ЭКШЕНА ***************************************
     **********************************************************************************
     */

    /**
     * Добавление нового блога
     *
     */
    protected function EventAddBlog()
    {
        /**
         * Устанавливаем title страницы
         */
        $this->Viewer_AddHtmlTitle($this->Lang_Get('blog.add.title'));
        /**
         * Меню
         */
        $this->sMenuSubItemSelect = 'add';
        $this->sMenuItemSelect = 'blog';
        /**
         * Проверяем авторизован ли пользователь
         */
        if (!$this->User_IsAuthorization()) {
            $this->Message_AddErrorSingle($this->Lang_Get('common.error.not_access'), $this->Lang_Get('common.error.error'));
            return Router::Action('error');
        }
        /**
         * Проверяем права на создание блога
         */
        if (!$this->ACL_CanCreateBlog($this->oUserCurrent)) {
            $this->Message_AddErrorSingle($this->Rbac_GetMsgLast());
            return Router::Action('error');
        }
        $this->Hook_Run('blog_add_show');
        /**
         * Прогружаем категории блогов
         */
        $aCategories = $this->Blog_GetCategoriesTree();
        $this->Viewer_Assign('blogCategories', $aCategories);
        /**
         * Создаем объект блога
         */
        $oBlog = Engine::GetEntity('Blog');
        /**
         * Запускаем проверку корректности ввода полей при добалении блога.
         * Дополнительно проверяем, что был отправлен POST запрос.
         */
        if (!$this->checkBlogFields($oBlog)) {
            return false;
        }
        /**
         * Если всё ок то пытаемся создать блог
         */
        $oBlog->setOwnerId($this->oUserCurrent->getId());
        $oBlog->setTitle(strip_tags(getRequestStr('blog_title')));
        /**
         * Парсим текст на предмет разных ХТМЛ тегов
         */
        $sText = $this->Text_Parser(getRequestStr('blog_description'));
        $oBlog->setDescription($sText);
        $oBlog->setType(getRequestStr('blog_type'));
        $oBlog->setDateAdd(date("Y-m-d H:i:s"));
        $oBlog->setLimitRatingTopic(getRequestStr('blog_limit_rating_topic'));
        $oBlog->setUrl(getRequestStr('blog_url'));
        $oBlog->setAvatar(null);
        /**
         * Создаём блог
         */
        $this->Hook_Run('blog_add_before', array('oBlog' => $oBlog));
        if ($this->Blog_AddBlog($oBlog)) {
            $this->Hook_Run('blog_add_after', array('oBlog' => $oBlog));
            /**
             * Сохраняем категории
             */
            if (Config::Get('module.blog.category_allow') and ($this->oUserCurrent->isAdministrator() or !Config::Get('module.blog.category_only_admin'))) {
                $oBlog->category->CallbackAfterSave();
            }
            /**
             * Получаем блог, это для получение полного пути блога, если он в будущем будет зависит от других сущностей(компании, юзер и т.п.)
             */
            $oBlog = $this->Blog_GetBlogById($oBlog->getId());
            /**
             * Фиксируем ID у media файлов
             */
            $this->Media_ReplaceTargetTmpById('blog', $oBlog->getId());
            /**
             * Добавляем событие в ленту
             */
            $this->Stream_write($oBlog->getOwnerId(), 'add_blog', $oBlog->getId());
            Router::Location($oBlog->getUrlFull());
        } else {
            $this->Message_AddError($this->Lang_Get('common.error.system.base'), $this->Lang_Get('common.error.error'));
        }
    }

    /**
     * Редактирование блога
     *
     */
    protected function EventEditBlog()
    {
        /**
         * Меню
         */
        $this->sMenuSubItemSelect = '';
        $this->sMenuItemSelect = 'profile';
        /**
         * Проверяем передан ли в УРЛе номер блога
         */
        $sBlogId = $this->GetParam(0);
        if (!$oBlog = $this->Blog_GetBlogById($sBlogId)) {
            return parent::EventNotFound();
        }
        /**
         * Проверяем тип блога
         */
        if ($oBlog->getType() == 'personal') {
            return parent::EventNotFound();
        }
        /**
         * Проверям авторизован ли пользователь
         */
        if (!$this->User_IsAuthorization()) {
            $this->Message_AddErrorSingle($this->Lang_Get('common.error.not_access'), $this->Lang_Get('common.error.error'));
            return Router::Action('error');
        }
        /**
         * Проверка на право редактировать блог
         */
        if (!$this->ACL_IsAllowEditBlog($oBlog, $this->oUserCurrent)) {
            return parent::EventNotFound();
        }

        $this->Hook_Run('blog_edit_show', array('oBlog' => $oBlog));
        /**
         * Прогружаем категории блогов
         */
        $aCategories = $this->Blog_GetCategoriesTree();
        $this->Viewer_Assign('blogCategories', $aCategories);
        /**
         * Устанавливаем title страницы
         */
        $this->Viewer_AddHtmlTitle($oBlog->getTitle());
        $this->Viewer_AddHtmlTitle($this->Lang_Get('common.edit'));

        $this->Viewer_Assign('blogEdit', $oBlog);
        /**
         * Устанавливаем шалон для вывода
         */
        $this->SetTemplateAction('add');
        /**
         * Если нажали кнопку "Сохранить"
         */
        if (isPost('submit_blog_add')) {
            /**
             * Запускаем проверку корректности ввода полей при редактировании блога
             */
            if (!$this->checkBlogFields($oBlog)) {
                return false;
            }
            $oBlog->setTitle(strip_tags(getRequestStr('blog_title')));
            /**
             * Парсим описание блога на предмет ХТМЛ тегов
             */
            $sText = $this->Text_Parser(getRequestStr('blog_description'));
            $oBlog->setDescription($sText);
            /**
             * Сбрасываем кеш, если поменяли тип блога
             * Нужна доработка, т.к. в этом блоге могут быть топики других юзеров
             */
            if ($oBlog->getType() != getRequestStr('blog_type')) {
                $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,
                    array("topic_update_user_{$oBlog->getOwnerId()}"));
            }
            $oBlog->setType(getRequestStr('blog_type'));
            $oBlog->setLimitRatingTopic(getRequestStr('blog_limit_rating_topic'));
            if ($this->oUserCurrent->isAdministrator()) {
                $oBlog->setUrl(getRequestStr('blog_url'));    // разрешаем смену URL блога только админу
            }
            /**
             * Обновляем блог
             */
            $this->Hook_Run('blog_edit_before', array('oBlog' => $oBlog));
            if ($this->Blog_UpdateBlog($oBlog)) {
                $this->Hook_Run('blog_edit_after', array('oBlog' => $oBlog));
                /**
                 * Сохраняем категории
                 */
                if (Config::Get('module.blog.category_allow') and ($this->oUserCurrent->isAdministrator() or !Config::Get('module.blog.category_only_admin'))) {
                    $oBlog->category->CallbackAfterSave();
                }

                Router::Location($oBlog->getUrlFull());
            } else {
                $this->Message_AddErrorSingle($this->Lang_Get('common.error.system.base'), $this->Lang_Get('common.error.error'));
                return Router::Action('error');
            }
        } else {
            /**
             * Загружаем данные в форму редактирования блога
             */
            $_REQUEST['blog_title'] = $oBlog->getTitle();
            $_REQUEST['blog_url'] = $oBlog->getUrl();
            $_REQUEST['blog_type'] = $oBlog->getType();
            $_REQUEST['blog_description'] = $oBlog->getDescription();
            $_REQUEST['blog_limit_rating_topic'] = $oBlog->getLimitRatingTopic();
            $_REQUEST['blog_id'] = $oBlog->getId();
        }
    }

    /**
     * Управление пользователями блога
     *
     */
    protected function EventAdminBlog()
    {
        /**
         * Меню
         */
        $this->sMenuItemSelect = 'admin';
        $this->sMenuSubItemSelect = '';
        /**
         * Проверяем передан ли в УРЛе номер блога
         */
        $sBlogId = $this->GetParam(0);
        if (!$oBlog = $this->Blog_GetBlogById($sBlogId)) {
            return parent::EventNotFound();
        }
        /**
         * Проверям авторизован ли пользователь
         */
        if (!$this->User_IsAuthorization()) {
            $this->Message_AddErrorSingle($this->Lang_Get('common.error.not_access'), $this->Lang_Get('common.error.error'));
            return Router::Action('error');
        }
        /**
         * Проверка на право управлением пользователями блога
         */
        if (!$this->ACL_IsAllowAdminBlog($oBlog, $this->oUserCurrent)) {
            return parent::EventNotFound();
        }
        /**
         * Обрабатываем сохранение формы
         */
        if (isPost('submit_blog_admin')) {
            $this->Security_ValidateSendForm();

            $aUserRank = getRequest('user_rank', array());
            if (!is_array($aUserRank)) {
                $aUserRank = array();
            }
            foreach ($aUserRank as $sUserId => $sRank) {
                $sRank = (string)$sRank;
                if (!($oBlogUser = $this->Blog_GetBlogUserByBlogIdAndUserId($oBlog->getId(), $sUserId))) {
                    $this->Message_AddError($this->Lang_Get('common.error.system.base'), $this->Lang_Get('common.error.error'));
                    break;
                }
                /**
                 * Увеличиваем число читателей блога
                 */
                if (in_array($sRank, array(
                        'administrator',
                        'moderator',
                        'reader'
                    )) and $oBlogUser->getUserRole() == ModuleBlog::BLOG_USER_ROLE_BAN
                ) {
                    $oBlog->setCountUser($oBlog->getCountUser() + 1);
                }

                switch ($sRank) {
                    case 'administrator':
                        $oBlogUser->setUserRole(ModuleBlog::BLOG_USER_ROLE_ADMINISTRATOR);
                        break;
                    case 'moderator':
                        $oBlogUser->setUserRole(ModuleBlog::BLOG_USER_ROLE_MODERATOR);
                        break;
                    case 'reader':
                        $oBlogUser->setUserRole(ModuleBlog::BLOG_USER_ROLE_USER);
                        break;
                    case 'ban':
                        if ($oBlogUser->getUserRole() != ModuleBlog::BLOG_USER_ROLE_BAN) {
                            $oBlog->setCountUser($oBlog->getCountUser() - 1);
                        }
                        $oBlogUser->setUserRole(ModuleBlog::BLOG_USER_ROLE_BAN);
                        break;
                    default:
                        $oBlogUser->setUserRole(ModuleBlog::BLOG_USER_ROLE_GUEST);
                }
                $this->Blog_UpdateRelationBlogUser($oBlogUser);
                $this->Message_AddNoticeSingle($this->Lang_Get('blog.admin.alerts.submit_success'));
            }
            $this->Blog_UpdateBlog($oBlog);
        }
        /**
         * Текущая страница
         */
        $iPage = $this->GetParamEventMatch(1, 2) ? $this->GetParamEventMatch(1, 2) : 1;
        /**
         * Получаем список подписчиков блога
         */
        $aResult = $this->Blog_GetBlogUsersByBlogId(
            $oBlog->getId(),
            array(
                ModuleBlog::BLOG_USER_ROLE_BAN,
                ModuleBlog::BLOG_USER_ROLE_USER,
                ModuleBlog::BLOG_USER_ROLE_MODERATOR,
                ModuleBlog::BLOG_USER_ROLE_ADMINISTRATOR
            ), $iPage, Config::Get('module.blog.users_per_page')
        );
        $aBlogUsers = $aResult['collection'];
        /**
         * Формируем постраничность
         */
        $aPaging = $this->Viewer_MakePaging($aResult['count'], $iPage, Config::Get('module.blog.users_per_page'),
            Config::Get('pagination.pages.count'), Router::GetPath('blog') . "admin/{$oBlog->getId()}");
        $this->Viewer_Assign('paging', $aPaging);
        /**
         * Устанавливаем title страницы
         */
        $this->Viewer_AddHtmlTitle($oBlog->getTitle());
        $this->Viewer_AddHtmlTitle($this->Lang_Get('blog.admin.title'));

        $this->Viewer_Assign('blogEdit', $oBlog);
        $this->Viewer_Assign('blogUsers', $aBlogUsers);
        /**
         * Устанавливаем шалон для вывода
         */
        $this->SetTemplateAction('admin');
        /**
         * Если блог закрытый, получаем приглашенных
         * и добавляем блок-форму для приглашения
         */
        if ($oBlog->getType() == 'close') {
            $aBlogUsersInvited = $this->Blog_GetBlogUsersByBlogId($oBlog->getId(), ModuleBlog::BLOG_USER_ROLE_INVITE,
                null);
            $this->Viewer_Assign('blogUsersInvited', $aBlogUsersInvited['collection']);
            $this->Viewer_AddBlock('right', 'component@blog.block.invite');
        }
    }

    /**
     * Проверка полей блога
     *
     * @param ModuleBlog_EntityBlog|null $oBlog
     * @return bool
     */
    protected function checkBlogFields($oBlog = null)
    {
        /**
         * Проверяем только если была отправлена форма с данными (методом POST)
         */
        if (!isPost('submit_blog_add')) {
            $_REQUEST['blog_limit_rating_topic'] = 0;
            return false;
        }
        $this->Security_ValidateSendForm();

        $bOk = true;
        /**
         * Проверяем есть ли название блога
         */
        if (!func_check(getRequestStr('blog_title'), 'text', 2, 200)) {
            $this->Message_AddError($this->Lang_Get('blog.add.fields.title.error'), $this->Lang_Get('common.error.error'));
            $bOk = false;
        } else {
            /**
             * Проверяем есть ли уже блог с таким названием
             */
            if ($oBlogExists = $this->Blog_GetBlogByTitle(getRequestStr('blog_title'))) {
                if (!$oBlog or $oBlog->getId() != $oBlogExists->getId()) {
                    $this->Message_AddError($this->Lang_Get('blog.add.fields.title.error_unique'),
                        $this->Lang_Get('common.error.error'));
                    $bOk = false;
                }
            }
        }

        /**
         * Проверяем есть ли URL блога, с заменой всех пробельных символов на "_"
         */
        if (!$oBlog or !$oBlog->getId() or $this->oUserCurrent->isAdministrator()) {
            $blogUrl = preg_replace("/\s+/", '_', getRequestStr('blog_url'));
            $_REQUEST['blog_url'] = $blogUrl;
            if (!func_check(getRequestStr('blog_url'), 'login', 2, 50)) {
                $this->Message_AddError($this->Lang_Get('blog.add.fields.url.error'), $this->Lang_Get('common.error.error'));
                $bOk = false;
            }
        }
        /**
         * Проверяем на счет плохих УРЛов
         */
        if (in_array(getRequestStr('blog_url'), $this->aBadBlogUrl)) {
            $this->Message_AddError($this->Lang_Get('blog.add.fields.url.error_badword') . ' ' . join(',',
                    $this->aBadBlogUrl), $this->Lang_Get('common.error.error'));
            $bOk = false;
        }
        /**
         * Проверяем есть ли уже блог с таким URL
         */
        if ($oBlogExists = $this->Blog_GetBlogByUrl(getRequestStr('blog_url'))) {
            if (!$oBlog or $oBlog->getId() != $oBlogExists->getId()) {
                $this->Message_AddError($this->Lang_Get('blog.add.fields.url.error_unique'), $this->Lang_Get('common.error.error'));
                $bOk = false;
            }
        }
        /**
         * Проверяем есть ли описание блога
         */
        if (!func_check(getRequestStr('blog_description'), 'text', 10, 3000)) {
            $this->Message_AddError($this->Lang_Get('blog.add.fields.description.error'), $this->Lang_Get('common.error.error'));
            $bOk = false;
        }
        /**
         * Проверяем доступные типы блога для создания
         */
        if (!$this->Blog_IsAllowBlogType(getRequestStr('blog_type'))) {
            $this->Message_AddError($this->Lang_Get('blog.add.fields.type.error'), $this->Lang_Get('common.error.error'));
            $bOk = false;
        }
        /**
         * Преобразуем ограничение по рейтингу в число
         */
        if (!func_check(getRequestStr('blog_limit_rating_topic'), 'float')) {
            $this->Message_AddError($this->Lang_Get('blog.add.fields.rating.error'), $this->Lang_Get('common.error.error'));
            $bOk = false;
        }
        /**
         * Проверяем категорию блога
         */
        if (Config::Get('module.blog.category_allow')) {
            if (true !== ($mRes = $oBlog->category->ValidateCategoriesCheck(getRequest('category')))) {
                $this->Message_AddError($mRes, $this->Lang_Get('common.error.error'));
                $bOk = false;
            }
        }

        /**
         * Выполнение хуков
         */
        $this->Hook_Run('check_blog_fields', array('bOk' => &$bOk));
        return $bOk;
    }

    /**
     * Показ всех топиков
     *
     */
    protected function EventTopics()
    {
        $sPeriod = 1; // по дефолту 1 день
        if (in_array(getRequestStr('period'), array(1, 7, 30, 'all'))) {
            $sPeriod = getRequestStr('period');
        }
        $sShowType = $this->sCurrentEvent;
        if (!in_array($sShowType, array('discussed', 'top'))) {
            $sPeriod = 'all';
        }
        /**
         * Меню
         */
        $this->sMenuSubItemSelect = $sShowType == 'newall' ? 'new' : $sShowType;
        /**
         * Передан ли номер страницы
         */
        $iPage = $this->GetParamEventMatch(0, 2) ? $this->GetParamEventMatch(0, 2) : 1;
        if ($iPage == 1 and !getRequest('period')) {
            $this->Viewer_SetHtmlCanonical(Router::GetPath('blog') . $sShowType . '/');
        }
        /**
         * Получаем список топиков
         */
        $aResult = $this->Topic_GetTopicsCollective($iPage, Config::Get('module.topic.per_page'), $sShowType,
            $sPeriod == 'all' ? null : $sPeriod * 60 * 60 * 24);
        /**
         * Если нет топиков за 1 день, то показываем за неделю (7)
         */
        if (in_array($sShowType,
                array('discussed', 'top')) and !$aResult['count'] and $iPage == 1 and !getRequest('period')
        ) {
            $sPeriod = 7;
            $aResult = $this->Topic_GetTopicsCollective($iPage, Config::Get('module.topic.per_page'), $sShowType,
                $sPeriod == 'all' ? null : $sPeriod * 60 * 60 * 24);
        }
        $aTopics = $aResult['collection'];
        /**
         * Вызов хуков
         */
        $this->Hook_Run('topics_list_show', array('aTopics' => $aTopics));
        /**
         * Формируем постраничность
         */
        $aPaging = $this->Viewer_MakePaging($aResult['count'], $iPage, Config::Get('module.topic.per_page'),
            Config::Get('pagination.pages.count'), Router::GetPath('blog') . $sShowType,
            in_array($sShowType, array('discussed', 'top')) ? array('period' => $sPeriod) : array());
        /**
         * Вызов хуков
         */
        $this->Hook_Run('blog_show', array('sShowType' => $sShowType));
        /**
         * Загружаем переменные в шаблон
         */
        $this->Viewer_Assign('topics', $aTopics);
        $this->Viewer_Assign('paging', $aPaging);
        if (in_array($sShowType, array('discussed', 'top'))) {
            $this->Viewer_Assign('periodSelectCurrent', $sPeriod);
            $this->Viewer_Assign('periodSelectRoot', Router::GetPath('blog') . $sShowType . '/');
        }
        /**
         * Устанавливаем шаблон вывода
         */
        $this->SetTemplateAction('index');
    }


    /**
     * Обработка ЧПУ топика
     */
    protected function EventInternalShowTopicByUrl()
    {
        $sTopicUrl = Config::Get('module.topic._router_topic_original_url');
        /**
         * Проверяем ключ
         */
        if (is_null($sTopicUrl)) {
            return $this->EventErrorDebug();
        }
        /**
         * Проверяем корректность URL топика
         * Сначала нужно получить сам топик по ID или уникальному полю Slug (транслитерированный заголовок)
         * Смотрим наличие ID или Slug в маске топика
         */
        $sUrlEscape = preg_quote(trim(Config::Get('module.topic.url'), '/ '));
        $aMask = array_map(function ($sItem) {
            return "({$sItem})";
        }, Config::Get('module.topic.url_preg'));
        $sPreg = strtr($sUrlEscape, $aMask);
        if (preg_match('@^' . $sPreg . '$@iu', $sTopicUrl, $aMatch)) {
            $aRuleRequire = array();
            if (preg_match_all('#%(\w+)%#', $sUrlEscape, $aMatch2)) {
                foreach ($aMatch2[1] as $k => $sFind) {
                    if (in_array($sFind, array('id', 'title'))) {
                        if (isset($aMatch[$k + 1])) {
                            $aRuleRequire[$sFind] = $aMatch[$k + 1];
                        }
                    }
                }
            }
            /**
             * Не удалось найти обязательные поля - запускаем обработку дальше по цепочке
             */
            if (!$aRuleRequire) {
                return Router::Action($sTopicUrl);
            }

            $oTopic = null;
            /**
             * Ищем топик
             */
            if (isset($aRuleRequire['id'])) {
                $oTopic = $this->Topic_GetTopicById($aRuleRequire['id']);
            } elseif (isset($aRuleRequire['title'])) {
                $oTopic = $this->Topic_GetTopicBySlug($aRuleRequire['title']);
            }
            if (!$oTopic) {
                return Router::Action($sTopicUrl);
            }
            /**
             * Проверяем корректность URL топика
             */
            if ($oTopic->getUrl(false) != $sTopicUrl) {
                Router::Location($oTopic->getUrl());
            }
            /**
             * Направляем на стандартную обработку топика
             */
            return Router::Action('blog', "{$oTopic->getId()}.html");
        }
        /**
         * Запускаем обработку дальше по цепочке
         */
        return Router::Action($sTopicUrl);
    }

    /**
     * Показ топика
     *
     */
    protected function EventShowTopic()
    {
        $iTopicId = $this->GetEventMatch(1);
        $this->sMenuItemSelect = 'blog';
        $this->sMenuSubItemSelect = '';
        /**
         * Проверяем есть ли такой топик
         */
        if (!($oTopic = $this->Topic_GetTopicById($iTopicId))) {
            return parent::EventNotFound();
        }
        /**
         * Проверяем права на просмотр топика
         */
        if (!$this->ACL_IsAllowShowTopic($oTopic, $this->oUserCurrent)) {
            return parent::EventNotFound();
        }
        /**
         * Достаём комменты к топику
         */
        if (!Config::Get('module.comment.nested_page_reverse') and Config::Get('module.comment.use_nested') and Config::Get('module.comment.nested_per_page')) {
            $iPageDef = ceil($this->Comment_GetCountCommentsRootByTargetId($oTopic->getId(),
                    'topic') / Config::Get('module.comment.nested_per_page'));
        } else {
            $iPageDef = 1;
        }
        $iPage = getRequest('cmtpage', 0) ? (int)getRequest('cmtpage', 0) : $iPageDef;
        $aReturn = $this->Comment_GetCommentsByTargetId($oTopic->getId(), 'topic', $iPage,
            Config::Get('module.comment.nested_per_page'));
        $iMaxIdComment = $aReturn['iMaxIdComment'];
        $aComments = $aReturn['comments'];
        /**
         * Если используется постраничность для комментариев - формируем ее
         */
        if (Config::Get('module.comment.use_nested') and Config::Get('module.comment.nested_per_page')) {
            $aPaging = $this->Viewer_MakePaging($aReturn['count'], $iPage,
                Config::Get('module.comment.nested_per_page'), Config::Get('pagination.pages.count'), '');
            if (!Config::Get('module.comment.nested_page_reverse') and $aPaging) {
                // переворачиваем страницы в обратном порядке
                $aPaging['aPagesLeft'] = array_reverse($aPaging['aPagesLeft']);
                $aPaging['aPagesRight'] = array_reverse($aPaging['aPagesRight']);
            }
            $this->Viewer_Assign('pagingComments', $aPaging);
        }
        /**
         * Отмечаем дату прочтения топика
         */
        if ($this->oUserCurrent) {
            $oTopicRead = Engine::GetEntity('Topic_TopicRead');
            $oTopicRead->setTopicId($oTopic->getId());
            $oTopicRead->setUserId($this->oUserCurrent->getId());
            $oTopicRead->setCommentCountLast($oTopic->getCountComment());
            $oTopicRead->setCommentIdLast($iMaxIdComment);
            $oTopicRead->setDateRead(date("Y-m-d H:i:s"));
            $this->Topic_SetTopicRead($oTopicRead);
        }
        /**
         * Выставляем SEO данные
         */
        $sTextSeo = strip_tags($oTopic->getText());
        $this->Viewer_SetHtmlDescription(func_text_words($sTextSeo, Config::Get('seo.description_words_count')));
        $this->Viewer_SetHtmlKeywords($oTopic->getTags());
        $this->Viewer_SetHtmlCanonical($oTopic->getUrl());
        /**
         * Open Graph
         */
        $this->Viewer_SetOpenGraphProperty('og:type', 'article');
        $this->Viewer_SetOpenGraphProperty('og:title', $oTopic->getTitle());
        $this->Viewer_SetOpenGraphProperty('og:description', $this->Viewer_GetHtmlDescription());
        $this->Viewer_SetOpenGraphProperty('og:url', $oTopic->getUrl());
        $this->Viewer_SetOpenGraphProperty('article:author', $oTopic->getUser()->getUserWebPath());
        $this->Viewer_SetOpenGraphProperty('article:published_time', date('c', strtotime($oTopic->getDatePublish())));
        if ($sImage = $oTopic->getPreviewImageWebPath(Config::Get('module.topic.default_preview_size'))) {
            $this->Viewer_SetOpenGraphProperty('og:image', $sImage);
        }
        if ($aTags = $oTopic->getTagsArray()) {
            $this->Viewer_SetOpenGraphProperty('article:tag', $aTags);
        }
        /**
         * Вызов хуков
         */
        $this->Hook_Run('topic_show', array("oTopic" => $oTopic));
        /**
         * Загружаем переменные в шаблон
         */
        $this->Viewer_Assign('topic', $oTopic);
        $this->Viewer_Assign('comments', $aComments);
        $this->Viewer_Assign('lastCommentId', $iMaxIdComment);
        /**
         * Устанавливаем title страницы
         */
        $this->Viewer_AddHtmlTitle($oTopic->getBlog()->getTitle());
        $this->Viewer_AddHtmlTitle($oTopic->getTitle());
        $this->Viewer_SetHtmlRssAlternate(Router::GetPath('rss') . 'comments/' . $oTopic->getId() . '/',
            $oTopic->getTitle());
        /**
         * Устанавливаем шаблон вывода
         */
        $this->SetTemplateAction('topic');
    }

    /**
     * Страница со списком читателей блога
     *
     */
    protected function EventShowUsers()
    {
        $sBlogUrl = $this->sCurrentEvent;
        /**
         * Проверяем есть ли блог с таким УРЛ
         */
        if (!($oBlog = $this->Blog_GetBlogByUrl($sBlogUrl))) {
            return parent::EventNotFound();
        }
        /**
         * Меню
         */
        $this->sMenuSubItemSelect = '';
        $this->sMenuSubBlogUrl = $oBlog->getUrlFull();
        /**
         * Текущая страница
         */
        $iPage = $this->GetParamEventMatch(1, 2) ? $this->GetParamEventMatch(1, 2) : 1;
        $aBlogUsersResult = $this->Blog_GetBlogUsersByBlogId($oBlog->getId(), ModuleBlog::BLOG_USER_ROLE_USER, $iPage,
            Config::Get('module.blog.users_per_page'));
        $aBlogUsers = $aBlogUsersResult['collection'];
        /**
         * Формируем постраничность
         */
        $aPaging = $this->Viewer_MakePaging($aBlogUsersResult['count'], $iPage,
            Config::Get('module.blog.users_per_page'), Config::Get('pagination.pages.count'),
            $oBlog->getUrlFull() . 'users');
        $this->Viewer_Assign('paging', $aPaging);
        /**
         * Вызов хуков
         */
        $this->Hook_Run('blog_collective_show_users', array('oBlog' => $oBlog));
        /**
         * Загружаем переменные в шаблон
         */
        $this->Viewer_Assign('blogUsers', $aBlogUsers);
        $this->Viewer_Assign('countBlogUsers', $aBlogUsersResult['count']);
        $this->Viewer_Assign('blog', $oBlog);
        /**
         * Устанавливаем title страницы
         */
        $this->Viewer_AddHtmlTitle($oBlog->getTitle());
        /**
         * Устанавливаем шаблон вывода
         */
        $this->SetTemplateAction('users');
    }

    /**
     * Вывод топиков из определенного блога
     *
     */
    protected function EventShowBlog()
    {
        $sPeriod = 1; // по дефолту 1 день
        if (in_array(getRequestStr('period'), array(1, 7, 30, 'all'))) {
            $sPeriod = getRequestStr('period');
        }
        $sBlogUrl = $this->sCurrentEvent;
        $sShowType = in_array($this->GetParamEventMatch(0, 0),
            array('bad', 'new', 'newall', 'discussed', 'top')) ? $this->GetParamEventMatch(0, 0) : 'good';
        if (!in_array($sShowType, array('discussed', 'top'))) {
            $sPeriod = 'all';
        }
        /**
         * Проверяем есть ли блог с таким УРЛ
         */
        if (!($oBlog = $this->Blog_GetBlogByUrl($sBlogUrl))) {
            return parent::EventNotFound();
        }
        /**
         * Определяем права на отображение закрытого блога
         */
        if ($oBlog->getType() == 'close'
            and (!$this->oUserCurrent
                or !in_array(
                    $oBlog->getId(),
                    $this->Blog_GetAccessibleBlogsByUser($this->oUserCurrent)
                )
            )
        ) {
            $bPrivateBlog = true;
        } else {
            $bPrivateBlog = false;
        }
        /**
         * Меню
         */
        $this->sMenuSubItemSelect = $sShowType == 'newall' ? 'new' : $sShowType;
        $this->sNavTopicsSubUrl = $oBlog->getUrlFull();
        /**
         * Передан ли номер страницы
         */
        $iPage = $this->GetParamEventMatch(($sShowType == 'good') ? 0 : 1,
            2) ? $this->GetParamEventMatch(($sShowType == 'good') ? 0 : 1, 2) : 1;
        if ($iPage == 1 and !getRequest('period') and in_array($sShowType, array('discussed', 'top'))) {
            $this->Viewer_SetHtmlCanonical($oBlog->getUrlFull() . $sShowType . '/');
        }

        if (!$bPrivateBlog) {
            /**
             * Получаем список топиков
             */
            $aResult = $this->Topic_GetTopicsByBlog($oBlog, $iPage, Config::Get('module.topic.per_page'), $sShowType,
                $sPeriod == 'all' ? null : $sPeriod * 60 * 60 * 24);
            /**
             * Если нет топиков за 1 день, то показываем за неделю (7)
             */
            if (in_array($sShowType,
                    array('discussed', 'top')) and !$aResult['count'] and $iPage == 1 and !getRequest('period')
            ) {
                $sPeriod = 7;
                $aResult = $this->Topic_GetTopicsByBlog($oBlog, $iPage, Config::Get('module.topic.per_page'),
                    $sShowType, $sPeriod == 'all' ? null : $sPeriod * 60 * 60 * 24);
            }
            $aTopics = $aResult['collection'];
            /**
             * Формируем постраничность
             */
            $aPaging = ($sShowType == 'good')
                ? $this->Viewer_MakePaging($aResult['count'], $iPage, Config::Get('module.topic.per_page'),
                    Config::Get('pagination.pages.count'), rtrim($oBlog->getUrlFull(), '/'))
                : $this->Viewer_MakePaging($aResult['count'], $iPage, Config::Get('module.topic.per_page'),
                    Config::Get('pagination.pages.count'), $oBlog->getUrlFull() . $sShowType,
                    array('period' => $sPeriod));
            /**
             * Получаем число новых топиков в текущем блоге
             */
            $this->iCountTopicsSubNew = $this->Topic_GetCountTopicsByBlogNew($oBlog);

            $this->Viewer_Assign('paging', $aPaging);
            $this->Viewer_Assign('topics', $aTopics);
            if (in_array($sShowType, array('discussed', 'top'))) {
                $this->Viewer_Assign('periodSelectCurrent', $sPeriod);
                $this->Viewer_Assign('periodSelectRoot', $oBlog->getUrlFull() . $sShowType . '/');
            }
        }
        /**
         * Выставляем SEO данные
         */
        $sTextSeo = strip_tags($oBlog->getDescription());
        $this->Viewer_SetHtmlDescription(func_text_words($sTextSeo, Config::Get('seo.description_words_count')));
        /**
         * Получаем список юзеров блога
         */
        $aBlogUsersResult = $this->Blog_GetBlogUsersByBlogId($oBlog->getId(), ModuleBlog::BLOG_USER_ROLE_USER, 1,
            Config::Get('module.blog.users_per_page'));
        $aBlogUsers = $aBlogUsersResult['collection'];
        $aBlogModeratorsResult = $this->Blog_GetBlogUsersByBlogId($oBlog->getId(),
            ModuleBlog::BLOG_USER_ROLE_MODERATOR);
        $aBlogModerators = $aBlogModeratorsResult['collection'];
        $aBlogAdministratorsResult = $this->Blog_GetBlogUsersByBlogId($oBlog->getId(),
            ModuleBlog::BLOG_USER_ROLE_ADMINISTRATOR);
        $aBlogAdministrators = $aBlogAdministratorsResult['collection'];
        /**
         * Для админов проекта получаем список блогов и передаем их во вьювер
         */
        if ($this->oUserCurrent and $this->oUserCurrent->isAdministrator()) {
            $aBlogs = $this->Blog_GetBlogs();
            unset($aBlogs[$oBlog->getId()]);

            $this->Viewer_Assign('blogs', $aBlogs);
        }
        /**
         * Вызов хуков
         */
        $this->Hook_Run('blog_collective_show', array('oBlog' => $oBlog, 'sShowType' => $sShowType));
        /**
         * Загружаем переменные в шаблон
         */
        $this->Viewer_Assign('blogUsers', $aBlogUsers);
        $this->Viewer_Assign('blogModerators', $aBlogModerators);
        $this->Viewer_Assign('blogAdministrators', $aBlogAdministrators);
        $this->Viewer_Assign('countBlogUsers', $aBlogUsersResult['count']);
        $this->Viewer_Assign('countBlogModerators', $aBlogModeratorsResult['count']);
        $this->Viewer_Assign('countBlogAdministrators', $aBlogAdministratorsResult['count'] + 1);
        $this->Viewer_Assign('blog', $oBlog);
        $this->Viewer_Assign('isPrivateBlog', $bPrivateBlog);
        /**
         * Устанавливаем title страницы
         */
        $this->Viewer_AddHtmlTitle($oBlog->getTitle());
        $this->Viewer_SetHtmlRssAlternate(Router::GetPath('rss') . 'blog/' . $oBlog->getUrl() . '/',
            $oBlog->getTitle());
        /**
         * Устанавливаем шаблон вывода
         */
        $this->SetTemplateAction('blog');
    }

    /**
     * Обработка добавление комментария к топику через ajax
     *
     */
    protected function AjaxAddComment()
    {
        /**
         * Устанавливаем формат Ajax ответа
         */
        $this->Viewer_SetResponseAjax('json');
        $this->SubmitComment();
    }

    /**
     * Проверка на соответсвие коментария требованиям безопасности
     *
     * @param ModuleTopic_EntityTopic $oTopic
     * @param string $sText
     *
     * @return bool result
     */
    protected function CheckComment($oTopic, $sText)
    {

        $bOk = true;
        /**
         * Проверям авторизован ли пользователь
         */
        if (!$this->User_IsAuthorization()) {
            $this->Message_AddErrorSingle($this->Lang_Get('common.error.need_authorization'), $this->Lang_Get('common.error.error'));
            $bOk = false;
        }
        /**
         * Проверяем топик
         */
        if (!$oTopic) {
            $this->Message_AddErrorSingle($this->Lang_Get('common.error.system.base'), $this->Lang_Get('common.error.error'));
            return false;
        }
        /**
         * Права на просмотр топика
         */
        if (!$this->ACL_IsAllowShowTopic($oTopic, $this->oUserCurrent)) {
            $this->Message_AddErrorSingle($this->Lang_Get('common.error.system.base'), $this->Lang_Get('common.error.error'));
            $bOk = false;
        }
        /**
         * Проверяем разрешено ли постить комменты
         */
        if (!$this->ACL_CanPostComment($this->oUserCurrent, $oTopic)) {
            $this->Message_AddErrorSingle($this->Rbac_GetMsgLast());
            $bOk = false;
        }
        /**
         * Проверяем запрет на добавления коммента автором топика
         */
        if ($oTopic->getForbidComment()) {
            $this->Message_AddErrorSingle($this->Lang_Get('topic.comments.notices.not_allowed'),
                $this->Lang_Get('common.error.error'));
            $bOk = false;
        }
        /**
         * Проверяем текст комментария
         */
        if (!func_check($sText, 'text', 2, 10000)) {
            $this->Message_AddErrorSingle($this->Lang_Get('topic.comments.notices.error_text'),
                $this->Lang_Get('common.error.error'));
            $bOk = false;
        }

        $this->Hook_Run('comment_check', array('oTopic' => $oTopic, 'sText' => $sText, 'bOk' => &$bOk));

        return $bOk;
    }

    /**
     * Проверка на соответсвие коментария родительскому коментарию
     *
     * @param ModuleTopic_EntityTopic $oTopic
     * @param string $sText
     * @param ModuleComment_EntityComment $oCommentParent
     *
     * @return bool result
     */
    protected function CheckParentComment($oTopic, $sText, $oCommentParent)
    {

        $sParentId = 0;
        if ($oCommentParent) {
            $sParentId = $oCommentParent->GetCommentId();
        }

        $bOk = true;
        /**
         * Проверям на какой коммент отвечаем
         */
        if (!func_check($sParentId, 'id')) {
            $this->Message_AddErrorSingle($this->Lang_Get('common.error.system.base'), $this->Lang_Get('common.error.error'));
            $bOk = false;
        }

        if ($sParentId) {
            /**
             * Проверяем существует ли комментарий на который отвечаем
             */
            if (!($oCommentParent)) {
                $this->Message_AddErrorSingle($this->Lang_Get('common.error.system.base'), $this->Lang_Get('common.error.error'));
                $bOk = false;
            }
            /**
             * Проверяем из одного топика ли новый коммент и тот на который отвечаем
             */
            if ($oCommentParent->getTargetId() != $oTopic->getId()) {
                $this->Message_AddErrorSingle($this->Lang_Get('common.error.system.base'), $this->Lang_Get('common.error.error'));
                $bOk = false;
            }
        } else {
            $sParentId = null;
        }

        /**
         * Проверка на дублирующий коммент
         */
        if ($this->Comment_GetCommentUnique($oTopic->getId(), 'topic', $this->oUserCurrent->getId(), $sParentId,
            md5($sText))
        ) {
            $this->Message_AddErrorSingle($this->Lang_Get('topic.comments.notices.spam'), $this->Lang_Get('common.error.error'));
            $bOk = false;
        }

        $this->Hook_Run('comment_check_parent',
            array('oTopic' => $oTopic, 'sText' => $sText, 'oCommentParent' => $oCommentParent, 'bOk' => &$bOk));

        return $bOk;
    }

    /**
     * Обработка добавление комментария к топику
     *
     */
    protected function SubmitComment()
    {

        $oTopic = $this->Topic_GetTopicById(getRequestStr('comment_target_id'));
        $sText = getRequestStr('comment_text');
        $sParentId = (int)getRequest('reply');
        $oCommentParent = null;

        if (!$sParentId) {
            /**
             * Корневой комментарий
             */
            $sParentId = null;
        } else {
            /**
             * Родительский комментарий
             */
            $oCommentParent = $this->Comment_GetCommentById($sParentId);
        }

        /**
         * Проверка на соответсвие комментария требованиям безопасности
         */
        if (!$this->CheckComment($oTopic, $sText)) {
            return;
        }

        /**
         * Проверка на соответсвие комментария родительскому коментарию
         */
        if (!$this->CheckParentComment($oTopic, $sText, $oCommentParent)) {
            return;
        }

        /**
         * Создаём коммент
         */
        $oCommentNew = Engine::GetEntity('Comment');
        $oCommentNew->setTargetId($oTopic->getId());
        $oCommentNew->setTargetType('topic');
        $oCommentNew->setTargetParentId($oTopic->getBlog()->getId());
        $oCommentNew->setUserId($this->oUserCurrent->getId());
        $oCommentNew->setText($this->Text_Parser($sText));
        $oCommentNew->setTextSource($sText);
        $oCommentNew->setDate(date("Y-m-d H:i:s"));
        $oCommentNew->setUserIp(func_getIp());
        $oCommentNew->setPid($sParentId);
        $oCommentNew->setTextHash(md5($sText));
        $oCommentNew->setPublish($oTopic->getPublish());
        /**
         * Добавляем коммент
         */
        $this->Hook_Run('comment_add_before',
            array('oCommentNew' => $oCommentNew, 'oCommentParent' => $oCommentParent, 'oTopic' => $oTopic));
        if ($this->Comment_AddComment($oCommentNew)) {
            $this->Hook_Run('comment_add_after',
                array('oCommentNew' => $oCommentNew, 'oCommentParent' => $oCommentParent, 'oTopic' => $oTopic));

            $this->Viewer_AssignAjax('sCommentId', $oCommentNew->getId());
            if ($oTopic->getPublish()) {
                /**
                 * Добавляем коммент в прямой эфир если топик не в черновиках
                 */
                $oCommentOnline = Engine::GetEntity('Comment_CommentOnline');
                $oCommentOnline->setTargetId($oCommentNew->getTargetId());
                $oCommentOnline->setTargetType($oCommentNew->getTargetType());
                $oCommentOnline->setTargetParentId($oCommentNew->getTargetParentId());
                $oCommentOnline->setCommentId($oCommentNew->getId());

                $this->Comment_AddCommentOnline($oCommentOnline);
            }
            /**
             * Сохраняем дату последнего коммента для юзера
             */
            $this->oUserCurrent->setDateCommentLast(date("Y-m-d H:i:s"));
            $this->User_Update($this->oUserCurrent);
            /**
             * Фиксируем ID у media файлов комментария
             */
            $this->Media_ReplaceTargetTmpById('comment', $oCommentNew->getId());
            /**
             * Список емайлов на которые не нужно отправлять уведомление
             */
            $aExcludeMail = array($this->oUserCurrent->getMail());
            /**
             * Отправляем уведомление тому на чей коммент ответили
             */
            if ($oCommentParent and $oCommentParent->getUserId() != $oTopic->getUserId() and $oCommentNew->getUserId() != $oCommentParent->getUserId()) {
                $oUserAuthorComment = $oCommentParent->getUser();
                $aExcludeMail[] = $oUserAuthorComment->getMail();
                $this->Topic_SendNotifyCommentReplyToAuthorParentComment($oUserAuthorComment, $oTopic, $oCommentNew,
                    $this->oUserCurrent);
            }
            /**
             * Отправка уведомления автору топика
             */
            $this->Subscribe_Send('topic_new_comment', $oTopic->getId(),
                'comment_new.tpl', $this->Lang_Get('emails.comment_new.subject'),
                array(
                    'oTopic'       => $oTopic,
                    'oComment'     => $oCommentNew,
                    'oUserComment' => $this->oUserCurrent,
                ), $aExcludeMail);
            /**
             * Добавляем событие в ленту
             */
            $this->Stream_write($oCommentNew->getUserId(), 'add_comment', $oCommentNew->getId(),
                $oTopic->getPublish() && $oTopic->getBlog()->getType() != 'close');
        } else {
            $this->Message_AddErrorSingle($this->Lang_Get('common.error.system.base'), $this->Lang_Get('common.error.error'));
        }
    }

    /**
     * Получение новых комментариев
     *
     */
    protected function AjaxResponseComment()
    {
        /**
         * Устанавливаем формат Ajax ответа
         */
        $this->Viewer_SetResponseAjax('json');
        /**
         * Пользователь авторизован?
         */
        if (!$this->oUserCurrent) {
            $this->Message_AddErrorSingle($this->Lang_Get('common.error.need_authorization'), $this->Lang_Get('common.error.error'));
            return;
        }
        /**
         * Топик существует?
         */
        $idTopic = getRequestStr('target_id', null, 'post');
        if (!($oTopic = $this->Topic_GetTopicById($idTopic))) {
            return $this->EventErrorDebug();
        }
        /**
         * Есть доступ к комментариям этого топика? Закрытый блог?
         */
        if (!$this->ACL_IsAllowShowBlog($oTopic->getBlog(), $this->oUserCurrent)) {
            return $this->EventErrorDebug();
        }

        $idCommentLast = getRequestStr('last_comment_id', null, 'post');
        $selfIdComment = getRequestStr('self_comment_id', null, 'post');
        $aComments = array();
        /**
         * Если используется постраничность, возвращаем только добавленный комментарий
         */
        if (getRequest('use_paging', null, 'post') and $selfIdComment) {
            if ($oComment = $this->Comment_GetCommentById($selfIdComment) and $oComment->getTargetId() == $oTopic->getId() and $oComment->getTargetType() == 'topic') {
                $oViewerLocal = $this->Viewer_GetLocalViewer();

                $oViewerLocal->Assign('oUserCurrent', $this->oUserCurrent);
                $oViewerLocal->Assign('oneComment', true, true);
                $oViewerLocal->Assign('useFavourite', true, true);
                $oViewerLocal->Assign('useVote', true, true);
                $oViewerLocal->Assign('comment', $oComment, true);

                $sHtml = $oViewerLocal->Fetch($this->Comment_GetTemplateCommentByTarget($oTopic->getId(), 'topic'));

                $aCmt = array();
                $aCmt[] = array(
                    'html' => $sHtml,
                    'obj'  => $oComment,
                );
            } else {
                $aCmt = array();
            }
            $aReturn['comments'] = $aCmt;
            $aReturn['iMaxIdComment'] = $selfIdComment;
        } else {
            $aReturn = $this->Comment_GetCommentsNewByTargetId($oTopic->getId(), 'topic', $idCommentLast);
        }
        $iMaxIdComment = $aReturn['iMaxIdComment'];

        $oTopicRead = Engine::GetEntity('Topic_TopicRead');
        $oTopicRead->setTopicId($oTopic->getId());
        $oTopicRead->setUserId($this->oUserCurrent->getId());
        $oTopicRead->setCommentCountLast($oTopic->getCountComment());
        $oTopicRead->setCommentIdLast($iMaxIdComment);
        $oTopicRead->setDateRead(date("Y-m-d H:i:s"));
        $this->Topic_SetTopicRead($oTopicRead);

        $aCmts = $aReturn['comments'];
        if ($aCmts and is_array($aCmts)) {
            foreach ($aCmts as $aCmt) {
                $aComments[] = array(
                    'html'      => $aCmt['html'],
                    'parent_id' => $aCmt['obj']->getPid(),
                    'id'        => $aCmt['obj']->getId(),
                );
            }
        }

        $this->Viewer_AssignAjax('last_comment_id', $iMaxIdComment);
        $this->Viewer_AssignAjax('comments', $aComments);
    }

    /**
     * Обработка ajax запроса на отправку
     * пользователям приглашения вступить в закрытый блог
     */
    protected function AjaxAddBlogInvite()
    {
        /**
         * Устанавливаем формат Ajax ответа
         */
        $this->Viewer_SetResponseAjax('json');
        $aUsers = getRequest('users', null, 'post');
        $sBlogId = getRequestStr('target_id', null, 'post');
        /**
         * Если пользователь не авторизирован, возвращаем ошибку
         */
        if (!$this->User_IsAuthorization()) {
            $this->Message_AddErrorSingle($this->Lang_Get('common.error.need_authorization'), $this->Lang_Get('common.error.error'));
            return;
        }
        $this->oUserCurrent = $this->User_GetUserCurrent();
        /**
         * Проверяем существование блога
         */
        if (!$oBlog = $this->Blog_GetBlogById($sBlogId) or !is_array($aUsers)) {
            return $this->EventErrorDebug();
        }
        /**
         * Проверяем тип блога
         */
        if ($oBlog->getType() != 'close') {
            return $this->EventErrorDebug();
        }
        /**
         * Проверяем, имеет ли право текущий пользователь добавлять invite в blog
         */
        $oBlogUser = $this->Blog_GetBlogUserByBlogIdAndUserId($oBlog->getId(), $this->oUserCurrent->getId());
        $bIsAdministratorBlog = $oBlogUser ? $oBlogUser->getIsAdministrator() : false;
        if ($oBlog->getOwnerId() != $this->oUserCurrent->getId() and !$this->oUserCurrent->isAdministrator() and !$bIsAdministratorBlog) {
            return $this->EventErrorDebug();
        }

        $aResult = array();
        /**
         * Обрабатываем добавление по каждому из переданных логинов
         */
        foreach ($aUsers as $sUser) {
            $sUser = trim($sUser);
            if ($sUser == '') {
                continue;
            }
            /**
             * Если пользователь не найден или неактивен,
             * возвращаем ошибку
             */
            if (!$oUser = $this->User_GetUserByLogin($sUser) or $oUser->getActivate() != 1) {
                $aResult[] = array(
                    'bStateError' => true,
                    'sMsgTitle'   => $this->Lang_Get('common.error.error'),
                    'sMsg'        => $this->Lang_Get('user.notices.not_found',
                        array('login' => htmlspecialchars($sUser))),
                    'user_login'  => htmlspecialchars($sUser)
                );
                continue;
            }
            /**
             * Запрещаем отправлять инвайт создателю блога
             */
            if ($oUser->getId() == $oBlog->getOwnerId()) {
                $aResult[] = array(
                    'bStateError' => true,
                    'sMsgTitle'   => $this->Lang_Get('common.error.error'),
                    'sMsg'        => $this->Lang_Get('blog.invite.notices.add_self')
                );
                continue;
            }

            if (!($oBlogUser = $this->Blog_GetBlogUserByBlogIdAndUserId($oBlog->getId(), $oUser->getId()))) {
                /**
                 * Создаем нового блог-пользователя со статусом INVITED
                 */
                $oBlogUserNew = Engine::GetEntity('Blog_BlogUser');
                $oBlogUserNew->setBlogId($oBlog->getId());
                $oBlogUserNew->setUserId($oUser->getId());
                $oBlogUserNew->setUserRole(ModuleBlog::BLOG_USER_ROLE_INVITE);

                if ($this->Blog_AddRelationBlogUser($oBlogUserNew)) {
                    $oViewer = $this->Viewer_GetLocalViewer();
                    $oViewer->Assign('user', $oUser, true);
                    $oViewer->Assign('showActions', true, true);

                    $aResult[] = array(
                        'bStateError' => false,
                        'sMsgTitle'   => $this->Lang_Get('common.attention'),
                        'sMsg'        => $this->Lang_Get('blog.invite.notices.add',
                            array('login' => htmlspecialchars($sUser))),
                        'user_id'     => $oUser->getId(),
                        'user_login'  => htmlspecialchars($sUser),
                        'html'        => $oViewer->Fetch("component@blog.invite-item")
                    );
                    $this->SendBlogInvite($oBlog, $oUser);
                } else {
                    $aResult[] = array(
                        'bStateError' => true,
                        'sMsgTitle'   => $this->Lang_Get('common.error.error'),
                        'sMsg'        => $this->Lang_Get('common.error.system.base'),
                        'user_login'  => htmlspecialchars($sUser)
                    );
                }
            } else {
                /**
                 * Попытка добавить приглашение уже существующему пользователю,
                 * возвращаем ошибку (сначала определяя ее точный текст)
                 */
                switch (true) {
                    case ($oBlogUser->getUserRole() == ModuleBlog::BLOG_USER_ROLE_INVITE):
                        $sErrorMessage = $this->Lang_Get('blog.invite.notices.already_invited',
                            array('login' => htmlspecialchars($sUser)));
                        break;
                    case ($oBlogUser->getUserRole() > ModuleBlog::BLOG_USER_ROLE_GUEST):
                        $sErrorMessage = $this->Lang_Get('blog.invite.notices.already_joined',
                            array('login' => htmlspecialchars($sUser)));
                        break;
                    case ($oBlogUser->getUserRole() == ModuleBlog::BLOG_USER_ROLE_REJECT):
                        $sErrorMessage = $this->Lang_Get('blog.invite.notices.reject',
                            array('login' => htmlspecialchars($sUser)));
                        break;
                    default:
                        $sErrorMessage = $this->Lang_Get('common.error.system.base');
                }
                $aResult[] = array(
                    'bStateError' => true,
                    'sMsgTitle'   => $this->Lang_Get('common.error.error'),
                    'sMsg'        => $sErrorMessage,
                    'user_login'  => htmlspecialchars($sUser)
                );
                continue;
            }
        }
        /**
         * Передаем во вьевер массив с результатами обработки по каждому пользователю
         */
        $this->Viewer_AssignAjax('users', $aResult);
    }

    /**
     * Обработка ajax запроса на отправку
     * повторного приглашения вступить в закрытый блог
     */
    protected function AjaxReBlogInvite()
    {
        /**
         * Устанавливаем формат Ajax ответа
         */
        $this->Viewer_SetResponseAjax('json');
        $sUserId = getRequestStr('user_id', null, 'post');
        $sBlogId = getRequestStr('target_id', null, 'post');
        /**
         * Если пользователь не авторизирован, возвращаем ошибку
         */
        if (!$this->User_IsAuthorization()) {
            $this->Message_AddErrorSingle($this->Lang_Get('common.error.need_authorization'), $this->Lang_Get('common.error.error'));
            return;
        }
        $this->oUserCurrent = $this->User_GetUserCurrent();
        /**
         * Проверяем существование блога
         */
        if (!$oBlog = $this->Blog_GetBlogById($sBlogId)) {
            return $this->EventErrorDebug();
        }
        /**
         * Пользователь существует и активен?
         */
        if (!$oUser = $this->User_GetUserById($sUserId) or $oUser->getActivate() != 1) {
            return $this->EventErrorDebug();
        }
        /**
         * Проверяем, имеет ли право текущий пользователь добавлять invite в blog
         */
        $oBlogUser = $this->Blog_GetBlogUserByBlogIdAndUserId($oBlog->getId(), $this->oUserCurrent->getId());
        $bIsAdministratorBlog = $oBlogUser ? $oBlogUser->getIsAdministrator() : false;
        if ($oBlog->getOwnerId() != $this->oUserCurrent->getId() and !$this->oUserCurrent->isAdministrator() and !$bIsAdministratorBlog) {
            return $this->EventErrorDebug();
        }
        /**
         * Попытка отправить инвайт пользователю, который не состоит в данном блоге
         */
        if (!($oBlogUser = $this->Blog_GetBlogUserByBlogIdAndUserId($oBlog->getId(), $oUser->getId()))) {
            return $this->EventErrorDebug();
        }
        if ($oBlogUser->getUserRole() == ModuleBlog::BLOG_USER_ROLE_INVITE) {
            $this->SendBlogInvite($oBlog, $oUser);
            $this->Message_AddNoticeSingle($this->Lang_Get('blog.invite.notices.add',
                array('login' => $oUser->getLogin())), $this->Lang_Get('common.attention'));
        } else {
            return $this->EventErrorDebug();
        }
    }

    /**
     * Обработка ajax запроса на удаление вступить в закрытый блог
     */
    protected function AjaxRemoveBlogInvite()
    {
        /**
         * Устанавливаем формат Ajax ответа
         */
        $this->Viewer_SetResponseAjax('json');
        $sUserId = getRequestStr('user_id', null, 'post');
        $sBlogId = getRequestStr('target_id', null, 'post');
        /**
         * Если пользователь не авторизирован, возвращаем ошибку
         */
        if (!$this->User_IsAuthorization()) {
            $this->Message_AddErrorSingle($this->Lang_Get('common.error.need_authorization'), $this->Lang_Get('common.error.error'));
            return;
        }
        $this->oUserCurrent = $this->User_GetUserCurrent();
        /**
         * Проверяем существование блога
         */
        if (!$oBlog = $this->Blog_GetBlogById($sBlogId)) {
            return $this->EventErrorDebug();
        }
        /**
         * Пользователь существует и активен?
         */
        if (!$oUser = $this->User_GetUserById($sUserId) or $oUser->getActivate() != 1) {
            return $this->EventErrorDebug();
        }
        /**
         * Проверяем, имеет ли право текущий пользователь добавлять invite в blog
         */
        $oBlogUser = $this->Blog_GetBlogUserByBlogIdAndUserId($oBlog->getId(), $this->oUserCurrent->getId());
        $bIsAdministratorBlog = $oBlogUser ? $oBlogUser->getIsAdministrator() : false;
        if ($oBlog->getOwnerId() != $this->oUserCurrent->getId() and !$this->oUserCurrent->isAdministrator() and !$bIsAdministratorBlog) {
            return $this->EventErrorDebug();
        }

        $oBlogUser = $this->Blog_GetBlogUserByBlogIdAndUserId($oBlog->getId(), $oUser->getId());
        if ($oBlogUser->getUserRole() == ModuleBlog::BLOG_USER_ROLE_INVITE) {
            /**
             * Удаляем связь/приглашение
             */
            $this->Blog_DeleteRelationBlogUser($oBlogUser);
            $this->Message_AddNoticeSingle($this->Lang_Get('blog.invite.notices.remove',
                array('login' => $oUser->getLogin())), $this->Lang_Get('common.attention'));
        } else {
            return $this->EventErrorDebug();
        }
    }

    /**
     * Выполняет отправку приглашения в блог
     * (по внутренней почте и на email)
     *
     * @param ModuleBlog_EntityBlog $oBlog
     * @param ModuleUser_EntityUser $oUser
     */
    protected function SendBlogInvite($oBlog, $oUser)
    {
        $sTitle = $this->Lang_Get(
            'blog.invite.email.title',
            array(
                'blog_title' => $oBlog->getTitle()
            )
        );

        require_once Config::Get('path.framework.libs_vendor.server') . '/XXTEA/encrypt.php';
        /**
         * Формируем код подтверждения в URL
         */
        $sCode = $oBlog->getId() . '_' . $oUser->getId();
        $sCode = rawurlencode(base64_encode(xxtea_encrypt($sCode, Config::Get('module.blog.encrypt'))));

        $aPath = array(
            'accept' => Router::GetPath('blog') . 'invite/accept/?code=' . $sCode,
            'reject' => Router::GetPath('blog') . 'invite/reject/?code=' . $sCode
        );

        $sText = $this->Lang_Get(
            'blog.invite.email.text',
            array(
                'login'       => $this->oUserCurrent->getLogin(),
                'accept_path' => $aPath['accept'],
                'reject_path' => $aPath['reject'],
                'blog_title'  => $oBlog->getTitle()
            )
        );
        $oTalk = $this->Talk_SendTalk($sTitle, $sText, $this->oUserCurrent, array($oUser), false, false);
        /**
         * Отправляем пользователю заявку
         */
        $this->Blog_SendNotifyBlogUserInvite(
            $oUser, $this->oUserCurrent, $oBlog,
            Router::GetPath('talk') . 'read/' . $oTalk->getId() . '/'
        );
        /**
         * Удаляем отправляющего юзера из переписки
         */
        $this->Talk_DeleteTalkUserByArray($oTalk->getId(), $this->oUserCurrent->getId());
    }

    /**
     * Обработка отправленого пользователю приглашения вступить в блог
     */
    protected function EventInviteBlog()
    {
        require_once Config::Get('path.framework.libs_vendor.server') . '/XXTEA/encrypt.php';
        /**
         * Получаем код подтверждения из ревеста и дешефруем его
         */
        $sCode = xxtea_decrypt(base64_decode(rawurldecode(getRequestStr('code'))), Config::Get('module.blog.encrypt'));
        if (!$sCode) {
            return $this->EventNotFound();
        }
        list($sBlogId, $sUserId) = explode('_', $sCode, 2);

        $sAction = $this->GetParam(0);
        /**
         * Получаем текущего пользователя
         */
        if (!$this->User_IsAuthorization()) {
            return $this->EventNotFound();
        }
        $this->oUserCurrent = $this->User_GetUserCurrent();
        /**
         * Если приглашенный пользователь не является авторизированным
         */
        if ($this->oUserCurrent->getId() != $sUserId) {
            return $this->EventNotFound();
        }
        /**
         * Получаем указанный блог
         */
        if ((!$oBlog = $this->Blog_GetBlogById($sBlogId)) || $oBlog->getType() != 'close') {
            return $this->EventNotFound();
        }
        /**
         * Получаем связь "блог-пользователь" и проверяем,
         * чтобы ее тип был INVITE или REJECT
         */
        if (!$oBlogUser = $this->Blog_GetBlogUserByBlogIdAndUserId($oBlog->getId(), $this->oUserCurrent->getId())) {
            return $this->EventNotFound();
        }
        if ($oBlogUser->getUserRole() > ModuleBlog::BLOG_USER_ROLE_GUEST) {
            $sMessage = $this->Lang_Get('blog.invite.alerts.already_joined');
            $this->Message_AddError($sMessage, $this->Lang_Get('common.error.error'), true);
            Router::Location(Router::GetPath('talk'));
            return;
        }
        if (!in_array($oBlogUser->getUserRole(),
            array(ModuleBlog::BLOG_USER_ROLE_INVITE, ModuleBlog::BLOG_USER_ROLE_REJECT))
        ) {
            $this->Message_AddError($this->Lang_Get('common.error.system.base'), $this->Lang_Get('common.error.error'), true);
            Router::Location(Router::GetPath('talk'));
            return;
        }
        /**
         * Обновляем роль пользователя до читателя
         */
        $oBlogUser->setUserRole(($sAction == 'accept') ? ModuleBlog::BLOG_USER_ROLE_USER : ModuleBlog::BLOG_USER_ROLE_REJECT);
        if (!$this->Blog_UpdateRelationBlogUser($oBlogUser)) {
            $this->Message_AddError($this->Lang_Get('common.error.system.base'), $this->Lang_Get('common.error.error'), true);
            Router::Location(Router::GetPath('talk'));
            return;
        }
        if ($sAction == 'accept') {
            /**
             * Увеличиваем число читателей блога
             */
            $oBlog->setCountUser($oBlog->getCountUser() + 1);
            $this->Blog_UpdateBlog($oBlog);
            $sMessage = $this->Lang_Get('blog.invite.alerts.accepted');
            /**
             * Добавляем событие в ленту
             */
            $this->Stream_write($oBlogUser->getUserId(), 'join_blog', $oBlog->getId());
        } else {
            $sMessage = $this->Lang_Get('blog.invite.alerts.rejected');
        }
        $this->Message_AddNotice($sMessage, $this->Lang_Get('common.attention'), true);
        /**
         * Перенаправляем на страницу личной почты
         */
        Router::Location(Router::GetPath('talk'));
    }

    /**
     * Удаление блога
     *
     */
    protected function EventDeleteBlog()
    {
        $this->Security_ValidateSendForm();
        /**
         * Проверяем передан ли в УРЛе номер блога
         */
        $sBlogId = $this->GetParam(0);
        if (!$oBlog = $this->Blog_GetBlogById($sBlogId)) {
            return parent::EventNotFound();
        }
        /**
         * Проверям авторизован ли пользователь
         */
        if (!$this->User_IsAuthorization()) {
            $this->Message_AddErrorSingle($this->Lang_Get('common.error.not_access'), $this->Lang_Get('common.error.error'));
            return Router::Action('error');
        }
        /**
         * проверяем есть ли право на удаление топика
         */
        if (!$bAccess = $this->ACL_IsAllowDeleteBlog($oBlog, $this->oUserCurrent)) {
            return parent::EventNotFound();
        }
        $aTopics = $this->Topic_GetTopicsByBlogId($sBlogId, 1, 1, array(),
            false); // нужно переделать функционал переноса топиков в дргугой блог
        switch ($bAccess) {
            case ModuleACL::CAN_DELETE_BLOG_EMPTY_ONLY :
                if ($aTopics['count']) {
                    $this->Message_AddErrorSingle($this->Lang_Get('blog.remove.alerts.not_empty'),
                        $this->Lang_Get('common.error.error'), true);
                    Router::Location($oBlog->getUrlFull());
                }
                break;
            case ModuleACL::CAN_DELETE_BLOG_WITH_TOPICS :
                /**
                 * Если указан идентификатор блога для перемещения,
                 * то делаем попытку переместить топики.
                 *
                 * (-1) - выбран пункт меню "удалить топики".
                 */
                if ($sBlogIdNew = getRequestStr('topic_move_to') and ($sBlogIdNew != -1) and $aTopics['count']) {
                    if (!$oBlogNew = $this->Blog_GetBlogById($sBlogIdNew)) {
                        $this->Message_AddErrorSingle($this->Lang_Get('blog.remove.alerts.move_error'),
                            $this->Lang_Get('common.error.error'), true);
                        Router::Location($oBlog->getUrlFull());
                    }
                    /**
                     * Если выбранный блог является персональным, возвращаем ошибку
                     */
                    if ($oBlogNew->getType() == 'personal') {
                        $this->Message_AddErrorSingle($this->Lang_Get('blog.remove.alerts.move_personal_error'),
                            $this->Lang_Get('common.error.error'), true);
                        Router::Location($oBlog->getUrlFull());
                    }
                    /**
                     * Перемещаем топики
                     */
                    $this->Topic_MoveTopics($sBlogId, $sBlogIdNew);
                }
                break;
            default:
                return parent::EventNotFound();
        }
        /**
         * Удаляяем блог и перенаправляем пользователя к списку блогов
         */
        $this->Hook_Run('blog_delete_before', array('sBlogId' => $sBlogId));
        if ($this->Blog_DeleteBlog($sBlogId)) {
            $this->Hook_Run('blog_delete_after', array('sBlogId' => $sBlogId));
            $this->Message_AddNoticeSingle($this->Lang_Get('blog.remove.alerts.success'), $this->Lang_Get('common.attention'),
                true);
            Router::Location(Router::GetPath('blogs'));
        } else {
            Router::Location($oBlog->getUrlFull());
        }
    }

    /**
     * Получение описания блога
     *
     */
    protected function AjaxBlogInfo()
    {
        /**
         * Устанавливаем формат Ajax ответа
         */
        $this->Viewer_SetResponseAjax('json');
        $sBlogId = getRequestStr('blog_id', null, 'post');
        /**
         * Определяем тип блога и получаем его
         */
        if ($sBlogId == 0) {
            if ($this->oUserCurrent) {
                $oBlog = $this->Blog_GetPersonalBlogByUserId($this->oUserCurrent->getId());
            }
        } else {
            $oBlog = $this->Blog_GetBlogById($sBlogId);
        }
        /**
         * если блог найден, то возвращаем описание
         */
        if (isset($oBlog)) {
            $sText = $oBlog->getDescription();

            /**
             * если блог персональный — возвращаем текущий языковой эквивалент
             */
            if ($sBlogId == 0) {
                $sText = $this->Lang_Get('blog.personal_description');
            }
            $this->Viewer_AssignAjax('text', $sText);
        } else {
            return $this->EventErrorDebug();
        }
    }

    /**
     * Подключение/отключение к блогу
     *
     */
    protected function AjaxBlogJoin()
    {
        /**
         * Устанавливаем формат Ajax ответа
         */
        $this->Viewer_SetResponseAjax('json');
        /**
         * Пользователь авторизован?
         */
        if (!$this->oUserCurrent) {
            $this->Message_AddErrorSingle($this->Lang_Get('common.error.need_authorization'), $this->Lang_Get('common.error.error'));
            return;
        }
        /**
         * Блог существует?
         */
        $idBlog = getRequestStr('blog_id', null, 'post');
        if (!($oBlog = $this->Blog_GetBlogById($idBlog))) {
            return $this->EventErrorDebug();
        }
        /**
         * Проверяем тип блога
         */
        if (!in_array($oBlog->getType(), array('open', 'close'))) {
            $this->Message_AddErrorSingle($this->Lang_Get('blog.join.notices.error_invite'), $this->Lang_Get('common.error.error'));
            return;
        }
        /**
         * Получаем текущий статус пользователя в блоге
         */
        $oBlogUser = $this->Blog_GetBlogUserByBlogIdAndUserId($oBlog->getId(), $this->oUserCurrent->getId());
        if (!$oBlogUser || ($oBlogUser->getUserRole() < ModuleBlog::BLOG_USER_ROLE_GUEST && $oBlog->getType() == 'close')) {
            if ($oBlog->getOwnerId() != $this->oUserCurrent->getId()) {
                /**
                 * Присоединяем юзера к блогу
                 */
                $bResult = false;
                if ($oBlogUser) {
                    $oBlogUser->setUserRole(ModuleBlog::BLOG_USER_ROLE_USER);
                    $bResult = $this->Blog_UpdateRelationBlogUser($oBlogUser);
                } elseif ($oBlog->getType() == 'open') {
                    $oBlogUserNew = Engine::GetEntity('Blog_BlogUser');
                    $oBlogUserNew->setBlogId($oBlog->getId());
                    $oBlogUserNew->setUserId($this->oUserCurrent->getId());
                    $oBlogUserNew->setUserRole(ModuleBlog::BLOG_USER_ROLE_USER);
                    $bResult = $this->Blog_AddRelationBlogUser($oBlogUserNew);
                }
                if ($bResult) {
                    $this->Message_AddNoticeSingle($this->Lang_Get('blog.join.notices.join_success'),
                        $this->Lang_Get('common.attention'));
                    $this->Viewer_AssignAjax('bState', true);
                    /**
                     * Увеличиваем число читателей блога
                     */
                    $oBlog->setCountUser($oBlog->getCountUser() + 1);
                    $this->Blog_UpdateBlog($oBlog);
                    $this->Viewer_AssignAjax('iCountUser', $oBlog->getCountUser());
                    /**
                     * Добавляем событие в ленту
                     */
                    $this->Stream_write($this->oUserCurrent->getId(), 'join_blog', $oBlog->getId());
                    /**
                     * Добавляем подписку на этот блог в ленту пользователя
                     */
                    $this->Userfeed_subscribeUser($this->oUserCurrent->getId(), ModuleUserfeed::SUBSCRIBE_TYPE_BLOG,
                        $oBlog->getId());
                } else {
                    $sMsg = ($oBlog->getType() == 'close')
                        ? $this->Lang_Get('blog.join.notices.error_invite')
                        : $this->Lang_Get('common.error.system.base');
                    $this->Message_AddErrorSingle($sMsg, $this->Lang_Get('common.error.error'));
                    return;
                }
            } else {
                $this->Message_AddErrorSingle($this->Lang_Get('blog.join.notices.error_self'),
                    $this->Lang_Get('common.attention'));
                return;
            }
        }
        if ($oBlogUser && $oBlogUser->getUserRole() > ModuleBlog::BLOG_USER_ROLE_GUEST) {
            /**
             * Покидаем блог
             */
            if ($this->Blog_DeleteRelationBlogUser($oBlogUser)) {
                $this->Message_AddNoticeSingle($this->Lang_Get('blog.join.notices.leave_success'),
                    $this->Lang_Get('common.attention'));
                $this->Viewer_AssignAjax('bState', false);
                /**
                 * Уменьшаем число читателей блога
                 */
                $oBlog->setCountUser($oBlog->getCountUser() - 1);
                $this->Blog_UpdateBlog($oBlog);
                $this->Viewer_AssignAjax('iCountUser', $oBlog->getCountUser());
                /**
                 * Удаляем подписку на этот блог в ленте пользователя
                 */
                $this->Userfeed_unsubscribeUser($this->oUserCurrent->getId(), ModuleUserfeed::SUBSCRIBE_TYPE_BLOG,
                    $oBlog->getId());
            } else {
                return $this->EventErrorDebug();
            }
        }
    }

    /**
     * Загрузка аватара в блог
     */
    protected function EventAjaxUploadAvatar()
    {
        /**
         * Устанавливаем формат Ajax ответа
         */
        $this->Viewer_SetResponseAjax('jsonIframe', false);
        if (!isset($_FILES['photo']['tmp_name'])) {
            return $this->EventErrorDebug();
        }

        if (!$oBlog = $this->Blog_GetBlogById(getRequestStr('target_id'))) {
            return $this->EventErrorDebug();
        }
        if (!$oBlog->isAllowEdit()) {
            return $this->EventErrorDebug();
        }
        /**
         * Копируем загруженный файл
         */
        $sFileTmp = Config::Get('sys.cache.dir') . func_generator();
        if (!move_uploaded_file($_FILES['photo']['tmp_name'], $sFileTmp)) {
            return false;
        }
        /**
         * Если объект изображения не создан, возвращаем ошибку
         */
        if (!$oImage = $this->Image_Open($sFileTmp)) {
            $this->Fs_RemoveFileLocal($sFileTmp);
            $this->Message_AddError($this->Image_GetLastError());
            return;
        }
        /**
         * Ресайзим и сохраняем именьшенную копию
         * Храним две копии - мелкую для показа пользователю и крупную в качестве исходной для ресайза
         */
        $sDir = Config::Get('path.uploads.images') . "/tmp/blog/{$oBlog->getId()}";
        if ($sFileOriginal = $oImage->resize(1000, null)->saveSmart($sDir, 'original')) {
            if ($sFilePreview = $oImage->resize(350, null)->saveSmart($sDir, 'preview')) {
                list($iOriginalWidth, $iOriginalHeight) = @getimagesize($this->Fs_GetPathServer($sFileOriginal));
                list($iWidth, $iHeight) = @getimagesize($this->Fs_GetPathServer($sFilePreview));
                /**
                 * Сохраняем в сессии временный файл с изображением
                 */
                $this->Session_Set('sBlogAvatarFileTmp', $sFileOriginal);
                $this->Session_Set('sBlogAvatarFilePreviewTmp', $sFilePreview);
                $this->Viewer_AssignAjax('path', $this->Fs_GetPathWeb($sFilePreview));
                $this->Viewer_AssignAjax('original_width', $iOriginalWidth);
                $this->Viewer_AssignAjax('original_height', $iOriginalHeight);
                $this->Viewer_AssignAjax('width', $iWidth);
                $this->Viewer_AssignAjax('height', $iHeight);
                $this->Fs_RemoveFileLocal($sFileTmp);
                return;
            }
        }
        $this->Message_AddError($this->Image_GetLastError());
        $this->Fs_RemoveFileLocal($sFileTmp);
    }

    /**
     * Обрезка аватара блога
     */
    protected function EventAjaxCropAvatar()
    {
        /**
         * Устанавливаем формат Ajax ответа
         */
        $this->Viewer_SetResponseAjax('json');

        if (!$oBlog = $this->Blog_GetBlogById(getRequestStr('target_id'))) {
            return $this->EventErrorDebug();
        }
        if (!$oBlog->isAllowEdit()) {
            return $this->EventErrorDebug();
        }

        $sFile = $this->Session_Get('sBlogAvatarFileTmp');
        $sFilePreview = $this->Session_Get('sBlogAvatarFilePreviewTmp');
        if (!$this->Image_IsExistsFile($sFile)) {
            $this->Message_AddErrorSingle($this->Lang_Get('common.error.system.base'));
            return;
        }

        if (true === ($res = $this->Blog_CreateAvatar($sFile, $oBlog, getRequest('size'),
                getRequestStr('canvas_width')))
        ) {
            $this->Image_RemoveFile($sFile);
            $this->Image_RemoveFile($sFilePreview);
            $this->Session_Drop('sBlogAvatarFileTmp');
            $this->Session_Drop('sBlogAvatarFilePreviewTmp');

            $this->Viewer_AssignAjax('upload_text', $this->Lang_Get('user.photo.actions.change_photo'));
            $this->Viewer_AssignAjax('photo', $oBlog->getAvatarPath('500crop'));
        } else {
            $this->Message_AddError(is_string($res) ? $res : $this->Lang_Get('common.error.error'));
        }
    }

    /**
     * Удаляет временные файлы кропа аватара
     */
    protected function EventAjaxCropCancelAvatar()
    {
        /**
         * Устанавливаем формат Ajax ответа
         */
        $this->Viewer_SetResponseAjax('json');

        if (!$oBlog = $this->Blog_GetBlogById(getRequestStr('target_id'))) {
            return $this->EventErrorDebug();
        }
        if (!$oBlog->isAllowEdit()) {
            return $this->EventErrorDebug();
        }

        $sFile = $this->Session_Get('sBlogAvatarFileTmp');
        $sFilePreview = $this->Session_Get('sBlogAvatarFilePreviewTmp');

        $this->Image_RemoveFile($sFile);
        $this->Image_RemoveFile($sFilePreview);
        $this->Session_Drop('sBlogAvatarFileTmp');
        $this->Session_Drop('sBlogAvatarFilePreviewTmp');
    }

    /**
     * Удаление аватара блога
     */
    protected function EventAjaxRemoveAvatar()
    {
        $this->Viewer_SetResponseAjax('json');

        if (!$oBlog = $this->Blog_GetBlogById(getRequestStr('target_id'))) {
            return $this->EventErrorDebug();
        }
        if (!$oBlog->isAllowEdit()) {
            return $this->EventErrorDebug();
        }

        $this->Blog_DeleteBlogAvatar($oBlog);
        $this->Blog_UpdateBlog($oBlog);

        $this->Viewer_AssignAjax('upload_text', $this->Lang_Get('user.photo.actions.upload_photo'));
        $this->Viewer_AssignAjax('photo', $oBlog->getAvatarPath('500crop'));
        $this->Viewer_AssignAjax('avatars', $oBlog->GetProfileAvatarsPath());
    }

    /**
     * Показывает модальное окно с кропом аватара
     */
    protected function EventAjaxModalCropAvatar()
    {
        $this->Viewer_SetResponseAjax('json');

        $oViewer = $this->Viewer_GetLocalViewer();

        $oViewer->Assign('image', getRequestStr('path'), true);
        $oViewer->Assign('originalWidth', (int)getRequest('original_width'), true);
        $oViewer->Assign('originalHeight', (int)getRequest('original_height'), true);
        $oViewer->Assign('width', (int)getRequest('width'), true);
        $oViewer->Assign('height', (int)getRequest('height'), true);

        $this->Viewer_AssignAjax('sText', $oViewer->Fetch("component@blog.modal.crop-avatar"));
    }

    /**
     * Выполняется при завершении работы экшена
     *
     */
    public function EventShutdown()
    {
        /**
         * Загружаем в шаблон необходимые переменные
         */
        $this->Viewer_Assign('sMenuHeadItemSelect', $this->sMenuHeadItemSelect);
        $this->Viewer_Assign('sMenuItemSelect', $this->sMenuItemSelect);
        $this->Viewer_Assign('sMenuSubItemSelect', $this->sMenuSubItemSelect);
        $this->Viewer_Assign('sMenuSubBlogUrl', $this->sMenuSubBlogUrl);
        $this->Viewer_Assign('iCountTopicsCollectiveNew', $this->iCountTopicsCollectiveNew);
        $this->Viewer_Assign('iCountTopicsPersonalNew', $this->iCountTopicsPersonalNew);
        $this->Viewer_Assign('iCountTopicsBlogNew', $this->iCountTopicsBlogNew);
        $this->Viewer_Assign('iCountTopicsNew', $this->iCountTopicsNew);
        $this->Viewer_Assign('iCountTopicsSubNew', $this->iCountTopicsSubNew);
        $this->Viewer_Assign('sNavTopicsSubUrl', $this->sNavTopicsSubUrl);

        $this->Viewer_Assign('BLOG_USER_ROLE_GUEST', ModuleBlog::BLOG_USER_ROLE_GUEST);
        $this->Viewer_Assign('BLOG_USER_ROLE_USER', ModuleBlog::BLOG_USER_ROLE_USER);
        $this->Viewer_Assign('BLOG_USER_ROLE_MODERATOR', ModuleBlog::BLOG_USER_ROLE_MODERATOR);
        $this->Viewer_Assign('BLOG_USER_ROLE_ADMINISTRATOR', ModuleBlog::BLOG_USER_ROLE_ADMINISTRATOR);
        $this->Viewer_Assign('BLOG_USER_ROLE_INVITE', ModuleBlog::BLOG_USER_ROLE_INVITE);
        $this->Viewer_Assign('BLOG_USER_ROLE_REJECT', ModuleBlog::BLOG_USER_ROLE_REJECT);
        $this->Viewer_Assign('BLOG_USER_ROLE_BAN', ModuleBlog::BLOG_USER_ROLE_BAN);
    }
}