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
require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/prj.php';
// Execute the task
$controller = BaseController::getInstance('prj');
$controller->execute(Factory::getApplication()->input->get('task'));

$controller->redirect();
