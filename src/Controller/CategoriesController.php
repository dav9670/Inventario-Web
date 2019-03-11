<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\I18n\Number;

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
                $success = true;
                $this->Flash->success(__('The category has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $success = false;
                $this->Flash->error(__('The category could not be saved. Please, try again.'));
            }
        }

        $this->set(compact('category', 'success'));
        $this->set('_serialize', ['success']);
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
        $data ='';
        $category = $this->Categories->get($id, [
            'contain' => ['Equipments']
        ]);
        $success = false;
        if ($this->getRequest()->is(['patch', 'post', 'put'])) {

            $category = $this->Categories->patchEntity($category, $this->getRequest()->getData());
            if ($this->Categories->save($category)) {
                $success = true;
                $data = 'view';
                
                $this->Flash->success(__('The category has been saved.'));
                return $this->redirect(['action' => 'consult', $category->id]);
            } else {
                $success = false;
                $data = 'edit';

                $this->Flash->error(__('The category could not be saved. Please, try again.'));
            }
        }
        
        $this->set(compact('category','data', 'success'));
        $this->set('_serialize', ['success']);
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
        $this->getRequest()->allowMethod(['get', 'post', 'delete']);
        $category = $this->Categories->get($id);
        $success = false;
        if ($this->Categories->delete($category)) {
            $success = true;
            $this->Flash->success(__('The category has been deleted.'));
        } else {
            $success = false;
            $this->Flash->error(__('The category could not be deleted. Please, try again.'));
        }

        $this->set(compact('success'));
        $this->set('_serialize', ['success']);
        return $this->redirect(['action' => 'index']);
    }

    private function modifyLink($func)
    {
        $this->getRequest()->allowMethod(['post', 'delete']);

        $category = "";
        $equipment = "";
        $success = false;
        if($this->isApi()){
            $jsonData = $this->getRequest()->input('json_decode', true);
            $category = $this->Categories->get($jsonData['category']);
            $equipment = $this->Categories->Equipments->get($jsonData['equipment']);
        } else {
            $category = $this->Categories->get($this->getRequest()->getQuery('category'));
            $equipment = $this->Categories->Equipments->get($this->getRequest()->getQuery('equipment'));
        }

        $state = $func == 'link' ? 'created' : 'deleted';

        if($func == 'link' && $this->Categories->Equipments->link($category, [$equipment]) || $func == 'unlink' && $this->Categories->Equipments->unlink($category, [$equipment])){
            $success = true;
            //$this->Flash->success(__('The association has been ' . $state . '.'));
        } else {
            $success = false;
            //$this->Flash->error(__('The association could not be ' . $state . '. Please, try again.'));
        }

        if($this->isApi()){
            $this->set(compact('success'));
            $this->set('_serialize', ['success']);
        } else {
            $this->autoRender = false;
            return /*$this->redirect(['action' => 'consult', $category->id])*/;
        }
    }

    public function link()
    {
        $this->modifyLink('link');    
    }

    public function unlink()
    {
        $this->modifyLink('unlink');
    }

    public function search()
    {   
        $this->getRequest()->allowMethod(['post', 'ajax', 'get']);
   
        $keyword = "";
        $sort_field = "";
        $sort_dir = "";

        if ($this->isApi()){
            $jsonData = $this->getRequest()->input('json_decode', true);
            $keyword = $jsonData['keyword'] != null ? $jsonData['keyword'] : '';
            $sort_field = $jsonData['sort_field'] != null ? $jsonData['sort_field'] : 'name';
            $sort_dir = $jsonData['sort_dir'] != null ? $jsonData['sort_dir'] : 'asc';
        } else {
            $keyword = $this->getRequest()->getQuery('keyword') != null ? $this->getRequest()->getQuery('keyword') : '';
            $sort_field = $this->getRequest()->getQuery('sort_field') != null ? $this->getRequest()->getQuery('sort_field') : 'name';
            $sort_dir = $this->getRequest()->getQuery('sort_dir') != null ? $this->getRequest()->getQuery('sort_dir') : 'asc';
        }
        
        if($keyword == '')
        {
            $query = $this->Categories->find('all');
        }
        else
        {
            $query = $this->Categories->find('all')
            ->where(["match (name, description) against(:search in boolean mode)
                            or name like :like_search or description like :like_search"])
                            ->bind(":search", $keyword, 'string')
                            ->bind(":like_search", '%' . $keyword . '%', 'string');
                     
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
