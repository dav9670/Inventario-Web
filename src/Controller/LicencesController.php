<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Licences Controller
 *
 * @property \App\Model\Table\LicencesTable $Licences
 *
 * @method \App\Model\Entity\Licence[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class LicencesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        ini_set('memory_limit', '-1');
        $licences = $this->Licences->find('all', [
            'contain' => ['Products']
        ])->where('deleted is null')->order(['name' => 'asc']);
        $archivedLicences = $this->Licences->find('all', [
            'contain' => ['Products']
        ])->where('deleted is not null')->order(['name' => 'asc']);
        $this->set(compact('licences', 'archivedLicences'));
        $this->set('_serialize', ['licences', 'archivedLicences']);
    }

    /**
     * View method
     *
     * @param string|null $id Licence id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $licence = $this->Licences->get($id, [
            'contain' => ['Products']
        ]);

        $this->set('licence', $licence);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $licence = $this->Licences->newEntity();
        if ($this->request->is('post')) {
            $licence = $this->Licences->patchEntity($licence, $this->request->getData());
            if ($this->Licences->save($licence)) {
                $this->Flash->success(__('The licence has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The licence could not be saved. Please, try again.'));
        }
        $products = $this->Licences->Products->find('list', ['limit' => 200]);
        $this->set(compact('licence', 'products'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Licence id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $licence = $this->Licences->get($id, [
            'contain' => ['Products']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $licence = $this->Licences->patchEntity($licence, $this->request->getData());
            if ($this->Licences->save($licence)) {
                $this->Flash->success(__('The licence has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The licence could not be saved. Please, try again.'));
        }
        $products = $this->Licences->Products->find('list', ['limit' => 200]);
        $this->set(compact('licence', 'products'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Licence id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $licence = $this->Licences->get($id);
        if ($this->Licences->delete($licence)) {
            $this->Flash->success(__('The licence has been deleted.'));
        } else {
            $this->Flash->error(__('The licence could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function isAuthorized($user)
    {
        return $this->Auth->user('admin_status') == 'admin';
    }

    /**
     * fonction search qui est appelée par la ajax request de la page licences/index
     * en fonction des requetes ajax retourne différentes liste de licences
     */

    public function search()
    {
        ini_set('memory_limit', '-1');

        if($this->isApi()){
            $this->getRequest()->allowMethod('post');
        }else {
            $this->getRequest()->allowMethod('ajax');
        }
   
        $keyword = "";
        $sort_field = "";
        $sort_dir = "";

        $search_available = true;
        $search_unavailable = true;
        $search_licences = true;
        $search_products = true;

        if ($this->getRequest()->is('ajax')){
            $keyword = $this->getRequest()->getQuery('keyword');
            $sort_field = $this->getRequest()->getQuery('sort_field');
            $sort_dir = $this->getRequest()->getQuery('sort_dir');
            
            $filters = $this->getRequest()->getQuery('filters');
            $search_available = $filters['search_available'] == 'true';
            $search_unavailable = $filters['search_unavailable'] == 'true';
            $search_licences = $filters['search_licences'] == 'true';
            $search_products = $filters['search_products'] == 'true';
        
        } else if ($this->getRequest()->is('post')){
            $jsonData = $this->getRequest()->input('json_decode', true);
            $keyword = $jsonData['keyword'];
            $sort_field = $jsonData['sort_field'];
            $sort_dir = $jsonData['sort_dir'];
        }
        
        if($keyword == '')
        {
            $query = $this->Licences->find('all');
        }
        else
        {
            if ($this->isApi()){
                $queryLicences = $this->Licences->find('all', [
                    'contain' => ['Products']
                ])
                    ->where(["match (Licences.nam, Licences.description) against(:search in boolean mode)"])
                    ->bind(":search", $keyword . '*', 'string');
                $queryProducts = $this->Licencers->find('all', [
                    'contain' => ['Products']
                ])
                    ->innerJoinWith('Products')
                    ->where(["match (Products.name, Products.description) against(:search in boolean mode)"])
                    ->bind(":search", $keyword . '*', 'string');

                $queryLicences->union($queryProducts);
                $query = $queryLicences;
            }
            else if($search_licences && $search_products)
            {
                $queryLicences = $this->Licences->find('all')
                    ->where(["match (Licences.name, Licences.description) against(:search in boolean mode)"])
                    ->bind(":search", $keyword . '*', 'string');
                $queryProducts = $this->Licences->find('all')
                    ->innerJoinWith('Products')
                    ->where(["match (Products.name, Products.description) against(:search in boolean mode)"])
                    ->bind(":search", $keyword . '*', 'string');

                $queryLicences->union($queryProducts);
                $query = $queryLicences;
            }
            else if($search_licences)
            {
                $query = $this->Licences->find('all')
                    ->where(["match (Licences.name, Licences.description) against(:search in boolean mode)"])
                    ->bind(":search", $keyword . '*', 'string');
            }
            else if($search_products)
            {
                $query = $this->Licences->find('all')
                    ->innerJoinWith('Products')
                    ->where(["match (Products.name, Products.description) against(:search in boolean mode)"])
                    ->bind(":search", $keyword . '*', 'string');
            }

            
        }

        $query->order([$sort_field => $sort_dir]);
        
        $licences = [];
        $archivedLicences = [];
        $allLicences = $this->paginate($query);
        foreach ($allLicences as $licence){
            if($search_available && $licence->available || $search_unavailable && !$licence->available){
                if ($licence->deleted != null && $licence->deleted != "") {
                
                    array_push($archivedLicences, $licence);
                } else {
                    array_push($licences, $licence);
                }
            }
        }
        
        $this->set(compact('licences', 'archivedLicences'));
        $this->set('_serialize', ['licences', 'archivedLicences']);
    }
}
