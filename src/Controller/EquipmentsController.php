<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\I18n\Time;
use Cake\Datasource\ConnectionManager;

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
                $success = true;

                $this->Flash->success(__('The equipment has been saved.'));
                if (!$this->isApi()) {
                    return $this->redirect(['action' => 'index']);
                }
            } else {
                $success = false;

                $this->Flash->error(__('The equipment could not be saved. Please, try again.'));
            }
        }

        $this->set(compact('equipment', 'success'));
        $this->set('_serialize', ['success']);
    }

    /**
     * consult method
     */

    public function consult($id = null)
    {
        $equipment = $this->Equipments->get($id, [
            'contain' => ['Categories']
        ]);
        $success = false;

        if($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            if(!$this->isApi()){
                $image = $data['image'];
                if($image['tmp_name'] != '') {
                    $imageData  = file_get_contents($image['tmp_name']);
                    $b64   = base64_encode($imageData);
                    $data['image'] = $b64;
                } else {
                    $data['image'] = $equipment->image;
                }
            }
            $equipment = $this->Equipments->patchEntity($equipment, $data);

            if ($this->Equipments->save($equipment)) {
                $success = true;

                $this->Flash->success(__('The equipment has been saved.'));
                if (!$this->isApi()) {
                    return $this->redirect(['action' => 'consult', $equipment->id]);
                }
            } else {
                $success = false;

                $this->Flash->error(__('The equipment could not be saved. Please, try again.'));
            }
            
        }

        $this->set(compact('equipment', 'success'));
        $this->set('_serialize', ['equipment', 'success']);
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
            $success = true;
            $this->Flash->success(__('The equipment has been deleted.'));
        } else {
            $success = false;
            $this->Flash->error(__('The equipment could not be deleted. Please, try again.'));
        }

        $this->set(compact('success'));
        $this->set('_serialize', ['success']);
        if (!$this->isApi()) {
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

        if ($this->Equipments->save($equipment)) {
            $success = true;
            if($deleted == null){
                $this->Flash->success(__('The equipment has been reactivated.'));
            } else {
                $this->Flash->success(__('The equipment has been deactivated.'));
            }
        } else {
            $success = false;
            if($deleted == null){
                $this->Flash->error(__('The equipment could not be reactivated. Please, try again.'));
            } else {
                $this->Flash->error(__('The equipment could not be deactivated. Please, try again.'));
            }   
        }

        $this->set(compact('success'));
        $this->set('_serialize', ['success']);
        if (!$this->isApi()) {
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

        $this->getRequest()->allowMethod(['post', 'ajax', 'get']);
   
        $keyword = "";
        $sort_field = "name";
        $sort_dir = "asc";

        $search_available = true;
        $search_unavailable = true;
        $search_equipments = true;
        $search_categories = true;

        $start_time_available = null;
        $end_time_available = null;

        if ($this->isApi()){
            $jsonData = $this->getRequest()->input('json_decode', true);
            $keyword = $jsonData['keyword'];
            $sort_field = $jsonData['sort_field'];
            $sort_dir = $jsonData['sort_dir'];
            $search_unavailable = isset($jsonData['search_unavailable']) ? $jsonData['search_unavailable'] == 'true' : true;
            $start_time_available = isset($jsonData['start_time_available']) ? $jsonData['start_time_available'] : null;
            $end_time_available = isset($jsonData['end_time_available']) ? $jsonData['end_time_available'] : null;
        } else {
            $keyword = $this->getRequest()->getQuery('keyword');
            $sort_field = $this->getRequest()->getQuery('sort_field');
            $sort_dir = $this->getRequest()->getQuery('sort_dir');
            
            $filters = $this->getRequest()->getQuery('filters');
            $search_available = isset($filters['search_available']) ? $filters['search_available'] == 'true' : true;
            $search_unavailable = isset($filters['search_unavailable']) ? $filters['search_unavailable'] == 'true' : true;
            $search_equipments = isset($filters['search_equipments']) ? $filters['search_equipments'] == 'true' : true;
            $search_categories = isset($filters['search_categories']) ?  $filters['search_categories'] == 'true' : true;
            $start_time_available = isset($filters['start_time_available']) ? $filters['start_time_available'] : null;
            $end_time_available = isset($filters['end_time_available']) ? $filters['end_time_available'] : null;
        }

        $query = null;

        $options = [];
        if($this->isApi())
        {
            $options = ['contain' => ['Categories']];
        }
        
        if($keyword == '')
        {
            $query = $this->Equipments->find('all', $options);
        }
        else
        {
            $union_query = null;

            if($search_equipments)
            {
                $query = $this->Equipments->find('all', $options)
                    ->where(["match (Equipments.name, Equipments.description) against(:search in boolean mode)
                        or Equipments.name like :like_search or Equipments.description like :like_search"])
                    ->bind(":search", $keyword, 'string')
                    ->bind(":like_search", '%' . $keyword . '%', 'string');
            }
            if($search_categories)
            {
                if($query != null){
                    $union_query = $query;
                }   

                $query = $this->Equipments->find('all')
                    ->innerJoinWith('Categories')
                    ->where(["match (Categories.name, Categories.description) against(:search in boolean mode)
                        or Categories.name like :like_search or Categories.description like :like_search"])
                    ->bind(":search", $keyword, 'string')
                    ->bind(":like_search", '%' . $keyword . '%', 'string');
                
                if($union_query != null){
                    $query->union($union_query);
                }
            }
        }

        if ($query != null)
        {
            $query->order(["Equipments.".$sort_field => $sort_dir]);
        }
        
        $equipments = [];
        $archivedEquipments = [];
        $allEquipments = $this->paginate($query);
        foreach ($allEquipments as $equipment){
            $withTime = (isset($start_time_available) && isset($end_time_available));
            $isValidAvailable = false;
            if($withTime){
                $isValidAvailable = (($search_available && $equipment->isAvailableBetween($start_time_available, $end_time_available)) || ($search_unavailable && !$equipment->isAvailableBetween($start_time_available, $end_time_available)));
            } else {
                $isValidAvailable = (($search_available && $equipment->available) || ($search_unavailable && !$equipment->available));
            }
            
            if($isValidAvailable){
                if($withTime){
                    $equipment['available_between'] = $equipment->isAvailableBetween($start_time_available, $end_time_available);
                }
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

