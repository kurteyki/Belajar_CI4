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
    
    public function getData($limit, $type, $sort, $search = null, $category = null) 
    {

        $builder = $this->select('
            product.id,             
            product.name, 
            product.category, 
            product.price,
            product.photo,
            user.username
            ')
        ->join('user', 'product.id_user = user.id');

        if (isset($search)) {
            $builder->Like('product.name', $search);
        }elseif (isset($category)) {
            $builder->where('category', $category);
        }

        if (empty($sort) OR $sort == 'latest') {
            $builder->orderBy('product.id','DESC');
        }elseif ($sort == 'oldest') {
            $builder->orderBy('product.id','ASC');
        }elseif ($sort == 'high-price') {
            $builder->orderBy('product.price','DESC');
        }elseif ($sort == 'low-price') {
            $builder->orderBy('product.price','ASC');
        }
        
        $products = $builder->paginate($limit, $type);

        // load helper
        helper('number');        

        // build data
        $data = [];
        foreach ($products as $product) {

            // if photo as url
            $photo = (filter_var($product['photo'], FILTER_VALIDATE_URL)) ? $product['photo'] : base_url('uploads/'.$product['photo']);

            $data[] = array(
                'id' => $product['id'],
                'name' => $product['name'],
                'category' => $product['category'],
                'price' => number_to_currency($product['price'], "IDR", "id", 0),
                'photo' => $photo,
                'owner' => $product['username'],
                );
        }   

        return $data;     
    }
    
    public function getDataForBootstrapTable($request)
    {

        $builder =  $this->select('id, name, price')->where('id_user', session('auth')['id']);
        
        // search query
        $builder->like('name', $request->getGet('search'));
        
        // sort query
        $builder->orderBy($request->getGet('sort'), $request->getGet('order'));  
        
        // paging query
        $builder->limit($request->getGet('limit'), $request->getGet('offset'));  
        
        $total = $builder->countAllResults(false); // set false for not reset query
        $products = $builder->get()->getResultArray();
        $total_filter = count($products);
        
        // load helper
        helper('number');
        helper('aeshash');        
        
        // build data
        $data = [];
        foreach ($products as $product) {
            $data[] = array(
                'hash' => aeshash('enc', $product['id'] , session('auth')['id'] ),
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
    
    public function getCategories()
    {
        $builder = $this->select('category')->distinct()->findAll();

        $category_list = [];
        foreach ($builder as $key => $value) {
            $category_list[] = $value['category'];
        }

        return $category_list;
    }    
    
}