<div class="modal fade" id="editTransactionModal" tabindex="-1" aria-labelledby="editTransactionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTransactionModalLabel">Modifier la transaction</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="">
                <div class="modal-body">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="transaction_id" id="edit_transaction_id">
                    
                    <div class="mb-3">
                        <label class="form-label">Type de transaction</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="edit_transaction_type" id="edit_typeRevenu" value="revenu">
                            <label class="form-check-label" for="edit_typeRevenu">Revenu</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="edit_transaction_type" id="edit_typeDepense" value="depense">
                            <label class="form-check-label" for="edit_typeDepense">Dépense</label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_category_id" class="form-label">Catégorie</label>
                        <select class="form-select" id="edit_category_id" name="category_id" required>
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
                        <label for="edit_montant" class="form-label">Montant</label>
                        <input type="number" class="form-control" id="edit_montant" name="montant" step="0.01" min="0.01" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description</label>
                        <input type="text" class="form-control" id="edit_description" name="description" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_date_transaction" class="form-label">Date</label>
                        <input type="date" class="form-control" id="edit_date_transaction" name="date_transaction" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>