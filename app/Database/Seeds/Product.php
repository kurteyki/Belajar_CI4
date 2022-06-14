<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Product extends Seeder
{
    public function run()
    {

        $replacement = function ($matches) {
            $array = explode("|", $matches[1]);
            return $array[array_rand($array)];
        };

        // get all user id
        $user_ids = $this->db->table('user')->select('id')->get()->getResultArray();

        // if not exist user_ids
        if (!$user_ids) {
            $user_ids = [1]; // set id_user to 1
        }

        // build random data
        $data = [];
        for ($i=0; $i < 100000 ; $i++) { 

            $name = "{Jual|Jasa|Tutor|Script|Great} {membuat|menjadi|pasti} {A|B|C|D|E|F|G|H|I|J|K|L|M|N|O|P|Q|R|S|T|U|V|W|X|Y|Z}";
            $name_spin = preg_replace_callback("/\{([^}]+)\}/", $replacement, $name); 

            $category = "{Script|Account|Jasa|Tutorial}";
            $category_spin = preg_replace_callback("/\{([^}]+)\}/", $replacement, $category);            
            $data[] = [
                'id_user' => array_rand($user_ids) + 1,
                'name' => $name_spin,
                'category' => $category_spin,
                'price' => rand(10000,1000000),
                'photo' => 'https://via.placeholder.com/265x150/83b5ff/000000?text=Product%20Image',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),            
            ];
        }  

        $this->db->table('product')->insertBatch($data);
    }
}