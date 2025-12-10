<?php

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Layout\LayoutHelper;

$wa = $this->document->getWebAssetManager();


// Get filter options
$demandTypes = $this->getModel()->getDemandTypes();

?>

<form action="<?php echo Route::_('index.php?option=com_clientmanager&view=demands'); ?>" method="post" name="adminForm"
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
                            placeholder="Título ou Cliente...">
                        <button class="btn btn-outline-secondary" type="submit"><i class="fas fa-search"></i></button>
                        <button class="btn btn-outline-secondary" type="button"
                            onclick="document.getElementById('filter_search').value='';this.form.submit();"><i
                                class="fas fa-times"></i></button>
                    </div>
                </div>


                <!-- Filter: Status -->
                <div class="col-12 col-xl-auto" style="min-width: 180px;">
                    <label for="filter_status" class="form-label fw-bold text-muted small text-uppercase">Status</label>
                    <select name="filter_status" id="filter_status" class="form-select" onchange="this.form.submit()">
                        <option value="">- Todos -</option>
                        <option value="0" <?php echo $this->state->get('filter.status') === '0' ? 'selected' : ''; ?>>Não
                            Iniciada</option>
                        <option value="1" <?php echo $this->state->get('filter.status') === '1' ? 'selected' : ''; ?>>Em
                            Andamento</option>
                        <option value="2" <?php echo $this->state->get('filter.status') === '2' ? 'selected' : ''; ?>>
                            Concluído (Sucesso)</option>
                        <option value="3" <?php echo $this->state->get('filter.status') === '3' ? 'selected' : ''; ?>>
                            Concluído (Sem Sucesso)</option>
                    </select>
                </div>

                <!-- Filter: Date Range -->
                <div class="col-12 col-xl-auto d-flex gap-2">
                    <div>
                        <label for="filter_date_start"
                            class="form-label fw-bold text-muted small text-uppercase">De</label>
                        <input type="date" name="filter_date_start" id="filter_date_start" class="form-control"
                            value="<?php echo $this->escape($this->state->get('filter.date_start')); ?>"
                            onchange="this.form.submit()">
                    </div>
                    <div>
                        <label for="filter_date_end"
                            class="form-label fw-bold text-muted small text-uppercase">Até</label>
                        <input type="date" name="filter_date_end" id="filter_date_end" class="form-control"
                            value="<?php echo $this->escape($this->state->get('filter.date_end')); ?>"
                            onchange="this.form.submit()">
                    </div>
                </div>

                <!-- Sort: Deadline -->
                <div class="col-12 col-xl-auto form-check mb-2 align-self-center">
                    <input class="form-check-input" type="checkbox" name="filter_sort_deadline" value="1"
                        id="filter_sort_deadline" onchange="this.form.submit()" <?php echo $this->state->get('filter.sort_deadline') == 1 ? 'checked' : ''; ?>>
                    <label class="form-check-label fw-bold text-danger" for="filter_sort_deadline">
                        <i class="fas fa-sort-amount-up me-1"></i> Ordenar por Prazo
                    </label>
                </div>

            </div>
        </div>
    </div>

    <!-- Main List Card -->
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-primary fw-bold"><i class="fas fa-tasks me-2"></i>Gerenciamento de Demandas</h5>
            <a href="<?php echo Route::_('index.php?option=com_clientmanager&task=demand.add'); ?>"
                class="btn btn-primary btn-sm rounded-pill px-3">
                <i class="fas fa-plus me-1"></i> Nova Demanda
            </a>
        </div>
        <div class="card-body p-0">
            <?php if (empty($this->items)): ?>
                <div class="alert alert-info m-3 text-center">
                    <i class="fas fa-info-circle me-2"></i> Nenhuma demanda encontrada.
                </div>
            <?php else: ?>
                <!-- Desktop Table View -->
                <div class="table-responsive d-none d-md-block" style="min-height: 300px; overflow-y: visible;">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Título / Cliente</th>
                                <th>Tipo</th>
                                <th>Criado em</th>
                                <th>Prazo</th>
                                <th>Status</th>
                                <th class="text-end pe-3">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($this->items as $i => $item): ?>
                                <tr>
                                    <td class="ps-3">
                                        <div class="fw-bold text-dark"><?php echo $this->escape($item->titulo); ?></div>
                                        <div class="small text-muted"><i class="fas fa-user me-1"></i>
                                            <?php echo $this->escape($item->cliente_nome); ?></div>
                                    </td>
                                    <td><span
                                            class="badge bg-light text-dark border"><?php echo $this->escape($item->tipo_demanda); ?></span>
                                    </td>
                                    <td><?php echo date('d/m/Y', strtotime($item->created)); ?></td>
                                    <td>
                                        <?php if ($item->data_conclusao): ?>
                                            <?php
                                            $deadline = strtotime($item->data_conclusao);
                                            $now = time();
                                            $class = 'text-muted';
                                            if ($item->status < 2 && $deadline < $now) {
                                                $class = 'text-danger fw-bold';
                                            } elseif ($item->status < 2 && $deadline < $now + (86400 * 3)) {
                                                $class = 'text-warning fw-bold';
                                            }
                                            ?>
                                            <span class="<?php echo $class; ?>"><?php echo date('d/m/Y H:i', $deadline); ?></span>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                        $statusClass = 'bg-secondary';
                                        $statusLabel = 'Desconhecido';
                                        switch ($item->status) {
                                            case 0:
                                                $statusClass = 'bg-secondary';
                                                $statusLabel = 'Não Iniciada';
                                                break;
                                            case 1:
                                                $statusClass = 'bg-primary';
                                                $statusLabel = 'Em Andamento';
                                                break;
                                            case 2:
                                                $statusClass = 'bg-success';
                                                $statusLabel = 'Concluído (Sucesso)';
                                                break;
                                            case 3:
                                                $statusClass = 'bg-danger';
                                                $statusLabel = 'Concluído (Falha)';
                                                break;
                                        }
                                        ?>
                                        <span
                                            class="badge rounded-pill <?php echo $statusClass; ?>"><?php echo $statusLabel; ?></span>
                                    </td>
                                    <td class="text-end pe-3">
                                        <div class="dropdown">
                                            <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow" style="z-index: 1050;">
                                                <li><a class="dropdown-item"
                                                        href="<?php echo Route::_('index.php?option=com_clientmanager&task=demand.edit&id=' . $item->id); ?>"><i
                                                            class="fas fa-edit me-2 text-primary"></i> Editar</a></li>
                                                <?php if ($item->canDelete): ?>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li>
                                                        <button type="button" class="dropdown-item text-danger"
                                                            onclick="if (confirm('Tem certeza?')) { document.getElementById('delete-form-<?php echo $item->id; ?>').submit(); }">
                                                            <i class="fas fa-trash me-2"></i>
                                                            Excluir
                                                        </button>
                                                    </li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
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
                                            <?php echo $this->escape($item->titulo); ?>
                                        </h5>
                                        <div class="small text-muted mb-2">
                                            <i class="fas fa-user me-1"></i> <?php echo $this->escape($item->cliente_nome); ?>
                                        </div>
                                        <span class="badge bg-light text-dark border mb-2">
                                            <?php echo $this->escape($item->tipo_demanda); ?>
                                        </span>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-link text-muted p-0" type="button" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-lg"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
                                            <li><a class="dropdown-item"
                                                    href="<?php echo Route::_('index.php?option=com_clientmanager&task=demand.edit&id=' . $item->id); ?>">
                                                    <i class="fas fa-edit me-2 text-primary"></i> Editar
                                                </a></li>
                                            <?php if ($item->canDelete): ?>
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                <li>
                                                    <button type="button" class="dropdown-item text-danger"
                                                        onclick="if (confirm('Tem certeza?')) { document.getElementById('delete-form-mobile-<?php echo $item->id; ?>').submit(); }">
                                                        <i class="fas fa-trash me-2"></i> Excluir
                                                    </button>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </div>

                                <div class="row g-2 small text-muted">
                                    <div class="col-6">
                                        <i class="far fa-calendar-alt me-1"></i> Criado: <br>
                                        <span class="text-dark"><?php echo date('d/m/Y', strtotime($item->created)); ?></span>
                                    </div>
                                    <div class="col-6">
                                        <i class="far fa-clock me-1"></i> Prazo: <br>
                                        <?php if ($item->data_conclusao): ?>
                                            <?php
                                            $deadline = strtotime($item->data_conclusao);
                                            $now = time();
                                            $class = 'text-dark';
                                            if ($item->status < 2 && $deadline < $now) {
                                                $class = 'text-danger fw-bold';
                                            } elseif ($item->status < 2 && $deadline < $now + (86400 * 3)) {
                                                $class = 'text-warning fw-bold';
                                            }
                                            ?>
                                            <span class="<?php echo $class; ?>"><?php echo date('d/m/Y H:i', $deadline); ?></span>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <?php
                                    $statusClass = 'bg-secondary';
                                    $statusLabel = 'Desconhecido';
                                    switch ($item->status) {
                                        case 0:
                                            $statusClass = 'bg-secondary';
                                            $statusLabel = 'Não Iniciada';
                                            break;
                                        case 1:
                                            $statusClass = 'bg-primary';
                                            $statusLabel = 'Em Andamento';
                                            break;
                                        case 2:
                                            $statusClass = 'bg-success';
                                            $statusLabel = 'Concluído (Sucesso)';
                                            break;
                                        case 3:
                                            $statusClass = 'bg-danger';
                                            $statusLabel = 'Concluído (Falha)';
                                            break;
                                    }
                                    ?>
                                    <span
                                        class="badge rounded-pill w-100 py-2 <?php echo $statusClass; ?>"><?php echo $statusLabel; ?></span>
                                </div>

                                <?php if ($item->canDelete): ?>
                                    <form action="<?php echo Route::_('index.php?option=com_clientmanager&task=demand.remove'); ?>"
                                        method="post" id="delete-form-mobile-<?php echo $item->id; ?>" class="d-none">
                                        <input type="hidden" name="cid[]" value="<?php echo $item->id; ?>">
                                        <?php echo HTMLHelper::_('form.token'); ?>
                                    </form>
                                <?php endif; ?>
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

    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
    <?php echo HTMLHelper::_('form.token'); ?>
</form>

<?php if (!empty($this->items)): ?>
    <?php foreach ($this->items as $item): ?>
        <?php if ($item->canDelete): ?>
            <form action="<?php echo Route::_('index.php?option=com_clientmanager&task=demand.remove'); ?>" method="post"
                id="delete-form-<?php echo $item->id; ?>" class="d-none">
                <input type="hidden" name="cid[]" value="<?php echo $item->id; ?>">
                <?php echo HTMLHelper::_('form.token'); ?>
            </form>
        <?php endif; ?>
    <?php endforeach; ?>
<?php endif; ?>