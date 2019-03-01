<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\I18n\Time;

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
        ])->where('deleted is null')->order(['name' => 'asc']);
        $archivedEquipments = $this->Equipments->find('all', [
            'contain' => ['Categories']
        ])->where('deleted is not null')->order(['name' => 'asc']);
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
            'contain' => ['Categories' => [
                'sort' => ['Categories.name' => 'asc']
                ]
            ]
        ]);

        $this->set(compact('equipment'));
        $this->set('_serialize', ['equipment']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $equipment = $this->Equipments->newEntity();
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
            }
            $equipment = $this->Equipments->patchEntity($equipment, $data);
            if ($this->Equipments->save($equipment)) {
                if($this->isApi()){
                    $success = true;
                } else {
                    $this->Flash->success(__('The equipment has been saved.'));
                    return $this->redirect(['action' => 'index']);
                }
            } else if($this->isApi()){
                $success = false;
            } else {
                $this->Flash->error(__('The equipment could not be saved. Please, try again.'));
            }
        }

        if($this->isApi()){
            $this->set(compact('success'));
            $this->set('_serialize', ['success']);
        } else {
            $this->set(compact('equipment'));
        }
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
        $success = false;

        if ($this->request->is(['patch', 'post', 'put'])) {

            $data = $this->request->getData();
            if(!$this->isApi()){
                $image = $data['image'];
                if($image['tmp_name'] != '') {
                    $imageData  = file_get_contents($image['tmp_name']);
                    $b64   = base64_encode($imageData);
                    $data['image'] = $b64;
                }
            }
            $equipment = $this->Equipments->patchEntity($equipment, $data);
            if ($this->Equipments->save($equipment)) {
                if($this->isApi()){
                    $success = true;
                } else {
                    $this->Flash->success(__('The equipment has been saved.'));
                    return $this->redirect(['action' => 'index']);
                }
            } else if($this->isApi()){
                $success = false;
            } else {
                $this->Flash->error(__('The equipment could not be saved. Please, try again.'));
            }
        }

        if($this->isApi()){
            $this->set(compact('success'));
            $this->set('_serialize', ['success']);
        } else {
            $categories = $this->Equipments->Categories->find('list', ['limit' => 200]);
            $this->set(compact('equipment', 'categories'));
        }
    }


    /**
     * consult method
     */

    public function consult($id = null)
    {
        $equipment = $this->Equipments->get($id, [
            'contain' => ['Categories']
        ]);
        if($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $image = $data['image'];
            if($image['tmp_name'] != '') {
                $imageData  = file_get_contents($image['tmp_name']);
                $b64   = base64_encode($imageData);
                $data['image'] = $b64;
            } else {
                $data['image'] = $equipment->image;
            }

            $equipment = $this->Equipments->patchEntity($equipment, $data);
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
        $this->getRequest()->allowMethod(['get', 'post', 'delete']);
        $equipment = $this->Equipments->get($id);
        $success = false;
        if ($this->Equipments->delete($equipment)) {
            if($this->isApi()){
                $success = true;
            } else {
                $this->Flash->success(__('The equipment has been deleted.'));
            }
        } else {
            if($this->isApi()){
                $success = false;
            } else {
                $this->Flash->error(__('The equipment could not be deleted. Please, try again.'));
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
        $equipment = $this->Equipments->get($id);
        $equipment->deleted = $deleted;
        $success = false;

        $state = $deleted == null ? 'reactivated' : 'deactivated';

        if ($this->Equipments->save($equipment)) {
            if($this->isApi()){
                $success = true;
            } else {
                $this->Flash->success(__('The equipment has been ' . $state .'.'));
            }
        } else {
            if($this->isApi()){
                $success = false;
            } else {
                $this->Flash->error(__('The equipment could not be ' . $state . '. Please, try again.'));
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
            $equipment = $this->Equipments->find('all')
                ->where(["lower(name) = :search"])
                ->bind(":search", strtolower($jsonData['name']), 'string')->first();
            
                $this->set(compact('equipment'));
            $this->set('_serialize', ['equipment']);  
        } else {
            return $this->redirect(['action' => 'index']);
        }
    }


    /**
     * isAuthorized function
     */

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
            $this->getRequest()->allowMethod('get', 'ajax');
        }
   
        $keyword = "";
        $sort_field = "";
        $sort_dir = "";

        $search_available = true;
        $search_unavailable = true;
        $search_equipments = true;
        $search_categories = true;

        if ($this->isApi()){
            $jsonData = $this->getRequest()->input('json_decode', true);
            $keyword = $jsonData['keyword'];
            $sort_field = $jsonData['sort_field'];
            $sort_dir = $jsonData['sort_dir'];
        } else {
            $keyword = $this->getRequest()->getQuery('keyword');
            $sort_field = $this->getRequest()->getQuery('sort_field');
            $sort_dir = $this->getRequest()->getQuery('sort_dir');
            
            $filters = $this->getRequest()->getQuery('filters');
            $search_available = $filters['search_available'] == 'true';
            $search_unavailable = $filters['search_unavailable'] == 'true';
            $search_equipments = $filters['search_equipments'] == 'true';
            $search_categories = $filters['search_categories'] == 'true';
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

        if (!is_null($query))
        {
            $query->order(["Equipments.".$sort_field => $sort_dir]);
        }
        
        $equipments = [];
        $archivedEquipments = [];
        $allEquipments = $this->paginate($query);
        foreach ($allEquipments as $equipment){
            if($search_available && $equipment->available || $search_unavailable && !$equipment->available){
                if ($equipment->deleted != null && $equipment->deleted != "") {
                    
                    if (!in_array($equipment,$archivedEquipments))
                    {
                        array_push($archivedEquipments, $equipment);
                    }
                } else {
                    if (!in_array($equipment,$equipments))
                    {
                        array_push($equipments, $equipment);
                    }
                }
            }
        }
        $this->set(compact('equipments', 'archivedEquipments'));
        $this->set('_serialize', ['equipments', 'archivedEquipments']);
        
    }
}
