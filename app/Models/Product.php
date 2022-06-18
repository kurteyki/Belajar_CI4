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

        $products = $this->select('
            product.id,             
            product.name, 
            product.category, 
            product.price,
            product.photo,
            user.username
            ')
        ->join('user', 'product.id_user = user.id')
        ->orderBy('id','DESC')->findAll();
 
        // load helper
        helper('number');        
 
        // build data
        $data = [];
        foreach ($products as $product) {
            $data[] = array(
                'id' => $product['id'],
                'name' => $product['name'],
                'category' => $product['category'],
                'price' => number_to_currency($product['price'], "IDR", "id", 0),
                'photo' => base_url('uploads/'.$product['photo']),
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
 
 
}