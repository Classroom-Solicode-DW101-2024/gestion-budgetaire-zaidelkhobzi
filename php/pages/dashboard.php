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
    <link rel="stylesheet" href="../../public/css/dashboard.css">
</head>
<body>
    <?php include "../includes/header_dashboard.php" ?>

    <div class="container py-4">
        <h1 class="mb-4">Tableau de Bord</h1>

        <!-- Balance Summary -->
        <?php include "../templates/dashboard/balance_summary.php"; ?>

        <!-- Current Month Summary -->
        <?php include "../templates/dashboard/current_month_summary.php"; ?>

        <!-- Total by Category -->
        <?php include "../templates/dashboard/category_totals.php"; ?>

        <!-- Highest Income and Expense -->
        <?php include "../templates/dashboard/highest_transactions.php"; ?>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>