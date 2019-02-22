<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Categories Controller
 *
 * @property \App\Model\Table\CategoriesTable $Categories
 *
 * @method \App\Model\Entity\Category[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CategoriesController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Paginator');
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->set('categories', $this->Categories->find('all')->order(['name' => 'asc']));
        $this->set('_serialize', ['categories']);
    }

    /**
     * View method
     *
     * @param string|null $id Category id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $category = $this->Categories->get($id, [
            'contain' => ['Equipments']
        ]);

        $this->set(compact('category'));
        $this->set('_serialize', ['category']);
    }
    

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $category = $this->Categories->newEntity();
        $success = false;
        if ($this->getRequest()->is('post')) {
            $category = $this->Categories->patchEntity($category, $this->getRequest()->getData());
            if ($this->Categories->save($category)) {
                if($this->isApi()){
                    $success = true;
                } else {
                    $this->Flash->success(__('The category has been saved.'));

                    return $this->redirect(['action' => 'index']);
                }
            } else if($this->isApi()){
                $success = false;
            } else {
                $this->Flash->error(__('The category could not be saved. Please, try again.'));
            }
        }
        if($this->isApi()){
            $this->set(compact('success'));
            $this->set('_serialize', ['success']);
        } else {
            $equipments = $this->Categories->Equipments->find('list', ['limit' => 200]);
            $this->set(compact('category', 'equipments'));
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Category id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $category = $this->Categories->get($id, [
            'contain' => ['Equipments']
        ]);
        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
            $category = $this->Categories->patchEntity($category, $this->getRequest()->getData());
            if ($this->Categories->save($category)) {
                if($this->isApi()){
                    $success = true;
                } else {
                    $this->Flash->success(__('The category has been saved.'));

                    return $this->redirect(['action' => 'index']);
                }
            } else if($this->isApi()){
                $success = false;
            } else {
                $this->Flash->error(__('The category could not be saved. Please, try again.'));
            }
        }
        if($this->isApi()){
            $this->set(compact('success'));
            $this->set('_serialize', ['success']);
        } else {
            $equipments = $this->Categories->Equipments->find('list', ['limit' => 200]);
            $this->set(compact('category', 'equipments'));
        }
    }

    /**
     * Consult method
     *
     * @param string|null $id Category id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function consult($id = null)
    {
        $category = $this->Categories->get($id, [
            'contain' => ['Equipments']
        ]);
        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
            $category = $this->Categories->patchEntity($category, $this->getRequest()->getData());
            if ($this->Categories->save($category)) {
                $this->Flash->success(__('The category has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The category could not be saved. Please, try again.'));
        }
        $equipments = $this->Categories->Equipments->find('list', ['limit' => 200]);
        $this->set(compact('category', 'equipments'));
    }
    

    /**
     * Delete method
     *
     * @param string|null $id Category id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->getRequest()->allowMethod(['post', 'delete']);
        $category = $this->Categories->get($id);
        $success = false;
        if ($this->Categories->delete($category)) {
            if($this->isApi()){
                $success = true;
            } else {
                $this->Flash->success(__('The category has been deleted.'));
            }
        } else {
            if($this->isApi()){
                $success = false;
            } else {
                $this->Flash->error(__('The category could not be deleted. Please, try again.'));
            }
        }

        if($this->isApi()){
            $this->set(compact('success'));
            $this->set('_serialize', ['success']);
        } else {
            return $this->redirect(['action' => 'index']);
        }
    }

    public function unlink()
    {
        $this->getRequest()->allowMethod(['post', 'delete']);

        $category = "";
        $equipment = "";
        if($this->isApi()){
            $jsonData = $this->getRequest()->input('json_decode', true);
            $category = $this->Categories->get($jsonData['category']);
            $equipment = $this->Categories->Equipments->get($jsonData['equipment']);
        } else {
            $category = $this->Categories->get($this->getRequest()->getQuery('category'));
            $equipment = $this->Categories->Equipments->get($this->getRequest()->getQuery('equipment'));
        }
        

        if ($this->Categories->Equipments->unlink($category, [$equipment])) {
            if($this->isApi()){
                $success = true;
            } else {
                $this->Flash->success(__('The association has been deleted.'));
            }
        } else {
            if($this->isApi()){
                $success = false;
            } else {
                $this->Flash->error(__('The association could not be deleted. Please, try again.'));
            }
        }

        if($this->isApi()){
            $this->set(compact('success'));
            $this->set('_serialize', ['success']);
        } else {
            return $this->redirect(['action' => 'consult', $category->id]);
        }
    }

    public function search()
    {   
        if($this->isApi()){
            $this->getRequest()->allowMethod('post');
        }else {
            $this->getRequest()->allowMethod('ajax');
        }
   
        $keyword = "";
        $sort_field = "";
        $sort_dir = "";
        
        if ($this->getRequest()->is('ajax')){
            $keyword = $this->getRequest()->getQuery('keyword');
            $sort_field = $this->getRequest()->getQuery('sort_field');
            $sort_dir = $this->getRequest()->getQuery('sort_dir');
        } else if ($this->getRequest()->is('post')){
            $jsonData = $this->getRequest()->input('json_decode', true);
            $keyword = $jsonData['keyword'];
            $sort_field = $jsonData['sort_field'];
            $sort_dir = $jsonData['sort_dir'];
        }
        
        if($keyword == '')
        {
            $query = $this->Categories->find('all');
        }
        else
        {
            $query = $this->Categories->find('all')
                ->where(["match (name, description) against(:search in boolean mode)"])
                ->bind(":search", $keyword . '*', 'string');
        }

        $query->order([$sort_field => $sort_dir]);
        
        $this->set('categories', $this->paginate($query));
        $this->set('_serialize', ['categories']);
    }

    public function isAuthorized($user)
    {
        return $this->Auth->user('admin_status') == 'admin';
    }
}
