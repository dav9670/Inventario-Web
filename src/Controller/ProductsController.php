<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Products Controller
 *
 * @property \App\Model\Table\ProductsTable $Products
 *
 * @method \App\Model\Entity\Product[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ProductsController extends AppController
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
        $this->set('products', $this->Products->find('all')->order(['name' => 'asc']));
        $this->set('_serialize', ['products']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $product = $this->Products->newEntity();
        $success = false;
        if ($this->getRequest()->is('post')) {
            $product = $this->Products->patchEntity($product, $this->getRequest()->getData());
            if ($this->Products->save($product)) {
                $success = true;
                    
                $this->Flash->success(__('The product has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $success = false;
                $this->Flash->error(__('The product could not be saved. Please, try again.'));
            }
        }
            
        $this->set(compact('product', 'success'));
        $this->set('_serialize', ['success']);
    }

    /**
     * Consult method
     *
     * @param string|null $id Product id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function consult($id = null)
    {
        $product = $this->Products->get($id, [
            'contain' => ['Licences']
        ]);
        $success = false;
        if ($this->getRequest()->is(['patch', 'post', 'put'])) {

            $product = $this->Products->patchEntity($product, $this->getRequest()->getData());
            if ($this->Products->save($product)) {
                $success = true;

                $this->Flash->success(__('The product has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $success = false;

                $this->Flash->error(__('The product could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('product', 'success'));
    }


    /**
     * Delete method
     *
     * @param string|null $id Product id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->getRequest()->allowMethod(['get', 'post', 'delete']);
        $product = $this->Products->get($id);
        $success = false;
        if ($this->Products->delete($product)) {
            $success = true;
            $this->Flash->success(__('The product has been deleted.'));
        } else {
            $success = false;
            $this->Flash->error(__('The product could not be deleted. Please, try again.'));
        }

        $this->set(compact('success'));
        $this->set('_serialize', ['success']);
        return $this->redirect(['action' => 'index']);
    }

    public function isTaken()
    {
        if ($this->isApi()){
            $jsonData = $this->getRequest()->input('json_decode', true);
            $product = $this->Products->find('all')
                ->where(["lower(name) = :search"])
                ->bind(":search", strtolower($jsonData['name']), 'string')->first();
            
                $this->set(compact('product'));
            $this->set('_serialize', ['product']);  
        } else {
            return $this->redirect(['action' => 'index']);
        }
    }

    public function modifyLink($func)
    {
        $this->getRequest()->allowMethod(['post', 'delete']);

        $product = "";
        $licence = "";
        $success = false;
        if ($this->isApi()){
            $jsonData = $this->getRequest()->input('json_decode', true);
            $product = $this->Products->get($jsonData['product']);
            $licence = $this->Products->Licences->get($jsonData['licence']);
        } else {
            $product = $this->Products->get($this->getRequest()->getQuery('product'));
            $licence = $this->Products->Licences->get($this->getRequest()->getQuery('licence'));
        }

        $state = $func == 'link' ? 'created' : 'deleted';

        if($func == 'link' && $this->Products->Licences->link($product, [$licence]) || $func == 'unlink' && $this->Products->Licences->unlink($product, [$licence])){
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
            return /*$this->redirect(['action' => 'consult', $service->id])*/;
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
        $licence_id = "";

        if ($this->isApi()){
            $jsonData = $this->getRequest()->input('json_decode', true);
            $keyword = $jsonData['keyword'] != null ? $jsonData['keyword'] : '';
            $sort_field = $jsonData['sort_field'] != null ? $jsonData['sort_field'] : 'name';
            $sort_dir = $jsonData['sort_dir'] != null ? $jsonData['sort_dir'] : 'asc';
            $licence_id = $jsonData['licence_id'] != null ? $jsonData['licence_id'] : '';
        } else {
            $keyword = $this->getRequest()->getQuery('keyword') != null ? $this->getRequest()->getQuery('keyword') : '';
            $sort_field = $this->getRequest()->getQuery('sort_field') != null ? $this->getRequest()->getQuery('sort_field') : 'name';
            $sort_dir = $this->getRequest()->getQuery('sort_dir') != null ? $this->getRequest()->getQuery('sort_dir') : 'asc';
            $licence_id = $this->getRequest()->getQuery('licence_id') != null ? $this->getRequest()->getQuery('licence_id') : '';
        }
        
        if($keyword == '')
        {
            $query = $this->Products->find('all');
        }
        else
        {
            $query = $this->Products->find('all')
                ->where(["match (name, platform, description) against(:search in boolean mode)"])
                ->bind(":search", $keyword . '*', 'string');
        }

        if($licence_id != '')
        {
            $licenceProducts = $this->Products->Licences->find('all')
                ->select('Products.id')
                ->where('Licences.id = :id')
                ->bind(':id', $licence_id)
                ->innerJoinWith('Products');
            $query->where(["Products.id not in" => $licenceProducts]);
        }

        $query->order([$sort_field => $sort_dir]);
        
        $this->set('products', $query);
        $this->set('_serialize', ['products']);
    }

    public function isAuthorized($user)
    {
        return $this->Auth->user('admin_status') == 'admin';
    }
}
