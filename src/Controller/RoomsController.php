<?php
namespace App\Controller;

use App\Controller\AppController;

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
            'contain' => ['Services']
        ]);

        $this->set('room', $room);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $room = $this->Rooms->newEntity();
        if ($this->request->is('post')) {
            $room = $this->Rooms->patchEntity($room, $this->request->getData());
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
        if ($this->request->is(['patch', 'post', 'put'])) {
            $room = $this->Rooms->patchEntity($room, $this->request->getData());
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
        $this->request->allowMethod(['post', 'delete']);
        $room = $this->Rooms->get($id);
        if ($this->Rooms->delete($room)) {
            $this->Flash->success(__('The room has been deleted.'));
        } else {
            $this->Flash->error(__('The room could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function isAuthorized($user)
    {
        return $this->Auth->user('admin_status') == 'admin';
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
            $this->getRequest()->allowMethod('ajax');
        }
   
        $keyword = "";
        $sort_field = "name";
        $sort_dir = "asc";

        $search_available = true;
        $search_unavailable = true;
        $search_rooms = true;
        $search_services = true;

        if ($this->getRequest()->is('ajax')){
            $keyword = $this->getRequest()->getQuery('keyword');
            $sort_field = $this->getRequest()->getQuery('sort_field');
            $sort_dir = $this->getRequest()->getQuery('sort_dir');
            
            $filters = $this->getRequest()->getQuery('filters');
            $search_available = $filters['search_available'] == 'true';
            $search_unavailable = $filters['search_unavailable'] == 'true';
            $search_rooms = $filters['search_rooms'] == 'true';
            $search_services = $filters['search_services'] == 'true';
        
        } else if ($this->getRequest()->is('post')){
            $jsonData = $this->getRequest()->input('json_decode', true);
            $keyword = $jsonData['keyword'];
            $sort_field = $jsonData['sort_field'];
            $sort_dir = $jsonData['sort_dir'];
        }
        
        if($keyword == '')
        {
            $query = $this->Rooms->find('all');
        }
        else
        {
            if ($this->isApi()){
                $queryRooms = $this->Rooms->find('all', [
                    'contain' => ['Services']
                ])
                    ->where(["match (Rooms.name, Rooms.description) against(:search in boolean mode)"])
                    ->bind(":search", $keyword . '*', 'string');
                $queryServices = $this->Rooms->find('all', [
                    'contain' => ['Services']
                ])
                    ->innerJoinWith('Services')
                    ->where(["match (Services.name, Services.description) against(:search in boolean mode)"])
                    ->bind(":search", $keyword . '*', 'string');

                $queryRooms->union($queryServices);
                $query = $queryRooms;
            }
            else if($search_rooms && $search_services)
            {
                $queryRooms = $this->Rooms->find('all')
                    ->where(["match (Rooms.name, Rooms.description) against(:search in boolean mode)"])
                    ->bind(":search", $keyword . '*', 'string');
                $queryServices = $this->Rooms->find('all')
                    ->innerJoinWith('Services')
                    ->where(["match (Services.name, Services.description) against(:search in boolean mode)"])
                    ->bind(":search", $keyword . '*', 'string');

                $queryRooms->union($queryServices);
                $query = $queryRooms;
            }
            else if($search_rooms)
            {
                $query = $this->Rooms->find('all')
                    ->where(["match (Rooms.name, Rooms.description) against(:search in boolean mode)"])
                    ->bind(":search", $keyword . '*', 'string');
            }
            else if($search_services)
            {
                $query = $this->Rooms->find('all')
                    ->innerJoinWith('Services')
                    ->where(["match (Services.name, Services.description) against(:search in boolean mode)"])
                    ->bind(":search", $keyword . '*', 'string');
            }

            
        }

        if(!is_null($query))
        {
            $query->order(['Rooms.'.$sort_field => 'asc']);
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
