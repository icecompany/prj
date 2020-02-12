<?php
use Joomla\CMS\HTML\HTMLHelper;

defined('_JEXEC') or die;

class PrjHelper
{
	public function addSubmenu($vName)
	{
		HTMLHelper::_('sidebar.addEntry', JText::sprintf('COM_PRJ_MENU_PROJECTS'), 'index.php?option=com_prj&amp;view=projects', $vName === 'projects');
		HTMLHelper::_('sidebar.addEntry', JText::sprintf('COM_PRJ_MENU_THEMATICS'), 'index.php?option=com_prj&amp;view=thematics', $vName === 'thematics');
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
     * @throws
     */
    public static function getActionUrl(): string
    {
        $uri = JUri::getInstance();
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
