<?php

class HomeController extends BaseController {
    public function index() {
        $userModel = new User();
        $categoryModel = new Category();
        $transactionModel = new Transaction();
        $exchangeRateModel = new ExchangeRate();
        $walletModel = new Wallet();
        
        $latestUsers = $userModel->getLatest(5);
        $latestCategories = $categoryModel->getLatestPublic(5);
        $latestTransactions = $transactionModel->getLatest(5);
        $exchangeRates = $exchangeRateModel->getOrUpdate();
        
        $totalBalance = 0;
        $userBalance = null;
        if (isset($_SESSION['user_id'])) {
            $userBalance = $walletModel->getTotalBalance($_SESSION['user_id']);
        }
        
        $this->render('home', [
            'latestUsers' => $latestUsers,
            'latestCategories' => $latestCategories,
            'latestTransactions' => $latestTransactions,
            'exchangeRates' => $exchangeRates,
            'userBalance' => $userBalance
        ]);
    }
}
