<?php

/**
 * @package Cm\Component\Clientmanager
 *
 * @copyright Copyright (C) 2025 Pedro Inácio Rodrigues Pontes. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Cm\Component\Clientmanager\Site\View\Clients;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;

\defined('_JEXEC') or die;

class HtmlView extends BaseHtmlView
{
    public $items;
    public $user;

    public function display($tpl = null)
    {
        $this->items = $this->get('Items');
        $this->state = $this->get('State');
        $this->pagination = $this->get('Pagination');
        $this->user = Factory::getApplication()->getIdentity();

        if ($this->items) {
            $canDeleteGlobally = $this->user->authorise('core.delete', 'com_clientmanager');
            $canEditGlobally = $this->user->authorise('core.edit', 'com_clientmanager');

            foreach ($this->items as $item) {
                $item->canEdit = $canEditGlobally;
                $item->canDelete = $canDeleteGlobally;
            }
        }

        parent::display($tpl);
    }
}
