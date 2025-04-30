<?php if (!empty($transactions)): ?>
    <div class="transactions-list">
        <?php foreach ($transactions as $transaction): ?>
            <div class="card transaction-card <?= $transaction['category_type'] ?> shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title"><?= htmlspecialchars($transaction['description']) ?></h5>
                            <p class="card-text mb-1">
                                <span class="badge bg-secondary"><?= htmlspecialchars($transaction['category_name']) ?></span>
                                <small class="text-muted"><?= date('d/m/Y', strtotime($transaction['date_transaction'])) ?></small>
                            </p>
                        </div>
                        <div class="d-flex align-items-center">
                            <h4 class="me-3 mb-0 <?= $transaction['category_type'] === 'revenu' ? 'revenu-amount' : 'depense-amount' ?>">
                                <?= $transaction['category_type'] === 'revenu' ? '+' : '-' ?><?= number_format($transaction['montant'], 2) ?> €
                            </h4>
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-outline-primary edit-transaction" 
                                        data-transaction='<?= json_encode($transaction) ?>'
                                        data-bs-toggle="modal" data-bs-target="#editTransactionModal">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger delete-transaction"
                                        data-id="<?= $transaction['id'] ?>"
                                        data-bs-toggle="modal" data-bs-target="#deleteTransactionModal">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="alert alert-info" role="alert">
        Aucune transaction trouvée pour la période sélectionnée.
    </div>
<?php endif; ?>