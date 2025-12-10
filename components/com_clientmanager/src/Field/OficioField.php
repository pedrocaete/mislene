<?php

namespace Cm\Component\Clientmanager\Site\Field;

use Joomla\CMS\Form\Field\TextField;
use Joomla\CMS\Factory;

defined('_JEXEC') or die;

class OficioField extends TextField
{
    protected $type = 'Oficio';

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

        $query->select('DISTINCT ' . $db->quoteName('oficio', 'value'))
            ->select($db->quoteName('oficio', 'text'))
            ->from($db->quoteName('#__clientmanager_demands'))
            ->where($db->quoteName('oficio') . ' IS NOT NULL')
            ->where($db->quoteName('oficio') . ' != ' . $db->quote(''))
            ->order($db->quoteName('oficio') . ' ASC');

        $db->setQuery($query);

        return $db->loadObjectList();
    }
}


