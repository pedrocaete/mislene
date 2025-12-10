<?php

namespace Cm\Component\Clientmanager\Site\Model;

use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\Factory;
use Joomla\CMS\Table\Table;

defined('_JEXEC') or die;

class DemandModel extends AdminModel
{
    public function getTable($type = 'DemandTable', $prefix = 'Cm\Component\Clientmanager\Site\Table\\', $config = [])
    {
        return Table::getInstance($type, $prefix, $config);
    }

    public function getForm($data = [], $loadData = true)
    {
        $form = $this->loadForm(
            'com_clientmanager.demand',
            'demand',
            ['control' => 'jform', 'load_data' => $loadData]
        );

        if (empty($form)) {
            return false;
        }

        return $form;
    }

    protected function loadFormData()
    {
        $data = Factory::getApplication()->getUserState('com_clientmanager.edit.demand.data', []);

        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    protected function prepareTable($table)
    {

    }

    public function save($data)
    {
        $user = Factory::getUser();

        if (empty($data['id'])) {
            $data['created_by'] = $user->id;
        }

        return parent::save($data);
    }
}
