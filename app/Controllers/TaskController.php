<?php namespace App\Controllers;

use App\Models\TaskModel;
use App\Controllers\BaseController;

class TaskController extends BaseController{
    protected $taskModel;
    protected $session;

    public function __construct(){
        $this->taskModel = new TaskModel();
        $this->session = session();
        helper(['url','form']);
    }

    public function index(){
        $userId = $this->session->get('user_id');
        if (!$userId) return redirect()->to(site_url('login'));

        $tasks = $this->taskModel->where('user_id', $userId)->findAll();
        return view('taskView', ['tasks' => $tasks]);
    }

    public function store(){
        $userId = $this->session->get('user_id');
        if (!$userId) return redirect()->to(site_url('login'));

        $post = $this->request->getPost();

        $this->taskModel->insert([
            'user_id'     => $userId,
            'name'        => $post['name'] ?? '',
            'description' => $post['description'] ?? '',
            'start_date'  => $post['start_date'] ?? null,
            'end_date'    => $post['end_date'] ?? null,
            'status'      => 'pendente'
        ]);

        return redirect()->to(site_url('tasks'));
    }

    public function updateStatus($id){
        $userId = $this->session->get('user_id');
        if (!$userId) return redirect()->to(site_url('login'));

        $task = $this->taskModel->find($id);
        if (!$task || $task['user_id'] != $userId) return redirect()->back();

        $newStatus = $this->request->getPost('status');

        $this->taskModel->update($id, [
            'status' => $newStatus
        ]);

        return redirect()->to(site_url('tasks'));
    }

    public function delete($id){
        $userId = $this->session->get('user_id');
        if (!$userId) return redirect()->to(site_url('login'));

        $task = $this->taskModel->find($id);
        if (!$task || $task['user_id'] != $userId) return redirect()->back();

        $this->taskModel->delete($id);
        return redirect()->to(site_url('tasks'));
    }

    public function eventsJson(){
        $userId = $this->session->get('user_id');
        if (!$userId) return $this->response->setJSON([]);

        $tasks = $this->taskModel->where('user_id', $userId)->findAll();

        $events = [];
        foreach ($tasks as $t) {
            $events[] = [
                'id'     => $t['task_id'],
                'title'  => $t['name'],
                'start'  => $t['start_date'],
                'end'    => $t['end_date'],
                'status' => $t['status']
            ];
        }

        return $this->response->setJSON($events);
    }
}
