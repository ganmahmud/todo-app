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
            return $this->respondCreated($response);
        }
        else{
            return $this->failNotFound('Todo insert failed');
        }
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
            return $this->respond($response);
        }
        else{
            return $this->failNotFound('Todo update failed');
        }
       
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
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => [
                    'success' => 'Todo successfully deleted'
                ]
            ];
            return $this->respondDeleted($response);
        }else{
            return $this->failNotFound('No todo found');
        }
    }

    /**
    * updates todo items in batch 
    * @param number $id
    * @return json
    */
    public function bulk_update(){
        // using updateBatch

        $model = new TodoModel();
        // $data = [
        //     'title' => $this->request->getVar('title'),
        //     'completed' => $this->request->getVar('completed'),
        // ];
        $data = json_decode(json_encode($this->request->getVar('todos')),true);
        // $data = json_decode($todoStr,true);
        
        $op = $model->updateBatch($data, 'id');
        if($op){
            $response = [
                'status'   => 201,
                'error'    => null,
                'messages' => [
                    'success' => 'Todo updated successfully'
                ]
            ];
            return $this->respond($response);
        }
        else{
            return $this->failNotFound('No todo found');
        }
       
    }

    /**
    * @return json
    */
    public function clear_completed(){

        $model = new TodoModel();
        $data = $model->where('completed', 1)->delete();
        
        if($data){
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => [
                    'success' => 'Completed cleared'
                ]
            ];
            return $this->respondDeleted($response);
        }else{
            return $this->failNotFound('Clearing failed');
        }
    }

}