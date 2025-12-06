<?php namespace App\Controllers;

use App\Models\UserModel;
use App\Controllers\BaseController;

class RegisterController extends BaseController{
    protected $userModel;
    protected $session;

    public function __construct(){
        $this->userModel = new UserModel();
        $this->session = session();
        helper(['url','form']);
    }

    public function index(){
        return view('registerView');
    }

    public function store(){
        $post = $this->request->getPost();

        if (empty($post['user_login']) || empty($post['user_password'])) {
            return redirect()->back()->with('error', 'Preencha todos os campos.')->withInput();
        }

        if ($this->userModel->where('user_login', $post['user_login'])->first()) {
            return redirect()->back()->with('error', 'Login já existe.')->withInput();
        }

        $this->userModel->insert([
            'user_login' => $post['user_login'],
            'user_password' => password_hash($post['user_password'], PASSWORD_DEFAULT)
        ]);

        return redirect()->to(site_url('login'))->with('success', 'Conta criada.');
    }
}
