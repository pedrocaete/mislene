<?php

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

?>

<div class="clientmanager-dashboard">
    <div class="page-header mb-4">
        <h1><?php echo Text::_('COM_CLIENTMANAGER_DASHBOARD_TITLE'); ?></h1>
    </div>

    <div class="row">
        <!-- Clients Card -->
        <div class="col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center p-5">
                    <i class="fas fa-users fa-4x text-primary mb-3"></i>
                    <h3 class="card-title"><?php echo Text::_('COM_CLIENTMANAGER_CLIENTS'); ?></h3>
                    <p class="card-text text-muted"><?php echo Text::_('COM_CLIENTMANAGER_DASHBOARD_CLIENTS_DESC'); ?>
                    </p>
                    <a href="<?php echo Route::_('index.php?option=com_clientmanager&view=clients'); ?>"
                        class="btn btn-primary btn-lg mt-3">
                        <i class="fas fa-list me-2"></i> <?php echo Text::_('COM_CLIENTMANAGER_VIEW_CLIENTS'); ?>
                    </a>
                </div>
            </div>
        </div>

        <!-- Demands Card -->
        <div class="col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center p-5">
                    <i class="fas fa-tasks fa-4x text-success mb-3"></i>
                    <h3 class="card-title"><?php echo Text::_('COM_CLIENTMANAGER_DEMANDS'); ?></h3>
                    <p class="card-text text-muted"><?php echo Text::_('COM_CLIENTMANAGER_DASHBOARD_DEMANDS_DESC'); ?>
                    </p>
                    <a href="<?php echo Route::_('index.php?option=com_clientmanager&view=demands'); ?>"
                        class="btn btn-success btn-lg mt-3">
                        <i class="fas fa-list me-2"></i> <?php echo Text::_('COM_CLIENTMANAGER_VIEW_DEMANDS'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>