<?php

class HomeController extends BaseController {
    public function index() {
        $userModel = new User();
        $categoryModel = new Category();
        $latestUsers = $userModel->getLatest(5);
        $latestCategories = $categoryModel->getLatestPublic(5);
        $this->render('home', [
            'latestUsers' => $latestUsers,
            'latestCategories' => $latestCategories
        ]);
    }
}
