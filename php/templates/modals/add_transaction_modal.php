<div class="modal fade" id="addTransactionModal" tabindex="-1" aria-labelledby="addTransactionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTransactionModalLabel">Ajouter une transaction</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    
                    <div class="mb-3">
                        <label class="form-label">Type de transaction</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="transaction_type" id="typeRevenu" value="revenu" checked>
                            <label class="form-check-label" for="typeRevenu">Revenu</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="transaction_type" id="typeDepense" value="depense">
                            <label class="form-check-label" for="typeDepense">Dépense</label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Catégorie</label>
                        <select class="form-select" id="category_id" name="category_id" required>
                            <optgroup label="Revenus" class="revenu-categories">
                                <?php foreach ($revenueCategories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nom']) ?></option>
                                <?php endforeach; ?>
                            </optgroup>
                            <optgroup label="Dépenses" class="depense-categories">
                                <?php foreach ($expenseCategories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nom']) ?></option>
                                <?php endforeach; ?>
                            </optgroup>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="montant" class="form-label">Montant</label>
                        <input type="number" class="form-control" id="montant" name="montant" step="0.01" min="0.01" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <input type="text" class="form-control" id="description" name="description" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="date_transaction" class="form-label">Date</label>
                        <input type="date" class="form-control" id="date_transaction" name="date_transaction" value="<?= date('Y-m-d') ?>" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>