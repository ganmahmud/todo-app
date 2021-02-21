<?php
namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\TodoModel;


class TodoController extends BaseController{
    use ResponseTrait;
    
    public function index(){    
       //get all todos
        $model = new TodoModel();
        $data= $model->findAll();
        return $this->respond($data);
    }

    // get single todo
    public function todo($id = null)
    {
        $model = new TodoModel();
        $data = $model->getWhere(['id' => $id])->getResult();
        if($data){
            return $this->respond($data);
        }else{
            return $this->failNotFound('No Todo Item Found with id '.$id);
        }
    }

    // create a todo item
    public function create()
    {
        $model = new TodoModel();
        $data = [
            'title' => $this->request->getVar('title'),
            'completed' => $this->request->getVar('completed')
        ];
        
        $op = $model->insert($data);
        if($op){
            $response = [
            'status'   => 201,
                'error'    => false,
                'messages' => [
                    'success' => 'Todo created successfully'
                ]
            ];
        }
        else{
            $response = [
                'status'   => 401,
                'error'    => true,
                'messages' => [
                    'success' => 'Todo Insert failed'
                ]
            ];
        }
        return $this->respondCreated($response);
    }

    // update
    public function update($id = null){
        // $this->response->setHeader('Content-Type', 'application/json');
        $model = new TodoModel();
        // $id = $this->request->getPost('id');
        $data = [
            'title' => $this->request->getVar('title'),
            'completed' => $this->request->getVar('completed'),
        ];
        // echo $id."<br>";
        // // echo $data['title']."<br>";
        // // echo $data['completed'];
        
        // echo "<pre>";
        // print_r ($data);
        // echo "</pre>";
        
        $op = $model->update($id, $data);
        if($op){
            $response = [
                'status'   => 201,
                'error'    => null,
                'messages' => [
                    'success' => 'Todo updated successfully'
                ]
            ];
        }
        else{
            $response = [
                'status'   => 400,
                'error'    => true,
                'messages' => [
                    'success' => 'Todo updated failed'
                ]
            ];
        }
        // echo $this->respond($response);
        
        return $this->respond($response);
    }

    // delete
    public function delete($id = null){
        $model = new TodoModel();
        $data = $model->where('id', $id)->delete($id);
        if($data){
            $model->delete($id);
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => [
                    'success' => 'Employee successfully deleted'
                ]
            ];
            return $this->respondDeleted($response);
        }else{
            return $this->failNotFound('No employee found');
        }
    }


}