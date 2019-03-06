<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;
use Cake\I18n\Time;
use Cake\Auth\DefaultPasswordHasher;
/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['logout', 'add']);

    }

    public function login()
    {
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) 
            {
                $this->Auth->setUser($user);
                
                if($this->isApi())
                {
                    $this->set('user', $user);
                    $this->set('_serialize', 'user');
                    return;
                }
                else
                {
                    if($this->Auth->redirectUrl() == "/")
                    {
                        return $this->redirect(['action' => 'profile']);
                    }
                    else
                    {
                        return $this->redirect($this->Auth->redirectUrl());
                    }
                }
            }
            else
            {
                $this->Flash->error(__('Your email and/or password is incorrect.'));
            }
        }
    }

    public function logout()
    {
        $this->Flash->success(__('You are now logged out.'));
        return $this->redirect($this->Auth->logout());
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $users = $this->paginate($this->Users);

        $this->set(compact('users'));
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => ['Loans']
        ]);

        $this->set('user', $user);
    }

    public function profile()
    {
        $user = $this->Users->get($this->Auth->user('id'), [
            'contain' => ['Loans']
        ]);

        $this->set('user', $user);
        $this->set('_serialize', ['user']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEntity();
        $success = false;
        if ($this->request->is('post')) {
            $data = $this->request->getData();

            if(!$this->isApi()){
                $image = $data['image'];
                if($image['tmp_name'] != '') {
                    $imageData  = file_get_contents($image['tmp_name']);
                    $b64   = base64_encode($imageData);
                    $data['image'] = $b64;
                }

                if($data['admin_status'] == 0){
                    $data['admin_status'] = 'user';
                }else{
                    $data['admin_status'] = 'admin';
                }
            }

            
            
            $user = $this->Users->patchEntity($user, $data);
            if ($this->Users->save($user))
            {
                if($this->isApi()){
                    $success = true;
                }
                else
                {
                    $this->Flash->success(__('The user has been saved.'));
                    return $this->redirect(['action' => 'index']);
                }
            }
            else if($this->isApi())
            {
                $success = false;
            }
            else
            {
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
            }
        }

        if($this->isApi()){
            $this->set(compact('success'));
            $this->set('_serialize', ['success']);
        } else {
            $this->set(compact('user'));
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
    }

       /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->getRequest()->allowMethod(['get', 'post', 'delete']);
        $user = $this->Users->get($id);
        $success = false;
        if ($this->Users->delete($user)) {
            if($this->isApi()){
                $success = true;
            } else {
                $this->Flash->success(__('The user has been deleted.'));
            }
        } else {
            if($this->isApi()){
                $success = false;
            } else {
                $this->Flash->error(__('The user could not be deleted. Please, try again.'));
            }
        }

        if($this->isApi()){
            $this->set(compact('success'));
            $this->set('_serialize', ['success']);
        } else {
            return $this->redirect(['action' => 'index']);
        }
    }

    /**
     * function deactivate
     * désactive un equipement, set sa date de delete à now
     */
    public function deactivate($id = null){
        $this->setDeleted($id, Time::now());
    }

    /**
     * function reactivate
     * reactive un equipement, set sa valeur deleted à null.
     */
    public function reactivate($id = null){
        $this->setDeleted($id, null);
    }

    private function setDeleted($id, $deleted){
        $this->getRequest()->allowMethod(['get', 'post']);
        $user = $this->Users->get($id);
        $user->deleted = $deleted;
        $success = false;

        $state = $deleted == null ? 'reactivated' : 'deactivated';

        if ($this->Users->save($user)) {
            if($this->isApi()){
                $success = true;
            } else {
                $this->Flash->success(__('The user has been ' . $state .'.'));
            }
        } else {
            if($this->isApi()){
                $success = false;
            } else {
                $this->Flash->error(__('The user could not be ' . $state . '. Please, try again.'));
            }
        }

        if($this->isApi()){
            $this->set(compact('success'));
            $this->set('_serialize', ['success']);
        } else {
            return $this->redirect($this->referer());
        }
    }
    
    public function isTaken()
    {
        if ($this->isApi()){
            $jsonData = $this->getRequest()->input('json_decode', true);
            $user = $this->Users->find('all')
                ->where(["lower(email) = :search"])
                ->bind(":search", strtolower($jsonData['email']), 'string')->first();
            
                $this->set(compact('user'));
            $this->set('_serialize', ['user']);  
        } else {
            return $this->redirect(['action' => 'index']);
        }
    }

        /**
     * consult method
     */

    public function consult($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => ['Loans']
        ]);
        if($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $image = $data['image'];
            if($image['tmp_name'] != '') {
                $imageData  = file_get_contents($image['tmp_name']);
                $b64   = base64_encode($imageData);
                $data['image'] = $b64;
            } else {
                $data['image'] = $user->image;
            }

            if($data['admin_status'] == 0){
                $data['admin_status'] = 'user';
            }else{
                $data['admin_status'] = 'admin';
            }
            $user = $this->Users->patchEntity($user, $data);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $loans = $this->Users->Loans->find('list', ['limit' => 200]);
        $this->set(compact('user', 'loans'));
    }

    /**
     * isAuthorized function
     */

    public function isAuthorized($user)
    {
        return $this->Auth->user('admin_status') == 'admin';
    }

    /**
     * fonction search qui est appelée par la ajax request de la page users/index
     * en fonction des requetes ajax retourne différentes liste de users
     */

    public function search()
    {
        ini_set('memory_limit', '-1');

        if($this->isApi()){
            $this->getRequest()->allowMethod('post');
        }else {
            $this->getRequest()->allowMethod('get', 'ajax');
        }
   
        $keyword = "";
        $sort_field = "email";
        $sort_dir = "asc";
        

        if ($this->isApi()){
            $jsonData = $this->getRequest()->input('json_decode', true);
            $keyword = $jsonData['keyword'];
            //$sort_field = $jsonData['sort_field'];
            //$sort_dir = $jsonData['sort_dir'];
        } else {
            $keyword = $this->getRequest()->getQuery('keyword');
            $sort_field = $this->getRequest()->getQuery('sort_field');
            $sort_dir = $this->getRequest()->getQuery('sort_dir');
            
            
        }

        $query = null;
        
        if($keyword == '')
        {
            $query = $this->Users->find('all');
        }
        else
        {
            $query = $this->Users->find('all')
                ->where(["match (Users.email) against(:search in boolean mode)
                    or Users.email like :like_search"])
                ->bind(":search", $keyword, 'string')
                ->bind(":like_search", '%' . $keyword . '%', 'string');
        }

        if ($query != null)
        {
            $query->order(["Users.".$sort_field => $sort_dir]);
        }
        
        $users = [];
        $archivedUsers = [];
        $allUsers = $this->paginate($query);
        foreach ($allUsers as $user){
            array_push($users, $user);
        }
        $this->set(compact('users', 'archivedUsers'));
        $this->set('_serialize', ['users', 'archivedUsers']);
        
    }

    public function verify(){
        $identify = false;

        if ($this->request->is('post')) {
            $user = $this->Auth->identify();

            if ($user) 
            {
                $identify = true;
                //$this->add();
            }
            else
            {
                $identify = false;
                $this->Flash->error(__('Your password is incorrect.'));
            }

        }

        $this->set(compact('identify'));
        $this->set('_serialize', 'identify');
    }
}
