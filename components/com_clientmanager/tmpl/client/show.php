<?php

/**
 * @package Cm\Component\Clientmanager
 *
 * @copyright Copyright (C) 2025 Pedro Inácio Rodrigues Pontes. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

$item = $this->item;
$form = $this->form;
?>

<div class="com-clientmanager-client-details">

    <h1><?php echo Text::_('Detalhes de:'); ?> <?php echo $this->escape($item->nome_completo); ?></h1>

    <div class="row">
        <div class="col-md-8">
            <?php foreach ($form->getFieldsets() as $fieldset) : ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <strong><?php echo Text::_($fieldset->label); ?></strong>
                    </div>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($form->getFieldset($fieldset->name) as $field) : ?>
                            <?php $fieldName = $field->getAttribute('name'); ?>
                            <?php if ($field->getAttribute('type') !== 'hidden') : ?>
                                <li class="list-group-item">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <strong><?php echo Text::_($field->label); ?></strong>
                                        </div>
                                        <div class="col-md-8">
                                            <?php if ($field->getAttribute('type') === 'calendar') : ?>
                                                <?php echo \Joomla\CMS\HTML\HTMLHelper::_('date', $item->$fieldName, 'd/m/Y'); ?>
                                            <?php elseif ($fieldName === 'children') : ?>

                                                <?php if (!empty($item->children)) : ?>
                                                    <table class="table table-sm table-bordered mt-2">
                                                        <thead>
                                                            <tr>
                                                                <th>Nome</th>
                                                                <th>Nascimento</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($item->children as $child) : ?>
                                                                <tr>
                                                                    <td><?php echo $child->nome_completo; ?></td>
                                                                    <td>
                                                                        <?php
                                                                        echo (!empty($child->data_nascimento))
                                                                            ? \Joomla\CMS\HTML\HTMLHelper::_('date', $child->data_nascimento, 'd/m/Y', 'America/Sao_Paulo')
                                                                            : '-';
                                                                        ?>
                                                                    </td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                <?php else : ?>
                                                    <span class="text-muted">Nenhum filho cadastrado.</span>
                                                <?php endif; ?>
                                            <?php else : ?>
                                                <?php echo $this->escape($item->$fieldName); ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <strong>Ações Rápidas</strong>
                </div>
                <div class="list-group list-group-flush">
                    <?php if ($item->canEdit) : ?>
                        <a href="<?php echo \Joomla\CMS\Router\Route::_('index.php?option=com_clientmanager&view=client&layout=edit&id=' . (int) $item->id); ?>" class="list-group-item list-group-item-action">
                            <i class="fas fa-pencil-alt me-2"></i> Editar Cliente
                        </a>
                    <?php endif; ?>
                    <?php if ($item->canDelete) : ?>
                        <a href="#" class="list-group-item list-group-item-action text-danger" onclick="document.getElementById('adminForm-delete-<?php echo $item->id; ?>').submit(); return false;">
                            <i class="fas fa-trash me-2"></i> Excluir Cliente
                        </a>
                        <form action="<?php echo \Joomla\CMS\Router\Route::_('index.php?option=com_clientmanager&task=client.delete', false); ?>" method="post" id="adminForm-delete-<?php echo $item->id; ?>" class="d-none">
                            <input type="hidden" name="cid[]" value="<?php echo $item->id; ?>">
                            <?php echo \Joomla\CMS\HTML\HTMLHelper::_('form.token'); ?>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
