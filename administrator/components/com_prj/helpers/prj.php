<?php
use Joomla\CMS\HTML\HTMLHelper;

defined('_JEXEC') or die;

class PrjHelper
{
	public function addSubmenu($vName)
	{
		HTMLHelper::_('sidebar.addEntry', JText::sprintf('COM_PRJ_MENU_PROJECTS'), 'index.php?option=com_prj&amp;view=projects', $vName === 'projects');
		HTMLHelper::_('sidebar.addEntry', JText::sprintf('COM_PRJ_MENU_THEMATICS'), 'index.php?option=com_prj&amp;view=thematics', $vName === 'thematics');
		HTMLHelper::_('sidebar.addEntry', JText::sprintf('COM_PRJ_MENU_FAMILIES'), 'index.php?option=com_prj&amp;view=families', $vName === 'families');
	}

    /**
     * Возвращает ID прерыдущего в семействе проекта
     * @param int $currentID ID текущего проекта
     * @since 2.0.6
     */
    public static function getPreviousProject(int $currentID = 0)
    {
        if ($currentID === 0) {
            $currentID = self::getActiveProject();
            if (!is_numeric($currentID)) return false;
        }
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("id, familyID, date_start")
            ->from("#__mkv_projects")
            ->where("id = {$db->q($currentID)}");
        $current = $db->setQuery($query)->loadAssoc();
        if (!is_numeric($current['familyID'])) return false;

        $query = $db->getQuery(true);
        $query
            ->select("id")
            ->from("#__mkv_projects")
            ->where("familyID = {$db->q($current['familyID'])}")
            ->where("date_start < {$db->q($current['date_start'])}")
            ->order("date_start desc");
        return $db->setQuery($query, 0, 1)->loadResult() ?? false;
	}

    public static function addNotifies()
    {
        $cnt = SchedulerHelper::getNotifiesCount();
        if ($cnt > 0) {
            $text = "<span style='color: red;'>" . JText::sprintf('COM_PRJ_MENU_NOTIFIES_COUNT', $cnt) . "</span>";
            HTMLHelper::_('sidebar.addEntry', $text , 'index.php?option=com_scheduler&amp;view=notifies');
        }
	}

	public function addActiveProjectFilter()
    {
        JHtmlSidebar::addFilter(JText::sprintf("COM_MKV_FILTER_SELECT_ACTIVE_PROJECT"), "set_active_project", JHtml::_("select.options", self::getAvailableProjects(), "value", "text", self::getActiveProject()));
    }

    /**
     * Возвращает ID последней созданной группы пользователей
     * @since 2.0.3
     * @return int ID Группы
     */
    public static function getLastUserGroupID(): int
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select("max(id)")
            ->from("#__usergroups");
        return $db->setQuery($query)->loadResult();
    }

    public function getAvailableProjects()
    {
        $userGroups = implode(', ', JFactory::getUser()->groups);
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`id`, `title`")
            ->from('#__mkv_projects')
            ->order("date_start desc");
        if (!empty($userGroups)) $query->where("groupID in ({$userGroups})");
        $result = $db->setQuery($query)->loadObjectList();

        $options = array();

        foreach ($result as $item) {
            $options[] = JHtml::_('select.option', $item->id, $item->title);
        }

        return $options;
	}

    public static function getActiveProject($default = null)
    {
        $session = JFactory::getSession();
        $project = $session->get('active_project', '');
        return ($project != 0) ? $project : $default;
    }
    

    /**
     * Проверяет необходимость перезагрузить страницу. Используется для возврата на предыдущую страницу при отправке формы в админке
     * @throws Exception
     * @since 1.0.4
     */
    public static function check_refresh(): void
    {
        $refresh = JFactory::getApplication()->input->getBool('refresh', false);
        if ($refresh) {
            $current = JUri::getInstance(self::getCurrentUrl());
            $current->delVar('refresh');
            JFactory::getApplication()->redirect($current);
        }
    }

    /**
     * Возвращает параметр ID из реферера
     * @since 1.0.0
     * @return int ID Элемента
     */
    public static function getItemID(): int
    {
        $uri = JUri::getInstance($_SERVER['HTTP_REFERER']);
        return (int) $uri->getVar('id') ?? 0;
	}

    /**
     * Возвращает URL для обработки формы
     * @return string
     * @since 1.0.0
     * @version 1.0.4-patch.1
     * @throws
     */
    public static function getActionUrl(): string
    {
        $uri = JUri::getInstance();
        $uri->setVar('refresh', '1');
        $query = $uri->getQuery();
        $client = (!JFactory::getApplication()->isClient('administrator')) ? 'site' : 'administrator';
        return JRoute::link($client, "index.php?{$query}");
    }

    /**
     * Возвращает текущий URL
     * @return string
     * @since 1.0.0
     * @throws
     */
    public static function getCurrentUrl(): string
    {
        $uri = JUri::getInstance();
        $query = $uri->getQuery();
        return "index.php?{$query}";
    }

    /**
     * Возвращает URL для возврата (текущий адрес страницы)
     * @return string
     * @since 1.0.0
     */
    public static function getReturnUrl(): string
    {
        $uri = JUri::getInstance();
        $query = $uri->getQuery();
        return base64_encode("index.php?{$query}");
    }

    /**
     * Возвращает URL для обработки формы левой панели
     * @return string
     * @since 1.0.0
     */
    public static function getSidebarAction():string
    {
        $return = self::getReturnUrl();
        return JRoute::_("index.php?return={$return}");
    }

    public static function canDo(string $action): bool
    {
        return JFactory::getUser()->authorise($action, 'com_prj');
    }

    public static function getConfig(string $param, $default = null)
    {
        $config = JComponentHelper::getParams("com_prj");
        return $config->get($param, $default);
    }
}
