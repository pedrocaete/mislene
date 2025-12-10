<?php

namespace Cm\Component\Clientmanager\Site\View\Demand;

use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

defined('_JEXEC') or die;

class HtmlView extends BaseHtmlView
{
    protected $form;
    protected $item;

    public function display($tpl = null)
    {
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');

        if (count($errors = $this->get('Errors'))) {
            throw new \Exception(implode("\n", $errors), 500);
        }

        $this->prepareDocument();

        return parent::display($tpl);
    }

    protected function prepareDocument()
    {
        $app = Factory::getApplication();
        $isNew = ($this->item->id == 0);

        $title = $isNew
            ? Text::_('COM_CLIENTMANAGER_DEMAND_NEW')
            : Text::sprintf('COM_CLIENTMANAGER_DEMAND_EDIT', $this->item->id);

        $this->document->setTitle($title);
    }
}
