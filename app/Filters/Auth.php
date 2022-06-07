<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class Auth implements FilterInterface
{

    public function before(RequestInterface $request, $arguments = null)
    {

        if (is_array($arguments) AND $arguments[0] == 'page') {
            if (session()->auth) {
                return redirect()->to(base_url('product'));       
            }
        }else{
            if (!session()->auth) {
                // if ajax request
                if ($request->isAJAX()) {
                    http_response_code(400);     
                    header('Content-Type: application/json; charset=utf-8');
                    die(json_encode([
                        'status' => false,
                        'response' => 'no authorize'                
                        ]));
                }else{

                    // if direct request
                    session()->setFlashdata('message', 'please login first');
                    return redirect()->to(base_url('login'));   
                }
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}