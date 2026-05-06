<?php

class HomeController extends BaseController {
    public function index() {
        $userModel = new User();
        $categoryModel = new Category();
        $transactionModel = new Transaction();
        $latestUsers = $userModel->getLatest(5);
        $latestCategories = $categoryModel->getLatestPublic(5);
        $latestTransactions = $transactionModel->getLatest(5);
        $this->render('home', [
            'latestUsers' => $latestUsers,
            'latestCategories' => $latestCategories,
            'latestTransactions' => $latestTransactions
        ]);
    }
}
