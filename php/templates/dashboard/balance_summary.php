<div class="row mb-4">
    <div class="col-md-4">
        <div class="summary-box text-center">
            <h5>Solde actuel</h5>
            <h3 class="<?= $balance >= 0 ? 'balance-positive' : 'balance-negative' ?>">
                <?= number_format($balance, 2) ?> €
            </h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="summary-box text-center">
            <h5>Total des revenus</h5>
            <h3 class="revenu-amount"><?= number_format($totals['total_revenus'] ?? 0, 2) ?> €</h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="summary-box text-center">
            <h5>Total des dépenses</h5>
            <h3 class="depense-amount"><?= number_format($totals['total_depenses'] ?? 0, 2) ?> €</h3>
        </div>
    </div>
</div>