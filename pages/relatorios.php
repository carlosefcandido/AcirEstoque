<?php
require_once 'includes/db.php';

// Check if user is logged in and is a stock_manager or admin
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'stock_manager' && $_SESSION['role'] !== 'admin')) {
    header('Location: index.php?page=login');
    exit();
}

include_once 'includes/header.php';

// Report 1: Current Stock Levels
$stmt_stock = $pdo->query("SELECT nome, quantidade FROM itens ORDER BY nome");
$stock_levels = $stmt_stock->fetchAll(PDO::FETCH_ASSOC);

// Report 2: Requisition Summary by Status
$stmt_requisition_summary = $pdo->query("SELECT status, COUNT(*) as total FROM requisicoes GROUP BY status");
$requisition_summary = $stmt_requisition_summary->fetchAll(PDO::FETCH_ASSOC);

// Report 3: Total Quantity of Items Disbursed per Cost Center
$stmt_items_per_cost_center = $pdo->query("SELECT cc.nome AS centro_de_custo, SUM(s.quantidade) AS total_quantidade_saida
                                            FROM saidas s
                                            JOIN centros_de_custo cc ON s.centro_de_custo_id = cc.id
                                            GROUP BY cc.nome
                                            ORDER BY total_quantidade_saida DESC");
$items_per_cost_center = $stmt_items_per_cost_center->fetchAll(PDO::FETCH_ASSOC);

// Report 4: Quantity of Requisitions per Cost Center
$stmt_requisitions_per_cost_center = $pdo->query("SELECT cc.nome AS centro_de_custo, COUNT(r.id) AS total_requisicoes
                                                    FROM requisicoes r
                                                    JOIN centros_de_custo cc ON r.centro_de_custo_id = cc.id
                                                    GROUP BY cc.nome
                                                    ORDER BY total_requisicoes DESC");
$requisitions_per_cost_center = $stmt_requisitions_per_cost_center->fetchAll(PDO::FETCH_ASSOC);

// Report 5: Quantity of Requisitions per User
$stmt_requisitions_per_user = $pdo->query("SELECT u.username AS usuario, COUNT(r.id) AS total_requisicoes
                                            FROM requisicoes r
                                            JOIN users u ON r.user_id = u.id
                                            GROUP BY u.username
                                            ORDER BY total_requisicoes DESC");
$requisitions_per_user = $stmt_requisitions_per_user->fetchAll(PDO::FETCH_ASSOC);

?>

<h1>Relatórios</h1>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">Níveis Atuais de Estoque</div>
            <div class="card-body">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Quantidade em Estoque</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($stock_levels) > 0): ?>
                            <?php foreach ($stock_levels as $item): ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['nome']) ?></td>
                                    <td><?= htmlspecialchars($item['quantidade']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="2" class="text-center">Nenhum item encontrado.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">Resumo de Requisições por Status</div>
            <div class="card-body">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($requisition_summary) > 0): ?>
                            <?php foreach ($requisition_summary as $summary): ?>
                                <tr>
                                    <td><?= htmlspecialchars($summary['status']) ?></td>
                                    <td><?= htmlspecialchars($summary['total']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="2" class="text-center">Nenhuma requisição encontrada.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">Quantidade Total de Itens Saídos por Centro de Custo</div>
            <div class="card-body">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Centro de Custo</th>
                            <th>Quantidade Total Saída</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($items_per_cost_center) > 0): ?>
                            <?php foreach ($items_per_cost_center as $data): ?>
                                <tr>
                                    <td><?= htmlspecialchars($data['centro_de_custo']) ?></td>
                                    <td><?= htmlspecialchars($data['total_quantidade_saida']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="2" class="text-center">Nenhuma saída registrada por centro de custo.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">Número de Requisições por Centro de Custo</div>
            <div class="card-body">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Centro de Custo</th>
                            <th>Total de Requisições</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($requisitions_per_cost_center) > 0): ?>
                            <?php foreach ($requisitions_per_cost_center as $data): ?>
                                <tr>
                                    <td><?= htmlspecialchars($data['centro_de_custo']) ?></td>
                                    <td><?= htmlspecialchars($data['total_requisicoes']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="2" class="text-center">Nenhuma requisição encontrada por centro de custo.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">Número de Requisições por Usuário</div>
            <div class="card-body">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Usuário</th>
                            <th>Total de Requisições</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($requisitions_per_user) > 0): ?>
                            <?php foreach ($requisitions_per_user as $data): ?>
                                <tr>
                                    <td><?= htmlspecialchars($data['usuario']) ?></td>
                                    <td><?= htmlspecialchars($data['total_requisicoes']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="2" class="text-center">Nenhuma requisição encontrada por usuário.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>