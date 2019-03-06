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
     * View method
     *
     * @param string|null $id Room id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $room = $this->Rooms->get($id, [
            'contain' => ['Services' => [
                'sort' => ['Services.name' => 'asc']
                ]
            ]
        ]);

        $this->set(compact('room'));
        $this->set('_serialize', ['room']);
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
                if($this->isApi()){
                    $success = true;
                } else {
                    $this->Flash->success(__('The room has been saved.'));
                    return $this->redirect(['action' => 'index']);
                }
            } else if($this->isApi()){
                $success = false;
            } else {
                $this->Flash->error(__('The room could not be saved. Please, try again.'));
            }
        }

        if($this->isApi()){
            $this->set(compact('success'));
            $this->set('_serialize', ['success']);
        } else {
            $this->set(compact('room'));
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Room id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $room = $this->Rooms->get($id, [
            'contain' => ['Services']
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
            $room = $this->Rooms->patchEntity($room, $data);
            if ($this->Rooms->save($room)) {
                if($this->isApi()){
                    $success = true;
                } else {
                    $this->Flash->success(__('The room has been saved.'));
                    return $this->redirect(['action' => 'index']);
                }
            } else if($this->isApi()){
                $success = false;
            } else {
                $this->Flash->error(__('The room could not be saved. Please, try again.'));
            }
        }

        if($this->isApi()){
            $this->set(compact('success'));
            $this->set('_serialize', ['success']);
        } else {
            $services = $this->Rooms->Services->find('list', ['limit' => 200]);
            $this->set(compact('room', 'services'));
        }
    }

    public function consult($id = null)
    {
        $room = $this->Rooms->get($id, [
            'contain' => ['Services']
        ]);
        if($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $image = $data['image'];
            if($image['tmp_name'] != '') {
                $imageData  = file_get_contents($image['tmp_name']);
                $b64   = base64_encode($imageData);
                $data['image'] = $b64;
            } else {
                $data['image'] = $room->image;
            }

            $room = $this->Rooms->patchEntity($room, $data);
            if ($this->Rooms->save($room)) {
                $this->Flash->success(__('The room has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The room could not be saved. Please, try again.'));
        }
        $services = $this->Rooms->Services->find('list', ['limit' => 200]);
        $this->set(compact('room', 'services'));
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
            if($this->isApi()){
                $success = true;
            } else {
                $this->Flash->success(__('The room has been deleted.'));
            }
        } else {
            if($this->isApi()){
                $success = false;
            } else {
                $this->Flash->error(__('The room could not be deleted. Please, try again.'));
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

        $state = $deleted == null ? 'reactivated' : 'deactivated';

        if ($this->Rooms->save($room)) {
            if($this->isApi()){
                $success = true;
            } else {
                $this->Flash->success(__('The room has been ' . $state .'.'));
            }
        } else {
            if($this->isApi()){
                $success = false;
            } else {
                $this->Flash->error(__('The room could not be ' . $state . '. Please, try again.'));
            }
        }

        if($this->isApi()){
            $this->set(compact('success'));
            $this->set('_serialize', ['success']);
        } else {
            return $this->redirect($this->referer());
        }
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

        if($this->isApi()){
            $this->getRequest()->allowMethod('post');
        }else {
            $this->getRequest()->allowMethod('get', 'ajax');
        }
   
        $keyword = "";
        $sort_field = "name";
        $sort_dir = "asc";

        $search_available = true;
        $search_unavailable = true;
        $search_rooms = true;
        $search_services = true;

        if ($this->isApi()){
            $jsonData = $this->getRequest()->input('json_decode', true);
            $keyword = $jsonData['keyword'];
            $sort_field = $jsonData['sort_field'];
            $sort_dir = $jsonData['sort_dir'];
            $search_unavailable = $jsonData['search_unavailable'] == 'true';
        } else {
            $keyword = $this->getRequest()->getQuery('keyword');
            $sort_field = $this->getRequest()->getQuery('sort_field');
            $sort_dir = $this->getRequest()->getQuery('sort_dir');
            
            $filters = $this->getRequest()->getQuery('filters');
            $search_available = $filters['search_available'] == 'true';
            $search_unavailable = $filters['search_unavailable'] == 'true';
            $search_rooms = $filters['search_rooms'] == 'true';
            $search_services = $filters['search_services'] == 'true';
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
            //$query->epilog($connection->newQuery()->order(['Mentors_' . $sort_field => $sort_dir]));
             $query->order(["Rooms.".$sort_field => $sort_dir]);
         }
        
        $rooms = [];
        $archivedRooms = [];
        $allRooms = $this->paginate($query);
        foreach ($allRooms as $room){
            if($search_available && $room->available || $search_unavailable && !$room->available){
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
