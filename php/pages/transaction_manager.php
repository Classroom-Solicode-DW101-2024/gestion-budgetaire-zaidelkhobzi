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
            require "../controllers/transaction_controller.php";
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
    <link rel="stylesheet" href="../../public/css/transactions.css">
</head>
<body>
    <?php include "../includes/header_transaction.php" ?>

    <div class="container py-4">
        <h1 class="mb-4">Gestion des Transactions</h1>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <!-- Filter Form -->
        <?php include "../templates/transactions/filter_form.php"; ?>

        <!-- Add New Transaction Button -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Historique des transactions</h2>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addTransactionModal">
                <i class="fas fa-plus"></i> Nouvelle transaction
            </button>
        </div>

        <!-- Transactions List -->
        <?php include "../templates/transactions/transactions_list.php"; ?>

        <!-- Add Transaction Modal -->
        <?php include "../templates/modals/add_transaction_modal.php"; ?>
        <!-- Edit Transaction Modal -->
        <?php include "../templates/modals/edit_transaction_modal.php"; ?>
        <!-- Delete Transaction Modal -->
        <?php include "../templates/modals/delete_transaction_modal.php"; ?>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="../../public/js/transaction_manager.js"></script>
</body>
</html>