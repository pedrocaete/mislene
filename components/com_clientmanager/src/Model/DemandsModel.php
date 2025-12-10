<?php

namespace Cm\Component\Clientmanager\Site\Model;

use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;

defined('_JEXEC') or die;

class DemandsModel extends ListModel
{
    public function __construct($config = [], ?MVCFactoryInterface $factory = null)
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = [
                'id',
                'a.id',
                'titulo',
                'a.titulo',
                'cliente_nome',
                'c.nome_completo',
                'created',
                'a.created',
                'tipo_demanda',
                'a.tipo_demanda',
                'status',
                'a.status',
                'date_start',
                'date_end'
            ];
        }

        parent::__construct($config, $factory);
    }

    protected function populateState($ordering = null, $direction = null)
    {
        $app = Factory::getApplication();
        $input = $app->input;

        $search = $input->get('filter_search', '', 'string');
        $this->setState('filter.search', $search);

        $type = $input->get('filter_tipo_demanda', '', 'string');
        $this->setState('filter.tipo_demanda', $type);

        $status = $input->get('filter_status', '', 'string');
        $this->setState('filter.status', $status);

        $dateStart = $input->get('filter_date_start', '', 'string');
        $this->setState('filter.date_start', $dateStart);

        $dateEnd = $input->get('filter_date_end', '', 'string');
        $this->setState('filter.date_end', $dateEnd);

        $sortDeadline = $input->get('filter_sort_deadline', 0, 'int');
        $this->setState('filter.sort_deadline', $sortDeadline);

        $this->setState('list.limit', 15);
        $value = $input->get('limitstart', 0, 'uint');
        $this->setState('list.start', $value);

        if ($sortDeadline == 1) {
            $this->setState('list.ordering', 'a.data_conclusao');
            $this->setState('list.direction', 'ASC');
        } else {
            $this->setState('list.ordering', 'a.id');
            $this->setState('list.direction', 'DESC');
        }
    }

    protected function getListQuery()
    {
        $db = $this->getDatabase();
        $query = $db->getQuery(true);

        $query->select(
            $this->getState(
                'list.select',
                [
                    $db->quoteName('a.id'),
                    $db->quoteName('a.titulo'),
                    $db->quoteName('c.nome_completo', 'cliente_nome'),
                    $db->quoteName('a.created'),
                    $db->quoteName('a.data_conclusao'),
                    $db->quoteName('a.tipo_demanda'),
                    $db->quoteName('a.status'),
                ]
            )
        );

        $query->from($db->quoteName('#__clientmanager_demands', 'a'));

        $query->join(
            'LEFT',
            $db->quoteName('#__clientmanager_clients', 'c')
            . ' ON ' . $db->quoteName('c.id') . ' = ' . $db->quoteName('a.cliente_id')
        );

        $type = $this->getState('filter.tipo_demanda');
        if (!empty($type)) {
            $query->where($db->quoteName('a.tipo_demanda') . ' = ' . $db->quote($type));
        }

        $status = $this->getState('filter.status');
        if (is_numeric($status)) {
            $query->where($db->quoteName('a.status') . ' = ' . (int) $status);
        }

        $dateStart = $this->getState('filter.date_start');
        if (!empty($dateStart)) {
            $query->where($db->quoteName('a.created') . ' >= ' . $db->quote($dateStart . ' 00:00:00'));
        }

        $dateEnd = $this->getState('filter.date_end');
        if (!empty($dateEnd)) {
            $query->where($db->quoteName('a.created') . ' <= ' . $db->quote($dateEnd . ' 23:59:59'));
        }

        $search = $this->getState('filter.search');
        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where($db->quoteName('a.id') . ' = ' . (int) substr($search, 3));
            } else {
                $search = $db->quote('%' . str_replace(' ', '%', $db->escape(trim($search), true) . '%'));
                $query->where(
                    '(' . $db->quoteName('c.nome_completo') . ' LIKE ' . $search .
                    ' OR ' . $db->quoteName('a.titulo') . ' LIKE ' . $search . ')'
                );
            }
        }

        $orderCol = $this->getState('list.ordering', 'a.id');
        $orderDirn = $this->getState('list.direction', 'DESC');

        if (!in_array($orderCol, ['a.id', 'a.created', 'a.status', 'c.nome_completo'])) {
            $orderCol = 'a.id';
        }

        $query->order($db->quoteName($orderCol) . ' ' . $orderDirn);

        return $query;
    }

    public function getDemandTypes()
    {
        $db = $this->getDatabase();
        $query = $db->getQuery(true);

        $query->select('DISTINCT ' . $db->quoteName('tipo_demanda', 'value'))
            ->select($db->quoteName('tipo_demanda', 'text'))
            ->from($db->quoteName('#__clientmanager_demands'))
            ->where($db->quoteName('tipo_demanda') . ' IS NOT NULL')
            ->where($db->quoteName('tipo_demanda') . ' != ' . $db->quote(''))
            ->order($db->quoteName('tipo_demanda') . ' ASC');

        $db->setQuery($query);

        return $db->loadObjectList();
    }
}
