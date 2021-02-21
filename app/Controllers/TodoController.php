<?php
namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\TodoModel;


class TodoController extends BaseController{
    use ResponseTrait;
    /**
    * gets all todo items 
    * @return json
    */
    public function index(){    
       //get all todos
        $model = new TodoModel();
        $data= $model->findAll();
        return $this->respond($data);
    }

    /**
    * gets single todo item 
    * @param number $id
    * @return json
    */
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

    /**
    * creates todo items 
    * @return json
    */
    public function create()
    {
        $model = new TodoModel();
        $data = [
            'title' => $this->request->getVar('title'),
            // 'completed' => $this->request->getVar('completed')
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

    /**
    * updates single todo item 
    * @param number $id
    * @return json
    */
    public function update($id = null){
        
        $model = new TodoModel();
        $data = [
            'title' => $this->request->getVar('title'),
            'completed' => $this->request->getVar('completed'),
        ];
       
        
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
       
        
        return $this->respond($response);
    }

    /**
    * delete a todo item 
    * @param number $id
    * @return json
    */
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