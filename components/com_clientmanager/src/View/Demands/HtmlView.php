<?php

/**
 * @package Cm\Component\Clientmanager
 *
 * @copyright Copyright (C) 2025 Pedro Inácio Rodrigues Pontes. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Cm\Component\Clientmanager\Site\View\Demands;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

\defined('_JEXEC') or die;

class HtmlView extends BaseHtmlView
{
    public $items;
    protected $pagination;
    protected $state;
    public $user;

    public function display($tpl = null)
    {
        $this->items = $this->get('Items');
        $this->user = Factory::getApplication()->getIdentity();
        $this->pagination = $this->get('Pagination');
        $this->state = $this->get('State');

        if (count($errors = $this->get('Errors'))) {
            throw new \Exception(implode("\n", $errors), 500);
        }

        if ($this->items) {
            $canDeleteGlobally = $this->user->authorise('core.delete', 'com_clientmanager');
            $canEditGlobally   = $this->user->authorise('core.edit', 'com_clientmanager');

            foreach ($this->items as $item) {
                $item->canEdit = $canEditGlobally;
                $item->canDelete = $canDeleteGlobally;
            }
        }

        $this->statusOptions = $this->getStatusOptions();
        $this->typeOptions   = $this->getTypeOptions();
        $this->dateFilters   = $this->getDateFilters();

        parent::display($tpl);
    }

    protected function getStatusOptions()
    {
        $options = [
            HTMLHelper::_('select.option', '', Text::_('- Status -')),
            HTMLHelper::_('select.option', '0', Text::_('Não Iniciada')),
            HTMLHelper::_('select.option', '1', Text::_('Em Andamento')),
            HTMLHelper::_('select.option', '2', Text::_('Concluída com Sucesso')),
            HTMLHelper::_('select.option', '3', Text::_('Concluída sem Sucesso')),
        ];

        return HTMLHelper::_(
            'select.genericlist',
            $options,
            'filter_status',
            [
                'class' => 'form-select',
                'onchange' => 'this.form.submit()'
            ],
            'value',
            'text',
            $this->state->get('filter.status')
        );
    }

    protected function getTypeOptions()
    {
        $model = $this->getModel();
        $dbOptions = $model->getDemandTypes();

        $options = [
            HTMLHelper::_('select.option', '', Text::_('- Tipo de Demanda -'))
        ];

        if (!empty($dbOptions)) {
            $options = array_merge($options, $dbOptions);
        }

        return HTMLHelper::_(
            'select.genericlist',
            $options,
            'filter_tipo_demanda',
            [
                'class'    => 'form-select',
                'onchange' => 'this.form.submit()'
            ],
            'value',
            'text',
            $this->state->get('filter.tipo_demanda')
        );
    }

    protected function getDateFilters()
    {
        $start = $this->state->get('filter.date_start');
        $end   = $this->state->get('filter.date_end');

        $htmlStart = HTMLHelper::_(
            'calendar',
            $start,
            'filter_date_start',
            'filter_date_start',
            '%Y-%m-%d',
            ['class' => 'form-control', 'placeholder' => 'Data Início', 'autocomplete' => 'off']
        );
        $htmlEnd = HTMLHelper::_(
            'calendar',
            $end,
            'filter_date_end',
            'filter_date_end',
            '%Y-%m-%d',
            ['class' => 'form-control', 'placeholder' => 'Data Fim', 'autocomplete' => 'off']
        );

        return ['start' => $htmlStart, 'end' => $htmlEnd];
    }
}
