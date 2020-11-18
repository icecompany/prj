<?php
/**
 * @package    prj
 *
 * @author     Антон <your@email.com>
 * @copyright  A copyright
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @link       http://your.url.com
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\BaseController;

defined('_JEXEC') or die;
// Access check.
if (!Factory::getUser()->authorise('core.manage', 'com_prj'))
{
	throw new InvalidArgumentException(Text::_('JERROR_ALERTNOAUTHOR'), 404);
}

// Require the helper
JFactory::getLanguage()->load('com_mkv', JPATH_ADMINISTRATOR . "/components/com_mkv", 'ru-RU', true);
require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/prj.php';
require_once JPATH_ADMINISTRATOR . '/components/com_scheduler/helpers/scheduler.php';
require_once JPATH_ADMINISTRATOR . '/components/com_mkv/helpers/mkv.php';
// Execute the task
$controller = BaseController::getInstance('prj');
$controller->execute(Factory::getApplication()->input->get('task'));
$controller->redirect();
