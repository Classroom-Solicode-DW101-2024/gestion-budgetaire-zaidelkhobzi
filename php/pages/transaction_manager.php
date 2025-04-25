<?php
    session_start();
    require_once '../model/config.php';
    require_once '../functions/transaction_model.php';

    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit();
    }

    $userId = $_SESSION['user_id'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
        try {
            // Add new transaction
            if ($_POST['action'] === 'add') {
                $category_id = filter_input(INPUT_POST, 'category_id', FILTER_VALIDATE_INT);
                $montant = filter_input(INPUT_POST, 'montant', FILTER_VALIDATE_FLOAT);
                $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
                $date_transaction = filter_input(INPUT_POST, 'date_transaction', FILTER_SANITIZE_STRING);

                if (!$category_id || !$montant || !$description || !$date_transaction) {
                    throw new Exception('Invalid input data');
                }

                $success = addTransaction($pdo, $userId, $category_id, $montant, $description, $date_transaction);
                if (!$success) {
                    throw new Exception('Failed to add transaction');
                }

                header('Location: transaction_manager.php');
                exit();
            }

            // Update transaction
            elseif ($_POST['action'] === 'update') {
                $transactionId = filter_input(INPUT_POST, 'transaction_id', FILTER_VALIDATE_INT);
                $category_id = filter_input(INPUT_POST, 'category_id', FILTER_VALIDATE_INT);
                $montant = filter_input(INPUT_POST, 'montant', FILTER_VALIDATE_FLOAT);
                $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
                $date_transaction = filter_input(INPUT_POST, 'date_transaction', FILTER_SANITIZE_STRING);

                if (!$transactionId || !$category_id || !$montant || !$description || !$date_transaction) {
                    throw new Exception('Invalid input data');
                }

                $success = updateTransaction($pdo, $userId, $transactionId, $category_id, $montant, $description, $date_transaction);
                if (!$success) {
                    throw new Exception('Failed to update transaction');
                }

                header('Location: transaction_manager.php');
                exit();
            }

            // Delete transaction
            elseif ($_POST['action'] === 'delete') {
                $transactionId = filter_input(INPUT_POST, 'transaction_id', FILTER_VALIDATE_INT);

                if (!$transactionId) {
                    throw new Exception('Invalid transaction ID');
                }

                $success = deleteTransaction($pdo, $userId, $transactionId);
                if (!$success) {
                    throw new Exception('Failed to delete transaction');
                }

                header('Location: transaction_manager.php');
                exit();
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            $error = urlencode($e->getMessage());
        }
    }

    $year = isset($_POST['year']) ? $_POST['year'] : 'all';
    $month = isset($_POST['month']) ? $_POST['month'] : 'all';

    $transactions = getTransactions($pdo, $userId, $year, $month);

    $years = getTransactionYears($pdo, $userId);

    $categories = getCategories($pdo);

    $revenueCategories = array_filter($categories, fn($cat) => $cat['type'] === 'revenu');
    $expenseCategories = array_filter($categories, fn($cat) => $cat['type'] === 'depense');

    function date_fr($format, $timestamp) {
        $english_months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
        $french_months = array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre');
        return str_replace($english_months, $french_months, date($format, $timestamp));
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Transactions</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../css/transactions.css">
</head>
<body>
    <header>
        <div class="container">
            <a href="index.html">
                <svg style="width: 60px; cursor: pointer;" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="50" cy="50" r="45" fill="#f0f4f8" stroke="#2c5282" stroke-width="2"/>
                    <path d="M55,40 h-15 M55,50 h-18 M55,60 h-15" stroke="#2c5282" stroke-width="3" stroke-linecap="round"/>
                    <path d="M40,35 v30" stroke="#2c5282" stroke-width="3" stroke-linecap="round"/>
                    <polyline points="55,55 65,45 75,50 85,35" fill="none" stroke="#4299e1" stroke-width="2.5" stroke-linejoin="round"/>
                    <text x="50" y="75" font-family="Arial, sans-serif" font-size="12" font-weight="bold" text-anchor="middle" fill="#2c5282">GB</text>
                </svg>
            </a>
            <nav>
                <a href="index.html">Accueil</a>
                <a href="dashboard.php">Tableau de Bord</a>
                <a href="login.php">Connexion</a>
                <a href="register.html">Inscription</a>
            </nav>
        </div>
    </header>

    <div class="container py-4">
        <h1 class="mb-4">Gestion des Transactions</h1>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <!-- Filter Form -->
        <div class="filter-form">
            <form method="post" action="" class="row align-items-end">
                <div class="col-md-4 mb-2">
                    <label for="year" class="form-label">Année</label>
                    <select name="year" id="year" class="form-select">
                        <option value="all">Toutes les années</option>
                        <?php foreach ($years as $yr): ?>
                            <option value="<?= $yr ?>" <?= $yr == $year ? 'selected' : '' ?>>
                                <?= $yr ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4 mb-2">
                    <label for="month" class="form-label">Mois</label>
                    <select name="month" id="month" class="form-select">
                        <option value="all">Tous les mois</option>
                        <?php for ($i = 1; $i <= 12; $i++): ?>
                            <option value="<?= sprintf('%02d', $i) ?>" <?= sprintf('%02d', $i) == $month ? 'selected' : '' ?>>
                                <?= date_fr('F', mktime(0, 0, 0, $i, 1)) ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-4 mb-2">
                    <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                </div>
            </form>
        </div>

        <!-- Add New Transaction Button -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Historique des transactions</h2>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addTransactionModal">
                <i class="fas fa-plus"></i> Nouvelle transaction
            </button>
        </div>

        <!-- Transactions List -->
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

        <!-- Add Transaction Modal -->
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

        <!-- Edit Transaction Modal -->
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

        <!-- Delete Transaction Modal -->
        <div class="modal fade" id="deleteTransactionModal" tabindex="-1" aria-labelledby="deleteTransactionModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteTransactionModalLabel">Confirmer la suppression</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Êtes-vous sûr de vouloir supprimer cette transaction ? Cette action est irréversible.
                    </div>
                    <div class="modal-footer">
                        <form method="post" action="">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="transaction_id" id="delete_transaction_id">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-danger">Supprimer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle category options based on transaction type for Add form
            document.querySelectorAll('input[name="transaction_type"]').forEach(function(radio) {
                radio.addEventListener('change', function() {
                    toggleCategoryOptions();
                });
            });

            // Initial toggle for add form
            toggleCategoryOptions();

            // Edit transaction
            document.querySelectorAll('.edit-transaction').forEach(function(button) {
                button.addEventListener('click', function() {
                    const transaction = JSON.parse(this.getAttribute('data-transaction'));
                    document.getElementById('edit_transaction_id').value = transaction.id;

                    if (transaction.category_type === 'revenu') {
                        document.getElementById('edit_typeRevenu').checked = true;
                    } else {
                        document.getElementById('edit_typeDepense').checked = true;
                    }

                    document.getElementById('edit_category_id').value = transaction.category_id;
                    document.getElementById('edit_montant').value = transaction.montant;
                    document.getElementById('edit_description').value = transaction.description;
                    document.getElementById('edit_date_transaction').value = transaction.date_transaction;

                    toggleEditCategoryOptions();
                });
            });

            // Toggle category options in edit form
            document.querySelectorAll('input[name="edit_transaction_type"]').forEach(function(radio) {
                radio.addEventListener('change', function() {
                    toggleEditCategoryOptions();
                });
            });

            // Delete transaction
            document.querySelectorAll('.delete-transaction').forEach(function(button) {
                button.addEventListener('click', function() {
                    const transactionId = this.getAttribute('data-id');
                    document.getElementById('delete_transaction_id').value = transactionId;
                });
            });

            // Function to toggle category options based on transaction type
            function toggleCategoryOptions() {
                const transactionType = document.querySelector('input[name="transaction_type"]:checked').value;
                const categorySelect = document.getElementById('category_id');

                for (let i = 0; i < categorySelect.options.length; i++) {
                    const option = categorySelect.options[i];
                    const optgroup = option.parentNode;

                    if (optgroup.tagName === 'OPTGROUP') {
                        if ((transactionType === 'revenu' && optgroup.label === 'Revenus') || 
                            (transactionType === 'depense' && optgroup.label === 'Dépenses')) {
                            option.style.display = '';
                        } else {
                            option.style.display = 'none';
                        }
                    }
                }

                // Set first visible option as selected
                for (let i = 0; i < categorySelect.options.length; i++) {
                    if (categorySelect.options[i].style.display !== 'none') {
                        categorySelect.selectedIndex = i;
                        break;
                    }
                }
            }

            // Function to toggle category options in edit form
            function toggleEditCategoryOptions() {
                const transactionType = document.querySelector('input[name="edit_transaction_type"]:checked').value;
                const categorySelect = document.getElementById('edit_category_id');

                for (let i = 0; i < categorySelect.options.length; i++) {
                    const option = categorySelect.options[i];
                    const optgroup = option.parentNode;

                    if (optgroup.tagName === 'OPTGROUP') {
                        if ((transactionType === 'revenu' && optgroup.label === 'Revenus') || 
                            (transactionType === 'depense' && optgroup.label === 'Dépenses')) {
                            option.style.display = '';
                        } else {
                            option.style.display = 'none';
                        }
                    }
                }

                // Check if current selection is valid
                const currentOption = categorySelect.options[categorySelect.selectedIndex];
                const currentOptgroup = currentOption.parentNode;

                if ((transactionType === 'revenu' && currentOptgroup.label !== 'Revenus') || 
                    (transactionType === 'depense' && currentOptgroup.label !== 'Dépenses')) {
                    // Set first visible option as selected
                    for (let i = 0; i < categorySelect.options.length; i++) {
                        if (categorySelect.options[i].style.display !== 'none') {
                            categorySelect.selectedIndex = i;
                            break;
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>