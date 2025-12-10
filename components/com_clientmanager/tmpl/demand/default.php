<?php
defined('_JEXEC') or die;

use Joomla\CMS\Router\Route;
use Joomla\CMS\HTML\HTMLHelper;

HTMLHelper::_('behavior.formvalidator');
HTMLHelper::_('behavior.keepalive');

$fieldsets = $this->form->getFieldsets();
$item = $this->item;
$form = $this->form;

$wa = $this->document->getWebAssetManager();
$wa->getRegistry()->addRegistryFile(dirname(__DIR__, 3) . '/media/joomla.asset.json');
$wa->useStyle('com_clientmanager.flatpickr-css');
$wa->useScript('com_clientmanager.demand-form');
?>

<div class="com_clientmanager-demand-form">
    <form
        action="<?php echo Route::_('index.php?option=com_clientmanager&view=demand&layout=edit&id=' . (int) $this->item->id); ?>"
        method="post" name="adminForm" id="adminForm" class="form-validate">

        <h1><?php echo empty($this->item->id) ? 'Nova Demanda' : 'Editar Demanda'; ?></h1>

        <div class="main-card">

            <?php
            echo HTMLHelper::_('bootstrap.startTabSet', 'myTab', ['active' => 'informacoes_demanda']);

            foreach ($form->getFieldsets() as $fieldset) {

                if ($fieldset->name === 'controle') {
                    continue;
                }

                $name = $fieldset->name;
                $label = JText::_($fieldset->label);

                echo HTMLHelper::_('bootstrap.addTab', 'myTab', $name, $label);
                echo $this->form->renderFieldset($name);
                echo HTMLHelper::_('bootstrap.endTab');
            }

            echo HTMLHelper::_('bootstrap.endTabSet');
            ?>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary validate">Salvar</button>
                <a class="btn btn-secondary"
                    href="<?php echo Route::_('index.php?option=com_clientmanager&view=demands'); ?>">Cancelar</a>
            </div>
        </div>

        <input type="hidden" name="task" value="demand.save" />
        <?php echo HTMLHelper::_('form.token'); ?>
    </form>
</div>