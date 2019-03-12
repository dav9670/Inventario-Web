<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\I18n\Time;
//use Cake\Datasource\ConnectionManager;

/**
 * Rooms Controller
 *
 * @property \App\Model\Table\RoomsTable $Rooms
 *
 * @method \App\Model\Entity\Room[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class RoomsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        ini_set('memory_limit', '-1');
        $rooms = $this->Rooms->find('all', [
            'contain' => ['Services']
        ])->where('deleted is null')->order(['name' => 'asc']);
        $archivedRooms = $this->Rooms->find('all', [
            'contain' => ['Services']
        ])->where('deleted is not null')->order(['name' => 'asc']);
        $this->set(compact('rooms', 'archivedRooms'));
        $this->set('_serialize', ['rooms', 'archivedRooms']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $room = $this->Rooms->newEntity();
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
            $room = $this->Rooms->patchEntity($room, $data);
            if ($this->Rooms->save($room)) {
                $success = true;

                $this->Flash->success(__('The room has been saved.'));
                if (!$this->isApi()) {
                    return $this->redirect(['action' => 'index']);
                }
            } else {
                $success = false;

                $this->Flash->error(__('The room could not be saved. Please, try again.'));
            }
        }

        $this->set(compact('room', 'success'));
        $this->set('_serialize', ['success']);
    }

    public function consult($id = null)
    {
        $room = $this->Rooms->get($id, [
            'contain' => ['Services']
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
                    $data['image'] = $room->image;
                }
            }
            $room = $this->Rooms->patchEntity($room, $data);

            if ($this->Rooms->save($room)) {
                $success = true;

                $this->Flash->success(__('The room has been saved.'));
                if (!$this->isApi()) {
                    return $this->redirect(['action' => 'consult', $room->id]);
                }
            } else {
                $success = false;

                $this->Flash->error(__('The room could not be saved. Please, try again.'));
            }
            
        }

        $this->set(compact('room', 'success'));
        $this->set('_serialize', ['room','success']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Room id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->getRequest()->allowMethod(['get', 'post', 'delete']);
        $room = $this->Rooms->get($id);
        $success = false;
        if ($this->Rooms->delete($room)) {
            $success = true;
            $this->Flash->success(__('The room has been deleted.'));
        } else {
            $success = false;
            $this->Flash->error(__('The room could not be deleted. Please, try again.'));
        }

        $this->set(compact('success'));
        $this->set('_serialize', ['success']);
        return $this->redirect(['action' => 'index']);
    }

    /**
     * function deactivate
     * désactive une room, set sa date de delete à now
     */
    public function deactivate($id = null){
        $this->setDeleted($id, Time::now());
    }

    /**
     * function reactivate
     * reactive une room, set sa valeur deleted à null.
     */
    public function reactivate($id = null){
        $this->setDeleted($id, null);
    }

    private function setDeleted($id, $deleted){
        $this->getRequest()->allowMethod(['get', 'post']);
        $room = $this->Rooms->get($id);
        $room->deleted = $deleted;
        $success = false;

        if ($this->Rooms->save($room)) {
            $success = true;
            if($deleted == null){
                $this->Flash->success(__('The room has been reactivated.'));
            } else {
                $this->Flash->success(__('The room has been deactivated.'));
            }
        } else {
            $success = false;
            if($deleted == null){
                $this->Flash->error(__('The room could not be reactivated. Please, try again.'));
            } else {
                $this->Flash->error(__('The room could not be deactivated. Please, try again.'));
            }   
        }

        $this->set(compact('success'));
        $this->set('_serialize', ['success']);
        return $this->redirect($this->referer());
    }

    public function isAuthorized($user)
    {
        return $this->Auth->user('admin_status') == 'admin';
    }

    public function isTaken()
    {
        if ($this->isApi()){
            $jsonData = $this->getRequest()->input('json_decode', true);
            $room = $this->Rooms->find('all')
                ->where(["lower(name) = :search"])
                ->bind(":search", strtolower($jsonData['name']), 'string')->first();
            
            $this->set(compact('room'));
            $this->set('_serialize', ['room']);  
        } else {
            return $this->redirect(['action' => 'index']);
        }
    }

    /**
     * fonction search qui est appelée par la ajax request de la page rooms/index
     * en fonction des requetes ajax retourne différentes liste de rooms
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
        $search_rooms = true;
        $search_services = true;

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
            $search_rooms = isset($filters['search_rooms']) ? $filters['search_rooms'] == 'true' : true;
            $search_services = isset($filters['search_services']) ?  $filters['search_services'] == 'true' : true;
            $start_time_available = isset($filters['start_time_available']) ? $filters['start_time_available'] : null;
            $end_time_available = isset($filters['end_time_available']) ? $filters['end_time_available'] : null;
        }

        $query = null;

        $options = [];
        if($this->isApi())
        {
            $options = ['contain' => ['Services']];
        }
        
        if($keyword == '')
        {
            $query = $this->Rooms->find('all', $options);
        }
        else
        {
            
            $union_query = null;

            if($search_rooms)
            {
                $query = $this->Rooms->find('all', $options)
                    ->where(["match (Rooms.name, Rooms.description) against(:search in boolean mode)
                        or Rooms.name like :like_search or Rooms.description like :like_search"])
                    ->bind(":search", $keyword, 'string')
                    ->bind(":like_search", '%' . $keyword . '%', 'string');
            }
            if($search_services)
            {
                if($query != null){
                    $union_query = $query;
                }
                $query = $this->Rooms->find('all')
                    ->innerJoinWith('Services')
                    ->where(["match (Services.name, Services.description) against(:search in boolean mode)
                        or Services.name like :like_search or Services.description like :like_search"])
                    ->bind(":search", $keyword, 'string')
                    ->bind(":like_search", '%' . $keyword . '%', 'string');
                
                if($union_query != null){
                    $query->union($union_query);
                }
            }
        }

        if ($query != null)
         {
            //$connection = ConnectionManager::get('default');
            //$query->epilog($connection->newQuery()->order(['rooms_' . $sort_field => $sort_dir]));
             $query->order(["Rooms.".$sort_field => $sort_dir]);
         }
        
        $rooms = [];
        $archivedRooms = [];
        $allRooms = $this->paginate($query);
        foreach ($allRooms as $room){
            $withTime = (isset($start_time_available) && isset($end_time_available));
            $isValidAvailable = false;
            if($withTime){
                $isValidAvailable = (($search_available && $room->isAvailableBetween($start_time_available, $end_time_available)) || ($search_unavailable && !$room->isAvailableBetween($start_time_available, $end_time_available)));
            } else {
                $isValidAvailable = (($search_available && $room->available) || ($search_unavailable && !$room->available));
            }
            
            if($isValidAvailable){
                if($withTime){
                    $room['available_between'] = $room->isAvailableBetween($start_time_available, $end_time_available);
                }
                if ($room->deleted != null && $room->deleted != "") {
                    if (!in_array($room,$archivedRooms))
                    {
                        array_push($archivedRooms, $room);
                    }
                } else {
                    if (!in_array($room,$rooms))
                    {
                        array_push($rooms, $room);
                    }
                }
            }
        }
        
        $this->set(compact('rooms', 'archivedRooms'));
        $this->set('_serialize', ['rooms', 'archivedRooms']);
    }
}
