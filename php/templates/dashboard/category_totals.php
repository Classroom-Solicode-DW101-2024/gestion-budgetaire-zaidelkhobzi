<div class="row mb-4">
    <div class="col-md-12">
        <h3>Somme totale par catégorie</h3>
    </div>
    <div class="col-md-6">
        <div class="summary-box">
            <h5>Revenus par catégorie</h5>
            <?php if (!empty($incomeByCategory)): ?>
                <ul class="list-group list-group-flush">
                    <?php foreach ($incomeByCategory as $cat): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?= htmlspecialchars($cat['category_name']) ?>
                            <span class="revenu-amount"><?= number_format($cat['total'], 2) ?> €</span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="text-muted">Aucun revenu enregistré.</p>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-md-6">
        <div class="summary-box">
            <h5>Dépenses par catégorie</h5>
            <?php if (!empty($expenseByCategory)): ?>
                <ul class="list-group list-group-flush">
                    <?php foreach ($expenseByCategory as $cat): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?= htmlspecialchars($cat['category_name']) ?>
                            <span class="depense-amount"><?= number_format($cat['total'], 2) ?> €</span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="text-muted">Aucune dépense enregistrée.</p>
            <?php endif; ?>
        </div>
    </div>
</div>