<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Equipments Controller
 *
 * @property \App\Model\Table\EquipmentsTable $Equipments
 *
 * @method \App\Model\Entity\Equipment[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class EquipmentsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        ini_set('memory_limit', '-1');
        $equipments = $this->Equipments->find('all', [
            'contain' => ['Categories']
        ])->where(['deleted IS' => null])->orWhere(['deleted IS' => ""])->order(['name' => 'asc']);
        $archivedEquipments = $this->Equipments->find('all', [
            'contain' => ['Categories']
        ])->where(['deleted IS NOT' => ""])->order(['name' => 'asc']);
        $this->set(compact('equipments', 'archivedEquipments'));
        $this->set('_serialize', ['equipments', 'archivedEquipments']);
    }

    /**
     * View method
     *
     * @param string|null $id Equipment id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $equipment = $this->Equipments->get($id, [
            'contain' => ['Categories']
        ]);

        $this->set('equipment', $equipment);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $equipment = $this->Equipments->newEntity();
        if ($this->request->is('post')) {
            $equipment = $this->Equipments->patchEntity($equipment, $this->request->getData());
            if ($this->Equipments->save($equipment)) {
                $this->Flash->success(__('The equipment has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The equipment could not be saved. Please, try again.'));
        }
        $categories = $this->Equipments->Categories->find('list', ['limit' => 200]);
        $this->set(compact('equipment', 'categories'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Equipment id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $equipment = $this->Equipments->get($id, [
            'contain' => ['Categories']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $equipment = $this->Equipments->patchEntity($equipment, $this->request->getData());
            if ($this->Equipments->save($equipment)) {
                $this->Flash->success(__('The equipment has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The equipment could not be saved. Please, try again.'));
        }
        $categories = $this->Equipments->Categories->find('list', ['limit' => 200]);
        $this->set(compact('equipment', 'categories'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Equipment id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $equipment = $this->Equipments->get($id);
        if ($this->Equipments->delete($equipment)) {
            $this->Flash->success(__('The equipment has been deleted.'));
        } else {
            $this->Flash->error(__('The equipment could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function isAuthorized($user)
    {
        return $this->Auth->user('admin_status') == 'admin';
    }

    /**
     * fonction search qui est appelée par la ajax request de la page equipments/index
     * en fonction des requetes ajax retourne différentes liste de equipments
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

        $search_available = false;
        $search_unavailable = false;
        $search_equipments = false;
        $search_categories = false;

        if ($this->getRequest()->is('ajax')){
            $keyword = $this->getRequest()->getQuery('keyword');
            $sort_field = $this->getRequest()->getQuery('sort_field');
            $sort_dir = $this->getRequest()->getQuery('sort_dir');
            
            $filters = $this->getRequest()->getQuery('filters');
            $search_available = $filters['search_available'] == 'true';
            $search_unavailable = $filters['search_unavailable'] == 'true';
            $search_equipments = $filters['search_equipments'] == 'true';
            $search_categories = $filters['search_categories'] == 'true';
        
        } else if ($this->getRequest()->is('post')){
            $jsonData = $this->getRequest()->input('json_decode', true);
            $keyword = $jsonData['keyword'];
            $sort_field = $jsonData['sort_field'];
            $sort_dir = $jsonData['sort_dir'];
        }
        
        if($keyword == '')
        {
            $query = $this->Equipments->find('all');
        }
        else
        {
            if ($this->isApi()){
                $queryEquipments = $this->Equipments->find('all', [
                    'contain' => ['Categories']
                ])
                    ->where(["match (Equipments.name, Equipments.description) against(:search in boolean mode)"])
                    ->bind(":search", $keyword . '*', 'string');
                $queryCategories = $this->Equipments->find('all', [
                    'contain' => ['Categories']
                ])
                    ->innerJoinWith('Categories')
                    ->where(["match (Categories.name, Categories.description) against(:search in boolean mode)"])
                    ->bind(":search", $keyword . '*', 'string');

                $queryEquipments->union($queryCategories);
                $query = $queryEquipments;
            }
            else if($search_equipments && $search_categories)
            {
                $queryEquipments = $this->Equipments->find('all')
                    ->where(["match (Equipments.name, Equipments.description) against(:search in boolean mode)"])
                    ->bind(":search", $keyword . '*', 'string');
                $queryCategories = $this->Equipments->find('all')
                    ->innerJoinWith('Categories')
                    ->where(["match (Categories.name, Categories.description) against(:search in boolean mode)"])
                    ->bind(":search", $keyword . '*', 'string');

                $queryEquipments->union($queryCategories);
                $query = $queryEquipments;
            }
            else if($search_equipments)
            {
                $query = $this->Equipments->find('all')
                    ->where(["match (Equipments.name, Equipments.description) against(:search in boolean mode)"])
                    ->bind(":search", $keyword . '*', 'string');
            }
            else if($search_categories)
            {
                $query = $this->Equipments->find('all')
                    ->innerJoinWith('Categories')
                    ->where(["match (Categories.name, Categories.description) against(:search in boolean mode)"])
                    ->bind(":search", $keyword . '*', 'string');
            }

            
        }

        $query->order([$sort_field => $sort_dir]);
        
        $equipments = [];
        $archivedEquipments = [];
        $allEquipments = $this->paginate($query);
        foreach ($allEquipments as $equipment){
            if ($equipment->deleted != null && $equipment->deleted != "") {
                array_push($archivedEquipments, $equipment);
            } else {
                array_push($equipments, $equipment);
            }
        }
        
        $this->set(compact('equipments', 'archivedEquipments'));
        $this->set('_serialize', ['equipments', 'archivedEquipments']);
    }
}
