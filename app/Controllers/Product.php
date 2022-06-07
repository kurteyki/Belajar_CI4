<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Product extends BaseController
{
    public function index()
    {

        $data['title'] = 'Product';

        return view('product', $data);
    }

    public function read()
    {
        $productModel = new \App\Models\Product();
        $products = $productModel->getDataForBootstrapTable($this->request);        
        return $this->response->setJSON($products);
    }

    private function _post_product($is_update = false)
    {

        // validate input text
        $validationRule = [
            'name' => [
                'rules' => 'required'
            ],
            'category' => [
                'rules' => 'required'
            ],
            'price' => [
                'rules' => 'required'
            ]            
        ];

        if (!$this->validate($validationRule)) {
            $error = $this->validator->getErrors();
            $error_val = array_values($error);
            die(json_encode([
                'status' => false,
                'response' => $error_val[0]
            ])); 
        }           

        $data['name'] = $this->request->getPost('name');
        $data['category'] = $this->request->getPost('category');        
        $data['price'] = $this->request->getPost('price');    

        // ======== photo handle

        // if create and not have photo
        if (!$is_update AND !$this->request->getFile('photo')->isValid()) {
            die(json_encode([
                'status' => false,
                'response' => 'photo required'
            ])); 
        }        

        // check new photo exist
        $photo = $this->request->getFile('photo');      
        if ($photo->isValid()) {

            // validate input file
            $validationRule = [
                'photo' => [
                    'rules' => 'uploaded[photo]'
                    . '|is_image[photo]'
                    . '|mime_in[photo,image/jpg,image/jpeg,image/gif,image/png,image/webp]'
                    . '|max_size[photo,100]'
                    . '|max_dims[photo,1024,768]'
                ]                
            ];        

            if (!$this->validate($validationRule)) {
                $error = $this->validator->getErrors();
                $error_val = array_values($error);
                die(json_encode([
                    'status' => false,
                    'response' => $error_val[0]
                ])); 
            }             

            $file_name = $data['name'].'.'.$photo->getClientExtension();
            $dir_upload = './uploads/';
            $file_des = $dir_upload.$file_name;

            // if update
            if ($is_update) {
                // delete previous photo
                $prev_photo = $this->request->getPost('previous_photo');
                if (file_exists($dir_upload.$prev_photo)) {
                    unlink($dir_upload.$prev_photo);
                }
            }

            // then upload
            $photo->move('./uploads/', $file_name);
            $data['photo'] = $file_name;    
        }   

        // ======== photo handle

        return $data;        
    }

    public function create()
    {

        $data = $this->_post_product();

        $productModel = new \App\Models\Product();
        $productModel->insert($data);

        return $this->response->setJSON([
            'status' => true,
            'response' => 'Success create data '.$data['name']
        ]);
    }

    public function edit()
    {

        $id = $this->request->getPost('hash');

        $productModel = new \App\Models\Product();
        $product = $productModel->select('name,category,price,photo')->find($id);

        // build hash
        $product['hash'] = $id;

        return $this->response->setJSON([
            'status' => true,
            'response' => $product
        ]);
    }

    public function update()
    {

        $id = $this->request->getPost('hash');

        $data = $this->_post_product($id);

        $productModel = new \App\Models\Product();
        $productModel->update($id, $data);

        return $this->response->setJSON([
            'status' => true,
            'response' => 'Success update data '.$data['name']
        ]);
    }        

    public function delete()
    {

        $id = $this->request->getPost('hash');

        $productModel = new \App\Models\Product();
        // read first
        $product = $productModel->select('photo')->find($id);
        // then delete
        if ($productModel->delete($id) AND file_exists('./uploads/'.$product['photo'])) {
            // delete photo
            unlink('./uploads/'.$product['photo']);
        }

        return $this->response->setJSON([
            'status' => true,
            'response' => 'Success delete data '.$id
        ]);
    }

    public function delete_batch()
    {
        $ids = $this->request->getPost('ids');
        $ids_array = explode("','", $ids);
        $count = count($ids_array);

        $productModel = new \App\Models\Product();
        // read first
        $products = $productModel->select('id,photo')->find($ids_array);

        // then delete one by one
        foreach ($products as $product) {
            if ($productModel->delete($product['id']) AND file_exists('./uploads/'.$product['photo'])) {
                // delete photo
                unlink('./uploads/'.$product['photo']);
            }
        }

        return $this->response->setJSON([
            'status' => true,
            'response' => 'Success '. $count .' delete data '
        ]);
    }
}