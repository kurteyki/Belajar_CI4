<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class User extends Seeder
{
    public function run()
    {
        $replacement = function ($matches) {
            $array = explode("|", $matches[1]);
            return $array[array_rand($array)];
        };

        // build random data
        $data = [];
        for ($i=0; $i < 10 ; $i++) { 

            $username = "{alexander|bizku|ciku|lazzy|waaazu|keila|omen}";
            $username_spin = preg_replace_callback("/\{([^}]+)\}/", $replacement, $username);
            $username_spin = $username_spin.$i;

            $data[] = [
                'username' => $username_spin,
                'password' => password_hash(1234, PASSWORD_DEFAULT),
                'email' => $username_spin.'@gmail.com',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),            
            ];
        }  

        $this->db->table('user')->insertBatch($data);
    }
}
