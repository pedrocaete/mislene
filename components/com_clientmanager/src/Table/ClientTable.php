<?php

/**
 * @package Cm\Component\Clientmanager
 *
 * @copyright Copyright (C) 2025 Pedro Inácio Rodrigues Pontes. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Cm\Component\Clientmanager\Site\Table;

use Joomla\CMS\Table\Table;
use Joomla\CMS\Factory;

\defined('_JEXEC') or die;

class ClientTable extends Table
{
    public function __construct($db)
    {
        parent::__construct('#__clientmanager_clients', 'id', $db);
    }

    public function check()
    {
        $currentDate = Factory::getDate()->toSql();

        if (empty($this->id)) {
            if (empty($this->created)) {
                $this->created = $currentDate;
            }
        } else {
            $this->modified = $currentDate;
        }

        if (empty($this->data_nascimento)) {
            $this->data_nascimento = null;
        }
        if (empty($this->created_by)) {
            $this->created_by = Factory::getUser()->id;
        }

        return parent::check();
    }
}
