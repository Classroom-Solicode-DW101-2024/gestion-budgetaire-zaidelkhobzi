<?php
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