<?php

/**
 * @package Cm\Component\Clientmanager
 *
 * @copyright Copyright (C) 2025 Pedro Inácio Rodrigues Pontes. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Cm\Component\Clientmanager\Site\Model;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\AdminModel;

\defined('_JEXEC') or die;

class ClientModel extends AdminModel
{
    protected $text_prefix = 'COM_CLIENTMANAGER';

    public function getForm($data = [], $loadData = true)
    {
        $form = $this->loadForm(
            'com_clientmanager.client',
            'client',
            ['control' => 'jform', 'load_data' => $loadData]
        );

        if (empty($form)) {
            return false;
        }

        return $form;
    }

    protected function loadFormData()
    {
        $app = Factory::getApplication();
        $data = $app->getUserState('com_clientmanager.edit.client.data', []);

        if (empty($data)) {
            $data = $this->getItem();

            if (!empty($data->id)) {
                $data->children = $this->getClientChildren((int) $data->id);
            }
        }

        return $data;
    }

    public function getItem($pk = null)
    {
        $item = parent::getItem($pk);

        if ($item && !empty($item->id)) {
            $item->children = $this->getClientChildren((int) $item->id);
        }

        return $item;
    }

    public function save($data)
    {
        $childrenData = [];
        if (isset($data['children']) && is_array($data['children'])) {
            $childrenData = $data['children'];
            unset($data['children']);
        }

        if (!parent::save($data)) {
            return false;
        }

        $clientId = (int) $this->getState($this->getName() . '.id');

        if ($clientId > 0) {
            $this->syncChildren($clientId, $childrenData);
        }

        return true;
    }

    protected function syncChildren(int $clientId, array $submittedChildren)
    {
        $db = $this->getDatabase();

        $query = $db->getQuery(true)
            ->select('id')
            ->from($db->quoteName('#__clientmanager_client_children'))
            ->where($db->quoteName('cliente_id') . ' = :clientId')
            ->bind(':clientId', $clientId, \Joomla\Database\ParameterType::INTEGER);
        $existingIds = $db->setQuery($query)->loadColumn();

        $processedIds = [];

        foreach ($submittedChildren as $child) {
            $obj = new \stdClass();
            $obj->cliente_id = $clientId;
            $obj->nome_completo = $child['nome_completo'];
            $obj->data_nascimento = !empty($child['data_nascimento']) ? $child['data_nascimento'] : null;

            if (!empty($child['id']) && in_array($child['id'], $existingIds)) {
                $obj->id = (int) $child['id'];
                $db->updateObject('#__clientmanager_client_children', $obj, 'id');
                $processedIds[] = $obj->id;
            } else {
                $db->insertObject('#__clientmanager_client_children', $obj);
            }
        }

        $idsToDelete = array_diff($existingIds, $processedIds);
        if (!empty($idsToDelete)) {
            $query = $db->getQuery(true)
                ->delete($db->quoteName('#__clientmanager_client_children'))
                ->whereIn($db->quoteName('id'), $idsToDelete);
            $db->setQuery($query)->execute();
        }
    }

    protected function getClientChildren(int $clientId): array
    {
        $db = $this->getDatabase();
        $query = $db->getQuery(true)
            ->select('*')
            ->from($db->quoteName('#__clientmanager_client_children'))
            ->where($db->quoteName('cliente_id') . ' = :clientId')
            ->bind(':clientId', $clientId, \Joomla\Database\ParameterType::INTEGER)
            ->order($db->quoteName('id') . ' ASC');

        return $db->setQuery($query)->loadObjectList();
    }

    public function delete(&$pks)
    {
        $table = $this->getTable();

        foreach ($pks as $pk) {
            if (!$table->delete($pk)) {
                $this->setError($table->getError());
                return false;
            }
        }

        return true;
    }

    protected function populateState()
    {
        $app = Factory::getApplication();
        $id = $app->input->getInt('id', 0);

        $this->setState('item.id', $id);
        $params = $app->getParams();
        $this->setState('params', $params);

        parent::populateState();
    }
}
