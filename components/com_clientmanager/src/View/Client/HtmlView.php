<?php

/**
 * @package Cm\Component\Clientmanager
 *
 * @copyright Copyright (C) 2025 Pedro Inácio Rodrigues Pontes. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Cm\Component\Clientmanager\Site\View\Client;

use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Factory;

\defined('_JEXEC') or die;

class HtmlView extends BaseHtmlView
{
    protected $form;
    protected $item;

    public function display($tpl = null)
    {
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');

        $this->user = Factory::getApplication()->getIdentity();

        if ($this->item) {
            if ($this->item->id == 0) {
                $this->item->canEdit = $this->user->authorise('core.create', 'com_clientmanager');
            } else {
                $this->item->canEdit = $this->user->authorise('core.edit', 'com_clientmanager');
            }
            $this->item->canDelete = $this->user->authorise('core.delete', 'com_clientmanager');

            parent::display($tpl);
        }
    }
}
