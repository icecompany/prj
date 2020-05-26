<?php
defined('_JEXEC') or die;
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldThematics extends JFormFieldList
{
    protected $type = 'Thematics';
    protected $loadExternally = 0;

    protected function getOptions()
    {
        $projectID = PrjHelper::getActiveProject();
        $db =& JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("t.id, t.title")
            ->from('#__mkv_projects_thematics pt')
            ->leftJoin("#__mkv_thematics t on t.id = pt.thematicID")
            ->order("t.weight");
        if (is_numeric($projectID)) {
            $query->where("pt.projectID = {$db->q($projectID)}");
        }
        $result = $db->setQuery($query)->loadObjectList();

        $options = array();

        foreach ($result as $item) {
            $options[] = JHtml::_('select.option', $item->id, $item->title);
        }

        if (!$this->loadExternally) {
            $options = array_merge(parent::getOptions(), $options);
        }

        return $options;
    }

    public function getOptionsExternally()
    {
        $this->loadExternally = 1;
        return $this->getOptions();
    }
}