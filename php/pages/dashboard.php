<?php
    session_start();
    require_once '../model/config.php';

    // Check if user is logged in
    if (!isset($_SESSION['id'])) {
        header('Location: login.php');
        exit();
    }

    $userId = $_SESSION['id'];

    // Process transaction form
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['action'])) {
            // Add new transaction
            if ($_POST['action'] === 'add') {
                $category_id = $_POST['category_id'];
                $montant = floatval($_POST['montant']);
                $description = $_POST['description'];
                $date_transaction = $_POST['date_transaction'];
                
                $sql = "INSERT INTO transactions (user_id, category_id, montant, description, date_transaction) 
                VALUES (:id, :category_id, :montant, :description, :date_transaction)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(":id", $userId);
                $stmt->bindParam(":category_id", $category_id);
                $stmt->bindParam(":montant", $montant);
                $stmt->bindParam(":description", $description);
                $stmt->bindParam(":date_transaction", $date_transaction);
                $stmt->execute();
                
                header('Location: dashboard.php');
                exit();
            }

            /** floatval
             * The floatval() function in PHP converts a value to a floating-point number. It takes a variable (like a string or integer) and returns its float representation. For example, if you pass "123.45", it returns 123.45 as a float.
             * This is useful when you need to perform numerical calculations with form input, as form data is usually sent as strings.
             * Empty arrays return 0, non-empty arrays return 1.
             * If the input isn’t a valid number (e.g., "abc"), floatval() returns 0.0. If the input is empty or not set, you should check it first to avoid issues:
                ** $montant = isset($_POST['montant']) ? floatval($_POST['montant']) : 0.0;
            * Exemple simple:
                $valeur1 = "12.34";
                $valeur2 = "abc123.45xyz";
                $valeur3 = 10;
                $valeur4 = 'The122.34343';

                echo floatval($valeur1); // 12.34
                echo floatval($valeur2); // 0
                echo floatval($valeur3); // 10
                echo floatval($valeur4); // 0

                ** Détails :
                    o floatval() ignore les caractères non numériques au début de la chaîne.
                    o Il prend les chiffres jusqu’à ce qu’il rencontre quelque chose qui ne peut pas être interprété comme un nombre valide.
            * Cas particuliers:
                o echo floatval("abc");      // 0
                o echo floatval("");         // 0
                o echo floatval(null);       // 0
                o echo floatval(true);       // 1
                o echo floatval(false);      // 0
            * Alternative:
                ** Tu peux aussi forcer le cast:
                    o $float = (float) $value;
            */
            
            // Update transaction
            elseif ($_POST['action'] === 'update') {
                $transactionId = $_POST['transaction_id'];
                $category_id = $_POST['category_id'];
                $montant = floatval($_POST['montant']);
                $description = $_POST['description'];
                $date_transaction = $_POST['date_transaction'];
                
                $sql = "UPDATE transactions 
                SET category_id = :category_id, montant = :montant, description = :description, date_transaction = :date_transaction 
                WHERE id = :transaction_id AND user_id = :user_id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':category_id', $category_id);
                $stmt->bindParam(':montant', $montant);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':date_transaction', $date_transaction);
                $stmt->bindParam(':transaction_id', $transactionId);
                $stmt->bindParam(':user_id', $userId);
                $stmt->execute();
                
                header('Location: dashboard.php');
                exit();
            }
            
            // Delete transaction
            elseif ($_POST['action'] === 'delete') {
                $transactionId = $_POST['transaction_id'];
                
                $sql = "DELETE FROM transactions WHERE id = ? AND id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$transactionId, $userId]);
                
                header('Location: dashboard.php');
                exit();
            }
        }
    }

    // Get filter parameters
    $year = isset($_POST['year']) ? $_POST['year'] : date('Y');
    $month = isset($_GET['month']) ? $_GET['month'] : date('m');

    // Get transactions based on filter
    $sql = "SELECT t.*, c.nom as category_name, c.type as category_type 
    FROM transactions t 
    JOIN categories c ON t.category_id = c.id 
    WHERE t.user_id = :user_id ";
    $params = [':user_id' => $userId];

    if ($year != 'all') {
        $sql .= "AND YEAR(t.date_transaction) = :year ";
        $params[':year'] = $year;
    }

    if ($month != 'all') {
        $sql .= "AND MONTH(t.date_transaction) = :month ";
        $params[':month'] = $month;
    }

    $sql .= "ORDER BY t.date_transaction DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calculate balance
    $sql = "SELECT 
    SUM(CASE WHEN c.type = 'revenu' THEN t.montant ELSE 0 END) as total_revenus,
    SUM(CASE WHEN c.type = 'depense' THEN t.montant ELSE 0 END) as total_depenses
    FROM transactions t 
    JOIN categories c ON t.category_id = c.id 
    WHERE t.id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$userId]);
    $totals = $stmt->fetch(PDO::FETCH_ASSOC);

    $balance = $totals['total_revenus'] - $totals['total_depenses'];

    // Get available years for filter
    $sql = "SELECT DISTINCT YEAR(date_transaction) as year FROM transactions WHERE id = ? ORDER BY year DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$userId]);
    $years = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Get categories
    $sql = "SELECT * FROM categories ORDER BY type, nom";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Group categories by type
    $revenueCategories = array_filter($categories, function($cat) {
        return $cat['type'] === 'revenu';
    });

    $expenseCategories = array_filter($categories, function($cat) {
        return $cat['type'] === 'depense';
    });
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Transactions</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .balance-positive {
            color: green;
            font-weight: bold;
        }
        .balance-negative {
            color: red;
            font-weight: bold;
        }
        .revenu-amount {
            color: green;
        }
        .depense-amount {
            color: red;
        }
        .transaction-card {
            margin-bottom: 15px;
            border-left: 5px solid #ccc;
        }
        .transaction-card.revenu {
            border-left-color: green;
        }
        .transaction-card.depense {
            border-left-color: red;
        }
        .filter-form {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .summary-box {
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <h1 class="mb-4">Gestion des Transactions</h1>
        
        <!-- Status messages -->
        <?php if (isset($_GET['status'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php 
                    switch($_GET['status']) {
                        case 'added': echo 'Transaction ajoutée avec succès!'; break;
                        case 'updated': echo 'Transaction mise à jour avec succès!'; break;
                        case 'deleted': echo 'Transaction supprimée avec succès!'; break;
                    }
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <!-- Balance Summary -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="summary-box text-center shadow-sm">
                    <h5>Solde actuel</h5>
                    <h3 class="<?= $balance >= 0 ? 'balance-positive' : 'balance-negative' ?>">
                        <?= number_format($balance, 2) ?> €
                    </h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="summary-box text-center shadow-sm">
                    <h5>Total des revenus</h5>
                    <h3 class="revenu-amount"><?= number_format($totals['total_revenus'], 2) ?> €</h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="summary-box text-center shadow-sm">
                    <h5>Total des dépenses</h5>
                    <h3 class="depense-amount"><?= number_format($totals['total_depenses'], 2) ?> €</h3>
                </div>
            </div>
        </div>
        
        <!-- Filter Form -->
        <div class="filter-form shadow-sm">
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
    </div>
    
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
                                <label class="form-check-label" for="typeRevenu">
                                    Revenu
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="transaction_type" id="typeDepense" value="depense">
                                <label class="form-check-label" for="typeDepense">
                                    Dépense
                                </label>
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
                                <label class="form-check-label" for="edit_typeRevenu">
                                    Revenu
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="edit_transaction_type" id="edit_typeDepense" value="depense">
                                <label class="form-check-label" for="edit_typeDepense">
                                    Dépense
                                </label>
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
                    
                    // Toggle categories in edit form
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
    
    <?php
    // Function for French month names
    function date_fr($format, $timestamp) {
        $english_months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
        $french_months = array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre');
        
        return str_replace($english_months, $french_months, date($format, $timestamp));
    }
    ?>
</body>
</html>