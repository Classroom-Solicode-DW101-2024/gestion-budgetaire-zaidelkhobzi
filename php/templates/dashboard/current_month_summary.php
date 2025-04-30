<div class="row mb-4">
    <div class="col-md-12">
        <h3>Résumé du mois en cours (<?= date_fr('F Y', mktime(0, 0, 0, $currentMonth, 1)) ?>)</h3>
    </div>
    <div class="col-md-6">
        <div class="summary-box text-center">
            <h5>Revenus du mois</h5>
            <h3 class="revenu-amount"><?= number_format($monthTotals['month_revenus'] ?? 0, 2) ?> €</h3>
        </div>
    </div>
    <div class="col-md-6">
        <div class="summary-box text-center">
            <h5>Dépenses du mois</h5>
            <h3 class="depense-amount"><?= number_format($monthTotals['month_depenses'] ?? 0, 2) ?> €</h3>
        </div>
    </div>
</div>