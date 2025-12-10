<?php

/**
 * @package Cm\Component\Clientmanager
 *
 * @copyright Copyright (C) 2025 Pedro Inácio Rodrigues Pontes. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

use Cm\Component\Clientmanager\Site\Extension\ClientmanagerComponent;
use Joomla\CMS\Dispatcher\ComponentDispatcherFactoryInterface;
use Joomla\CMS\Extension\ComponentInterface;
use Joomla\CMS\Extension\Service\Provider\ComponentDispatcherFactory;
use Joomla\CMS\Extension\Service\Provider\MVCFactory;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\CMS\HTML\Registry;

\defined('_JEXEC') or die;

return new class implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container->registerServiceProvider(new MVCFactory('\\Cm\\Component\\Clientmanager'));
        $container->registerServiceProvider(new ComponentDispatcherFactory('\\Cm\\Component\\Clientmanager'));

        $container->set(
            ComponentInterface::class,
            function (Container $container) {
                $component = new ClientmanagerComponent($container->get(ComponentDispatcherFactoryInterface::class));
                
                $component->setRegistry($container->get(Registry::class));

                return $component;
            }
        );
    }
};
