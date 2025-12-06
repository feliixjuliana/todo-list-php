<?php namespace App\Controllers;

use App\Models\UserModel;
use App\Controllers\BaseController;

class LoginController extends BaseController{
    protected $userModel;
    protected $session;

    public function __construct(){
        $this->userModel = new UserModel();
        $this->session = session();
        helper(['url','form']);
    }

    public function index(){
        return view('loginView');
    }

    public function auth(){
        $post = $this->request->getPost();

        if (empty($post['user_login']) || empty($post['user_password'])) {
            return redirect()->back()->with('error', 'Preencha login e senha.')->withInput();
        }

        $user = $this->userModel->where('user_login', $post['user_login'])->first();

        if (!$user || !password_verify($post['user_password'], $user['user_password'])) {
            return redirect()->back()->with('error', 'Credenciais inválidas.')->withInput();
        }

        $this->session->set([
            'user_id' => $user['user_id'],
            'user_login' => $user['user_login']
        ]);

        return redirect()->to(site_url('tasks'));
    }

    public function logout(){
        $this->session->destroy();
        return redirect()->to(site_url('/'));
    }
}
