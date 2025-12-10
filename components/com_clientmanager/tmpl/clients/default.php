<?php

/**
 * @package Cm\Component\Clientmanager
 *
 * @copyright Copyright (C) 2025 Pedro Inácio Rodrigues Pontes. All rights reserved.
 * @license   GNU GPLv2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Layout\LayoutHelper;

$wa = $this->document->getWebAssetManager();


// Get filter options
$bairros = $this->getModel()->getBairros();
$indicadores = $this->getModel()->getIndicadores();

?>

<form action="<?php echo Route::_('index.php?option=com_clientmanager&view=clients'); ?>" method="post" name="adminForm"
    id="adminForm">

    <!-- Filter Bar (Above Card) -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="bg-white p-3 rounded shadow-sm d-flex flex-wrap align-items-end gap-3">

                <!-- Search -->
                <div class="col-12 col-xl flex-grow-1">
                    <label for="filter_search" class="form-label fw-bold text-muted small text-uppercase">Buscar</label>
                    <div class="input-group">
                        <input type="text" name="filter_search" id="filter_search" class="form-control"
                            value="<?php echo $this->escape($this->state->get('filter.search')); ?>"
                            placeholder="Nome ou CPF...">
                        <button class="btn btn-outline-secondary" type="submit"><i class="fas fa-search"></i></button>
                        <button class="btn btn-outline-secondary" type="button"
                            onclick="document.getElementById('filter_search').value='';this.form.submit();"><i
                                class="fas fa-times"></i></button>
                    </div>
                </div>

                <!-- Filter: Bairro -->
                <div class="col-12 col-xl-auto" style="min-width: 200px;">
                    <label for="filter_bairro" class="form-label fw-bold text-muted small text-uppercase">Bairro</label>
                    <select name="filter_bairro" id="filter_bairro" class="form-select" onchange="this.form.submit()">
                        <option value="">- Todos -</option>
                        <?php foreach ($bairros as $bairro): ?>
                            <option value="<?php echo $this->escape($bairro); ?>" <?php echo $this->state->get('filter.bairro') == $bairro ? 'selected' : ''; ?>>
                                <?php echo $this->escape($bairro); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Filter: Quem Indicou -->
                <div class="col-12 col-xl-auto" style="min-width: 200px;">
                    <label for="filter_indicado_por" class="form-label fw-bold text-muted small text-uppercase">Quem
                        Indicou</label>
                    <select name="filter_indicado_por" id="filter_indicado_por" class="form-select"
                        onchange="this.form.submit()">
                        <option value="">- Todos -</option>
                        <?php foreach ($indicadores as $indicador): ?>
                            <option value="<?php echo $indicador->id; ?>" <?php echo $this->state->get('filter.indicado_por') == $indicador->id ? 'selected' : ''; ?>>
                                <?php echo $this->escape($indicador->nome_completo); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Filter: Aniversariantes -->
                <div class="col-12 col-xl-auto form-check mb-2 align-self-center">
                    <input class="form-check-input" type="checkbox" name="filter_aniversariantes" value="1"
                        id="filter_aniversariantes" onchange="this.form.submit()" <?php echo $this->state->get('filter.aniversariantes') == 1 ? 'checked' : ''; ?>>
                    <label class="form-check-label fw-bold text-primary" for="filter_aniversariantes">
                        <i class="fas fa-birthday-cake me-1"></i> Aniversariantes do Mês
                    </label>
                </div>

            </div>
        </div>
    </div>

    <!-- Main List Card -->
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-primary fw-bold"><i class="fas fa-users me-2"></i>Gerenciamento de Clientes</h5>
            <a href="<?php echo Route::_('index.php?option=com_clientmanager&task=client.add'); ?>"
                class="btn btn-primary btn-sm rounded-pill px-3">
                <i class="fas fa-plus me-1"></i> Novo Cliente
            </a>
        </div>
        <div class="card-body p-0">
            <?php if (empty($this->items)): ?>
                <div class="alert alert-info m-3 text-center">
                    <i class="fas fa-info-circle me-2"></i> Nenhum cliente encontrado.
                </div>
            <?php else: ?>
                <!-- Desktop Table View -->
                <div class="table-responsive d-none d-md-block" style="min-height: 300px; overflow-y: visible;">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Nome Completo</th>
                                <th>Bairro</th>
                                <th>WhatsApp</th>
                                <th class="text-end pe-3">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($this->items as $i => $item): ?>
                                <tr>
                                    <td class="ps-3 fw-bold text-dark">
                                        <?php echo $this->escape($item->nome_completo); ?>
                                        <?php if (!empty($item->indicador_nome)): ?>
                                            <div class="small text-muted"><i class="fas fa-user-tag me-1"></i>Ind:
                                                <?php echo $this->escape($item->indicador_nome); ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo $this->escape($item->bairro); ?></td>
                                    <td>
                                        <?php if (!empty($item->celular_whatsapp)): ?>
                                            <a href="https://wa.me/55<?php echo preg_replace('/[^0-9]/', '', $item->celular_whatsapp); ?>"
                                                target="_blank" class="text-decoration-none text-success">
                                                <i class="fab fa-whatsapp me-1"></i>
                                                <?php echo $this->escape($item->celular_whatsapp); ?>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end pe-3">
                                        <div class="dropdown">
                                            <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow" style="z-index: 1050;">
                                                <li><a class="dropdown-item"
                                                        href="<?php echo Route::_('index.php?option=com_clientmanager&task=client.edit&id=' . $item->id); ?>"><i
                                                            class="fas fa-edit me-2 text-primary"></i> Editar</a></li>
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="<?php echo Route::_('index.php?option=com_clientmanager&view=client&layout=show&id=' . (int) $item->id); ?>">
                                                        <i class="fas fa-eye me-2 text-info"></i> Ver Detalhes
                                                    </a>
                                                </li>
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                <li>
                                                    <button type="button" class="dropdown-item text-danger"
                                                        onclick="if (confirm('Tem certeza?')) { document.getElementById('adminForm-delete-<?php echo $item->id; ?>').submit(); }">
                                                        <i class="fas fa-trash me-2"></i> Excluir
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>

                                        <form
                                            action="<?php echo Route::_('index.php?option=com_clientmanager&task=client.delete'); ?>"
                                            method="post" id="adminForm-delete-<?php echo $item->id; ?>" class="d-none">
                                            <input type="hidden" name="cid[]" value="<?php echo $item->id; ?>">
                                            <?php echo HTMLHelper::_('form.token'); ?>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="d-md-none bg-light p-2">
                    <?php foreach ($this->items as $i => $item): ?>
                        <div class="card mb-3 shadow-sm border-0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h5 class="card-title fw-bold text-primary mb-1">
                                            <?php echo $this->escape($item->nome_completo); ?>
                                        </h5>
                                        <?php if (!empty($item->bairro)): ?>
                                            <span class="badge bg-light text-dark border">
                                                <i class="fas fa-map-marker-alt me-1"></i>
                                                <?php echo $this->escape($item->bairro); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-link text-muted p-0" type="button" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-lg"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
                                            <li><a class="dropdown-item"
                                                    href="<?php echo Route::_('index.php?option=com_clientmanager&task=client.edit&id=' . $item->id); ?>">
                                                    <i class="fas fa-edit me-2 text-primary"></i> Editar
                                                </a></li>
                                            <li><a class="dropdown-item"
                                                    href="<?php echo Route::_('index.php?option=com_clientmanager&view=client&layout=show&id=' . (int) $item->id); ?>">
                                                    <i class="fas fa-eye me-2 text-info"></i> Ver Detalhes
                                                </a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item text-danger"
                                                    onclick="if (confirm('Tem certeza?')) { document.getElementById('adminForm-delete-mobile-<?php echo $item->id; ?>').submit(); }">
                                                    <i class="fas fa-trash me-2"></i> Excluir
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <?php if (!empty($item->indicador_nome)): ?>
                                    <p class="card-text small text-muted mb-2">
                                        <i class="fas fa-user-tag me-1"></i> Indicado por:
                                        <strong><?php echo $this->escape($item->indicador_nome); ?></strong>
                                    </p>
                                <?php endif; ?>

                                <?php if (!empty($item->celular_whatsapp)): ?>
                                    <div class="d-grid mt-3">
                                        <a href="https://wa.me/55<?php echo preg_replace('/[^0-9]/', '', $item->celular_whatsapp); ?>"
                                            target="_blank" class="btn btn-outline-success btn-sm">
                                            <i class="fab fa-whatsapp me-1"></i>
                                            <?php echo $this->escape($item->celular_whatsapp); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>

                                <form action="<?php echo Route::_('index.php?option=com_clientmanager&task=client.delete'); ?>"
                                    method="post" id="adminForm-delete-mobile-<?php echo $item->id; ?>" class="d-none">
                                    <input type="hidden" name="cid[]" value="<?php echo $item->id; ?>">
                                    <?php echo HTMLHelper::_('form.token'); ?>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="card-footer bg-white d-flex justify-content-center py-3">
            <?php echo $this->pagination->getPagesLinks(); ?>
        </div>
    </div>
    </div>