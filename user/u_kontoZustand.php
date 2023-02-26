<?php

// Set initial account balance
if (!isset($_SESSION['balance'])) {
    $_SESSION['balance'] = 1000;
}

// Process deposit request
if (isset($_POST['deposit_amount'])) {
    $amount = (float)$_POST['deposit_amount'];
    $_SESSION['balance'] += $amount;
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Process withdrawal request
if (isset($_POST['withdrawal_amount'])) {
    $amount = (float)$_POST['withdrawal_amount'];
    if ($amount > $_SESSION['balance']) {
        $error = 'Unzureichende finanzielle Mittel';
    } else {
        $_SESSION['balance'] -= $amount;
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }
}
?>