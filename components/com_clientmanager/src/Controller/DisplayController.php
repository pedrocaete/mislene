<?php

/**
 * @package     Cm\Component\Clientmanager
 *
 * @copyright   Copyright (C) 2025 Pedro Inácio Rodrigues Pontes. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Cm\Component\Clientmanager\Site\Controller;

use Joomla\CMS\MVC\Controller\BaseController;

\defined('_JEXEC') or die;

class DisplayController extends BaseController
{
    public function display($cachable = false, $urlparams = array())
    {
        $user = \Joomla\CMS\Factory::getApplication()->getIdentity();

        if (!$user->authorise('core.manage', 'com_clientmanager')) {
            throw new \Exception(\Joomla\CMS\Language\Text::_('JERROR_ALERTNOAUTHOR'), 403);
        }

        return parent::display($cachable, $urlparams);
    }
}
