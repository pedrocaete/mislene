<?php
/**
 * @package     Cm\Component\Clientmanager
 *
 * @copyright   Copyright (C) 2025 Pedro Inácio Rodrigues Pontes. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Cm\Component\Clientmanager\Site\Controller;

use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\Utilities\ArrayHelper;

\defined('_JEXEC') or die;

class ClientController extends FormController
{
    public function delete()
    {
        $this->checkToken();
        $user = Factory::getApplication()->getIdentity();

        if (!$user->authorise('core.delete', 'com_clientmanager')) {
            $this->setMessage(Text::_('COM_CLIENTMANAGER_ERROR_NO_PERMISSION_TO_DELETE'), 'error');
            $this->setRedirect(Route::_('index.php?option=com_clientmanager&view=clients', false));
            return;
        }

        $pks = $this->input->post->get('cid', [], 'array');
        $model = $this->getModel('Client');

        ArrayHelper::toInteger($pks);

        if(!empty($pks)) {
            if ($model->delete($pks)) {
                $this->setMessage(Text::sprintf('COM_CLIENTMANAGER_DELETE_SUCCESS', count($pks)));
            } else {
                $this->setMessage(Text::_('COM_CLIENTMANAGER_DELETE_ERROR'), 'error');
            }
        }

        $this->setRedirect(Route::_('index.php?option=com_clientmanager&view=clients', false));
    }
}
