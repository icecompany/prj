<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Model\AdminModel;

class PrjModelProject_thematic extends AdminModel {

    public function getItem($pk = null)
    {
        return parent::getItem($pk);
    }

    public function save($data)
    {
        return parent::save($data);
    }

    public function toggle()
    {
        $input = JFactory::getApplication()->input;
        $thematicID = $input->getInt('thematicID', 0);
        $projectID = $input->getInt('projectID', 0);
        if ($thematicID === 0 || $projectID === 0) return false;
        $data = array('thematicID' => $thematicID, 'projectID' => $projectID);
        $table = $this->getTable();
        $table->load($data);
        if ($table->id !== null) {
            return $table->delete($table->id);
        }
        else {
            $data['id'] = null;
            return $table->save($data);
        }
    }

    public function getTable($name = 'Projects_thematics', $prefix = 'TablePrj', $options = array())
    {
        return JTable::getInstance($name, $prefix, $options);
    }

    public function getForm($data = array(), $loadData = true)
    {
        return false;
    }

    protected function loadFormData()
    {
        return false;
    }
}