<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Homepage extends BaseController
{
	public function index()
	{

		// get parameter
		$page = $this->request->getGet('page_product');
		$search = esc($this->request->getGet('q'));
		$data['search'] = $search;
		$sort = esc($this->request->getGet('sort'));		
		$data['sort'] = $sort;	
		$category = esc($this->request->getGet('category'));		
		$data['category'] = $category;				

		// load model
		$productModel = new \App\Models\Product();

		// data category
		$data['categories'] = $productModel->getCategories();

	    // detect if have search parameter
		if ($search) {
			$data['products'] = $productModel->getData(12, 'product', $sort, $search);            
		}elseif ($category) {
			$data['products'] = $productModel->getData(12, 'product', $sort, null, $category);            
		}else{
			$data['products'] = $productModel->getData(12, 'product', $sort);
		}		
		$data['pager'] = $productModel->pager;

		// build title
		if ($search) {
			if ($page) {
				$data['title'] = 'Search : '.$search . ' Page '.$page;
			}else{
				$data['title'] = 'Search : '. $search;
			}
		}elseif ($category) {
			if ($page) {
				$data['title'] = 'Category : '.$category . ' Page '.$page;
			}else{
				$data['title'] = 'Category : '. $category;
			}         
		}else{
			if ($page) {
				$data['title'] = 'Product - Page '.$page;
			}else{
				$data['title'] = 'Homepage';	    		
			}
		}	

		// build response
		if ($this->request->isAjax()) {
			// resposne for json
			return $this->response->setJSON([
				'title' => $data['title'],
				'content' => view('homepage/product', $data)
				]);
		}else{
			// respnse for html
			return view('homepage', $data);
		}
	}
}