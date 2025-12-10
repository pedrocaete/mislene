<?php

/**
 * @package     Cm\Component\Clientmanager
 * @subpackage  com_clientmanager
 *
 * @copyright   Copyright (C) 2025 Pedro Inácio Rodrigues Pontes. All rights reserved.
 * @license     GNU GPLv2 or later; see LICENSE.txt
 */

namespace Cm\Component\Clientmanager\Site\View\Dashboard;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;

/**
 * Dashboard View
 */
class HtmlView extends BaseHtmlView
{
    /**
     * Display the view
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  void
     */
    public function display($tpl = null)
    {
        // Check for errors.
        // Check for errors.
        if (count($errors = $this->get('Errors') ?: [])) {
            throw new \Exception(implode("\n", $errors), 500);
        }

        parent::display($tpl);
    }
}
