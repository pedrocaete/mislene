<?php
/**
 * @package     Cm\Component\Clientmanager
 *
 * @copyright   Copyright (C) 2025 Pedro Inácio Rodrigues Pontes. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;

\defined('_JEXEC') or die;

HTMLHelper::_('behavior.keepalive');
HTMLHelper::_('behavior.formvalidator');

$fieldsets = $this->form->getFieldsets();

$wa = $this->document->getWebAssetManager();
$wa->getRegistry()->addRegistryFile(dirname(__DIR__, 3) . '/media/joomla.asset.json');
$wa->useStyle('com_clientmanager.flatpickr-css');
$wa->useScript('com_clientmanager.client-form');
?>
<div class="com-clientmanager-client-form">
    <form
        action="<?php echo Route::_('index.php?option=com_clientmanager&view=client&layout=edit&id=' . (int) $this->item->id); ?>"
        method="post" name="adminForm" id="item-form" class="form-validate">

        <h1>Cadastro de Cliente</h1>

        <?php echo HTMLHelper::_('bootstrap.startTabSet', 'myTab', ['active' => 'dados_pessoais']); ?>

        <?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'dados_pessoais', JText::_($fieldsets['dados_pessoais']->label)); ?>
        <?php echo $this->form->renderFieldset('dados_pessoais'); ?>
        <?php echo HTMLHelper::_('bootstrap.endTab'); ?>

        <?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'documentos', JTEXT::_($fieldsets['documentos']->label)); ?>
        <?php echo $this->form->renderFieldset('documentos'); ?>
        <?php echo HTMLHelper::_('bootstrap.endTab'); ?>

        <?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'contato', JTEXT::_($fieldsets['contato']->label)); ?>
        <?php echo $this->form->renderFieldset('contato'); ?>
        <?php echo HTMLHelper::_('bootstrap.endTab'); ?>

        <?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'endereco', JTEXT::_($fieldsets['endereco']->label)); ?>
        <?php echo $this->form->renderFieldset('endereco'); ?>
        <?php echo HTMLHelper::_('bootstrap.endTab'); ?>

        <?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'informacoes_adicionais', JTEXT::_($fieldsets['informacoes_adicionais']->label)); ?>
        <?php echo $this->form->renderFieldset('informacoes_adicionais'); ?>
        <?php echo HTMLHelper::_('bootstrap.endTab'); ?>

        <?php echo HTMLHelper::_('bootstrap.endTabSet'); ?>

        <div class="form-group mt-3">
            <button type="submit" class="btn btn-primary">
                Salvar Cliente
            </button>
        </div>

        <input type="hidden" name="task" value="client.save" />
        <?php echo HTMLHelper::_('form.token'); ?>
    </form>
</div>