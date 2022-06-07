<?php

namespace App\Models;

use CodeIgniter\Model;

class Product extends Model
{
    protected $table            = 'product';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getData()
    {

        $products = $this->orderBy('id','DESC')->findAll();

        // load helper
        helper('number');        

        // build data
        $data = [];
        foreach ($products as $product) {
            $data[] = array(
                'hash' => $product['id'],
                'id' => $product['id'],
                'name' => $product['name'],
                'category' => $product['category'],
                'price' => number_to_currency($product['price'], "IDR", "id", 0),
                'photo' => base_url('uploads/'.$product['photo'])
            );
        }   

        return $data;     
    }

    public function getDataForBootstrapTable($request)
    {

        $builder =  $this->select('id, name, price');       

        // search query
        $builder->like('name', $request->getGet('search'));

        // sort query
        $builder->orderBy($request->getGet('sort'), $request->getGet('order'));  

        // paging query
        $builder->limit($request->getGet('limit'), $request->getGet('offset'));  

        $products = $builder->get()->getResultArray();
        $total = $builder->countAllResults();
        $total_filter = count($products);

        // load helper
        helper('number');

        // build data
        $data = [];
        foreach ($products as $product) {
            $data[] = array(
                'hash' => $product['id'],
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => number_to_currency($product['price'], "IDR", "id", 0),
            );
        }

        return [
            'total' => $total,
            'totalNotFiltered' => $total_filter,
            'rows' => $data
        ];   
    }


}