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
        $this->loadComponent('Paginator');
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
     * View method
     *
     * @param string|null $id Product id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $product = $this->Products->get($id, [
            'contain' => ['Licences']
        ]);

        $this->set('product', $product);
        $this->set('_serialize', ['product']);
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
                if($this->isApi()){
                    $success = true;
                } else {
                    $this->Flash->success(__('The product has been saved.'));

                    return $this->redirect(['action' => 'index']);
                }
            } else if($this->isApi()){
                $success = false;
            } else {
                $this->Flash->error(__('The product could not be saved. Please, try again.'));
            }
        }
        if($this->isApi()){
            $this->set(compact('success'));
            $this->set('_serialize', ['success']);
        } else {
            $licences = $this->Products->Licences->find('list', ['limit' => 200]);
            $this->set(compact('product', 'licences'));
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Product id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $product = $this->Products->get($id, [
            'contain' => ['Licences']
        ]);
        $success = false;
        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
            $product = $this->Products->patchEntity($product, $this->getRequest()->getData());
            if ($this->Products->save($product)) {
                if($this->isApi()){
                    $success = true;
                } else {
                    $this->Flash->success(__('The product has been saved.'));

                    return $this->redirect(['action' => 'index']);
                }
            } else if($this->isApi()){
                $success = false;
            } else {
                $this->Flash->error(__('The product could not be saved. Please, try again.'));
            }
        }
        if($this->isApi()){
            $this->set(compact('success'));
            $this->set('_serialize', ['success']);
        } else {
            $licences = $this->Products->Licences->find('list', ['limit' => 200]);
            $this->set(compact('product', 'licences'));
        }
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
        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
            $product = $this->Products->patchEntity($product, $this->getRequest()->getData());
            if ($this->Products->save($product)) {
                $this->Flash->success(__('The product has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The product could not be saved. Please, try again.'));
        }
        $licences = $this->Products->Licences->find('list', ['limit' => 200]);
        $this->set(compact('product', 'licences'));
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
        $this->getRequest()->allowMethod(['post', 'delete']);
        $product = $this->Products->get($id);
        $success = false;
        if ($this->Products->delete($product)) {
            if($this->isApi()){
                $success = true;
            } else {
                $this->Flash->success(__('The product has been deleted.'));
            }
        } else {
            if($this->isApi()){
                $success = false;
            } else {
                $this->Flash->error(__('The product could not be deleted. Please, try again.'));
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

        $product = "";
        $licence = "";
        $success = false;
        if($this->isApi()){
            $jsonData = $this->getRequest()->input('json_decode', true);
            $product = $this->Products->get($jsonData['product']);
            $licence = $this->Products->Licences->get($jsonData['licence']);
        } else {
            $product = $this->Products->get($this->getRequest()->getQuery('product'));
            $licence = $this->Products->Licences->get($this->getRequest()->getQuery('licence'));
        }
        

        if ($this->Products->Licences->unlink($product, [$licence])) {
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
            return $this->redirect(['action' => 'consult', $product->id]);
        }
    }

    public function search()
    {   
        if($this->isApi()){
            $this->getRequest()->allowMethod('post');
        } else {
            $this->getRequest()->allowMethod(['ajax', 'get']);
        }
   
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
            $query = $this->Products->find('all');
        }
        else
        {
            $query = $this->Products->find('all')
                ->where(["match (name, platform, description) against(:search in boolean mode)"])
                ->bind(":search", $keyword . '*', 'string');
        }

        $query->order(['Licences.'.$sort_field => $sort_dir]);
        
        $this->set('products', $this->paginate($query));
        $this->set('_serialize', ['products']);
    }

    public function isAuthorized($user)
    {
        return $this->Auth->user('admin_status') == 'admin';
    }
}
