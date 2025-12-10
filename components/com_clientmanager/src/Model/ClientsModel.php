<?php

/**
 * @package     Cm\Component\Clientmanager
 *
 * @copyright   Copyright (C) 2025 Pedro Inácio Rodrigues Pontes. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Cm\Component\Clientmanager\Site\Model;

use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;

\defined('_JEXEC') or die;

class ClientsModel extends ListModel
{
    public function __construct($config = [], ?MVCFactoryInterface $factory = null)
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = [
                'id',
                'a.id',
                'nome_completo',
                'a.nome_completo',
                'bairro',
                'a.bairro',
                'indicado_por',
                'a.indicado_por',
                'aniversariantes',
                'a.data_nascimento',
                'published',
                'a.published'
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

        $bairro = $input->get('filter_bairro', '', 'string');
        $this->setState('filter.bairro', $bairro);

        $indicadoPor = $input->get('filter_indicado_por', '', 'string');
        $this->setState('filter.indicado_por', $indicadoPor);

        $aniversariantes = $input->get('filter_aniversariantes', 0, 'int');
        $this->setState('filter.aniversariantes', $aniversariantes);

        $this->setState('list.limit', 15);
        $value = $input->get('limitstart', 0, 'uint');
        $this->setState('list.start', $value);
        $this->setState('list.ordering', 'a.nome_completo');
        $this->setState('list.direction', 'ASC');
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
                    $db->quoteName('a.nome_completo'),
                    $db->quoteName('a.bairro'),
                    $db->quoteName('a.celular_whatsapp'),
                    $db->quoteName('a.data_nascimento'),
                    $db->quoteName('a.published'),
                    $db->quoteName('i.nome_completo', 'indicador_nome')
                ]
            )
        );

        $query->from($db->quoteName('#__clientmanager_clients', 'a'));

        // Join to get Indicator Name
        $query->join(
            'LEFT',
            $db->quoteName('#__clientmanager_clients', 'i')
            . ' ON ' . $db->quoteName('i.id') . ' = ' . $db->quoteName('a.indicado_por')
        );

        $query->where($db->quoteName('a.published') . ' = 1');

        // Filter by Bairro
        $bairro = $this->getState('filter.bairro');
        if (!empty($bairro)) {
            $query->where($db->quoteName('a.bairro') . ' = ' . $db->quote($bairro));
        }

        // Filter by Indicado Por
        $indicadoPor = $this->getState('filter.indicado_por');
        if (is_numeric($indicadoPor) && $indicadoPor > 0) {
            $query->where($db->quoteName('a.indicado_por') . ' = ' . (int) $indicadoPor);
        }

        // Filter by Aniversariantes (Current Month)
        $aniversariantes = $this->getState('filter.aniversariantes');
        if ($aniversariantes == 1) {
            $currentMonth = date('m');
            $query->where('MONTH(' . $db->quoteName('a.data_nascimento') . ') = ' . $db->quote($currentMonth));
        }

        // Filter by Search
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where($db->quoteName('a.id') . ' = ' . (int) substr($search, 3));
            } else {
                $search = $db->quote('%' . str_replace(' ', '%', $db->escape(trim($search), true) . '%'));
                $query->where(
                    '(' . $db->quoteName('a.nome_completo') . ' LIKE ' . $search .
                    ' OR ' . $db->quoteName('a.cpf') . ' LIKE ' . $search . ')'
                );
            }
        }

        $query->order($db->quoteName('a.nome_completo') . ' ASC');

        return $query;
    }

    public function getBairros()
    {
        $db = $this->getDatabase();
        $query = $db->getQuery(true);

        $query->select('DISTINCT ' . $db->quoteName('bairro'))
            ->from($db->quoteName('#__clientmanager_clients'))
            ->where($db->quoteName('bairro') . ' IS NOT NULL')
            ->where($db->quoteName('bairro') . ' != ' . $db->quote(''))
            ->order($db->quoteName('bairro') . ' ASC');

        $db->setQuery($query);
        return $db->loadColumn();
    }

    public function getIndicadores()
    {
        $db = $this->getDatabase();
        $query = $db->getQuery(true);

        // Get IDs of clients who have indicated someone
        $subQuery = $db->getQuery(true)
            ->select('DISTINCT ' . $db->quoteName('indicado_por'))
            ->from($db->quoteName('#__clientmanager_clients'))
            ->where($db->quoteName('indicado_por') . ' > 0');

        $query->select($db->quoteName('id'))
            ->select($db->quoteName('nome_completo'))
            ->from($db->quoteName('#__clientmanager_clients'))
            ->where($db->quoteName('id') . ' IN (' . $subQuery . ')')
            ->order($db->quoteName('nome_completo') . ' ASC');

        $db->setQuery($query);
        return $db->loadObjectList();
    }
}
