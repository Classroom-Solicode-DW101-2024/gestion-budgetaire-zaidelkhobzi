<div class="row mb-4">
    <div class="col-md-12">
        <h3>Transactions les plus élevées du mois</h3>
    </div>
    <div class="col-md-6">
        <div class="summary-box">
            <h5>Revenu le plus élevé</h5>
            <?php if ($highestIncome): ?>
                <p class="mb-1"><strong><?= htmlspecialchars($highestIncome['description']) ?></strong></p>
                <p class="mb-1">Catégorie: <?= htmlspecialchars($highestIncome['category_name']) ?></p>
                <p class="mb-1">Date: <?= date('d/m/Y', strtotime($highestIncome['date_transaction'])) ?></p>
                <p class="revenu-amount"><?= number_format($highestIncome['montant'], 2) ?> €</p>
            <?php else: ?>
                <p class="text-muted">Aucun revenu ce mois-ci.</p>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-md-6">
        <div class="summary-box">
            <h5>Dépense la plus élevée</h5>
            <?php if ($highestExpense): ?>
                <p class="mb-1"><strong><?= htmlspecialchars($highestExpense['description']) ?></strong></p>
                <p class="mb-1">Catégorie: <?= htmlspecialchars($highestExpense['category_name']) ?></p>
                <p class="mb-1">Date: <?= date('d/m/Y', strtotime($highestExpense['date_transaction'])) ?></p>
                <p class="depense-amount"><?= number_format($highestExpense['montant'], 2) ?> €</p>
            <?php else: ?>
                <p class="text-muted">Aucune dépense ce mois-ci.</p>
            <?php endif; ?>
        </div>
    </div>
</div>