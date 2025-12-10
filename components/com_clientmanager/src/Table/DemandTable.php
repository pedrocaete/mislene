<?php

namespace Cm\Component\Clientmanager\Site\Table;

use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseDriver;
use Joomla\CMS\Factory;

defined('_JEXEC') or die;

class DemandTable extends Table
{
    public function __construct($db)
    {
        parent::__construct('#__clientmanager_demands', 'id', $db);
    }

    public function check()
    {
        $currentDate = Factory::getDate()->toSql();
        $user = Factory::getUser();

        if (empty($this->id)) {
            if (empty($this->created)) {
                $this->created = $currentDate;
            }
            if (empty($this->created_by)) {
                $this->created_by = $user->id;
            }
        } else {
            $this->modified = $currentDate;
        }

        if (empty($this->data_conclusao)) {
            $this->data_conclusao = null;
        }

        if (!empty($this->custo)) {
            $this->custo = str_replace(',', '.', $this->custo);
        } else {
            $this->custo = null;
        }

        return parent::check();
    }
}
