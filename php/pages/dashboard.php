<?php
    session_start();
    require_once '../model/config.php';
    require_once '../functions/db_functions.php';

    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit();
    }

    $userId = $_SESSION['user_id'];

    $totals = getBalanceTotals($pdo, $userId);
    $balance = ($totals['total_revenus'] ?? 0) - ($totals['total_depenses'] ?? 0);

    $currentYear = date('Y');
    $currentMonth = date('m');
    $monthTotals = getCurrentMonthTotals($pdo, $userId, $currentYear, $currentMonth);

    $categoryTotals = getCategoryTotals($pdo, $userId);

    $incomeByCategory = array_filter($categoryTotals, fn($cat) => $cat['category_type'] === 'revenu');
    $expenseByCategory = array_filter($categoryTotals, fn($cat) => $cat['category_type'] === 'depense');

    $highestIncome = getHighestTransaction($pdo, $userId, $currentYear, $currentMonth, 'revenu');
    $highestExpense = getHighestTransaction($pdo, $userId, $currentYear, $currentMonth, 'depense');

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
    <title>Tableau de Bord</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../css/dashboard.css">
    <style>
        .summary-box {
            padding: 20px;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .list-group-item {
            background-color: transparent;
            border: none;
            padding: 10px 0;
        }
        .revenu-amount {
            color: #28a745;
        }
        .depense-amount {
            color: #dc3545;
        }
        .balance-positive {
            color: #28a745;
        }
        .balance-negative {
            color: #dc3545;
        }
    </style>
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
                <a href="transaction_manager.php">Transactions</a>
                <a href="login.php">Connexion</a>
                <a href="register.html">Inscription</a>
            </nav>
        </div>
    </header>

    <div class="container py-4">
        <h1 class="mb-4">Tableau de Bord</h1>

        <!-- Balance Summary -->
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

        <!-- Current Month Summary -->
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

        <!-- Total by Category -->
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

        <!-- Highest Income and Expense -->
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
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>