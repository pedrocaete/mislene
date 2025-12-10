<?php

namespace Cm\Component\Clientmanager\Site\Field;

use Joomla\CMS\Form\Field\TextField;
use Joomla\CMS\Factory;

defined('_JEXEC') or die;

class SetorencaminhadoField extends TextField
{
    protected $type = 'Setorencaminhado';

    protected function getInput()
    {
        $listId = $this->id . '_list';

        $this->element['list'] = $listId;
        $this->element['autocomplete'] = 'off';

        $html = parent::getInput();

        $options = $this->getOptions();

        $html .= '<datalist id="' . $listId . '">';
        if (!empty($options)) {
            foreach ($options as $option) {
                $val = htmlspecialchars($option->value, ENT_QUOTES, 'UTF-8');
                $html .= '<option value="' . $val . '">';
            }
        }
        $html .= '</datalist>';

        return $html;
    }

    protected function getOptions()
    {
        $db = Factory::getContainer()->get('DatabaseDriver');
        $query = $db->getQuery(true);

        $query->select('DISTINCT ' . $db->quoteName('setor_encaminhado', 'value'))
            ->select($db->quoteName('setor_encaminhado', 'text'))
            ->from($db->quoteName('#__clientmanager_demands'))
            ->where($db->quoteName('setor_encaminhado') . ' IS NOT NULL')
            ->where($db->quoteName('setor_encaminhado') . ' != ' . $db->quote(''))
            ->order($db->quoteName('setor_encaminhado') . ' ASC');

        $db->setQuery($query);

        return $db->loadObjectList();
    }
}


