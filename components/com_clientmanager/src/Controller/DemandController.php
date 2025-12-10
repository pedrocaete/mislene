<?php

namespace Cm\Component\Clientmanager\Site\Controller;

use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\Utilities\ArrayHelper;

defined('_JEXEC') or die;

class DemandController extends FormController
{
    protected $view_list = 'demands';

    public function delete()
    {
        $this->checkToken();

        $user = Factory::getApplication()->getIdentity();
        if (!$user->authorise('core.delete', 'com_clientmanager')) {
            $this->setMessage(Text::_('COM_CLIENTMANAGER_ERROR_NO_PERMISSION_TO_DELETE'), 'error');
            $this->setRedirect(Route::_('index.php?option=com_clientmanager&view=demands', false));
            return;
        }

        $pks = $this->input->post->get('cid', [], 'array');
        ArrayHelper::toInteger($pks);

        if (!empty($pks)) {
            $model = $this->getModel('Demand');
            
            if ($model->delete($pks)) {
                $this->setMessage(Text::sprintf('COM_CLIENTMANAGER_DEMANDS_DELETE_SUCCESS', count($pks)));
            } else {
                $this->setMessage($model->getError(), 'error');
            }
        }

        $this->setRedirect(
            Route::_('index.php?option=com_clientmanager&view=demands', false)
        );
    }

    protected function allowAdd($data = array())
    {
        return parent::allowAdd($data) && $this->app->getIdentity()->authorise('core.create', 'com_clientmanager');
    }

    protected function allowEdit($data = array(), $key = 'id')
    {
        return parent::allowEdit($data, $key) && $this->app->getIdentity()->authorise('core.edit', 'com_clientmanager');
    }
}
