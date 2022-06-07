<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Homepage extends BaseController
{
    public function index()
    {

        $data['title'] = 'Homepage';

        $productModel = new \App\Models\Product();
        $data['products'] = $productModel->getData();

        return view('homepage', $data);
    }
}