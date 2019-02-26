<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Services Controller
 *
 * @property \App\Model\Table\ServicesTable $Services
 *
 * @method \App\Model\Entity\Service[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ServicesController extends AppController
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
        $this->set('services', $this->Services->find('all')->order(['name' => 'asc']));
        $this->set('_serialize', ['services']);
    }

    /**
     * View method
     *
     * @param string|null $id Service id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $service = $this->Services->get($id, [
            'contain' => ['Rooms' => [
                'sort' => ['Rooms.name' => 'asc']
                ]
            ]
        ]);

        $this->set(compact('service'));
        $this->set('_serialize', ['service']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $service = $this->Services->newEntity();
        $success = false;
        if ($this->getRequest()->is('post')) {
            $service = $this->Services->patchEntity($service, $this->getRequest()->getData());
            if ($this->Services->save($service)) {
                if($this->isApi()){
                    $success = true;
                } else {
                    $this->Flash->success(__('The service has been saved.'));

                    return $this->redirect(['action' => 'index']);
                }
            } else if($this->isApi()){
                $success = false;
            }else {
                $this->Flash->error(__('The service could not be saved. Please, try again.'));
            }
        }

        if($this->isApi()){
            $this->set(compact('success'));
            $this->set('_serialize', ['success']);
        } else {
            $rooms = $this->Services->Rooms->find('list', ['limit' => 200]);
            $this->set(compact('service', 'rooms'));
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Service id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $service = $this->Services->get($id, [
            'contain' => ['Rooms']
        ]);
        $success = false;
        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
            $service = $this->Services->patchEntity($service, $this->getRequest()->getData());
            if ($this->Services->save($service)) {
                if($this->isApi()){
                    $success = true;
                } else {
                    $this->Flash->success(__('The service has been saved.'));
    
                    return $this->redirect(['action' => 'index']);
                }
            } else if($this->isApi()){
                $success = false;
            } else {
                $this->Flash->error(__('The service could not be saved. Please, try again.'));
            }
        }
        if($this->isApi()){
            $this->set(compact('success'));
            $this->set('_serialize', ['success']);
        } else {
            $rooms = $this->Services->Rooms->find('list', ['limit' => 200]);
            $this->set(compact('service', 'rooms'));
        }
    }

    /**
     * Consult method
     *
     * @param string|null $id Service id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function consult($id = null)
    {
        $service = $this->Services->get($id, [
            'contain' => ['Rooms']
        ]);
        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
            $service = $this->Services->patchEntity($service, $this->getRequest()->getData());
            if ($this->Services->save($service)) {
                $this->Flash->success(__('The service has been saved.'));

                return $this->redirect(['action' => 'consult', $service->id]);
            }
            $this->Flash->error(__('The service could not be saved. Please, try again.'));
        }
        $mentors = $this->Services->Rooms->find('list', ['limit' => 200]);
        $this->set(compact('service', 'mentors'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Service id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->getRequest()->allowMethod(['get', 'post', 'delete']);
        $service = $this->Services->get($id);
        $success = false;
        if ($this->Services->delete($service)) {
            if($this->isApi()){
                $success = true;
            } else {
                $this->Flash->success(__('The service has been deleted.'));
            }
        } else {
            if($this->isApi()){
                $success = false;
            } else {
                $this->Flash->error(__('The service could not be deleted. Please, try again.'));
            }
        }

        if($this->isApi()){
            $this->set(compact('success'));
            $this->set('_serialize', ['success']);
        } else {
            return $this->redirect(['action' => 'index']);
        }
    }

    public function isTaken()
    {
        if ($this->isApi()){
            $jsonData = $this->getRequest()->input('json_decode', true);
            $service = $this->Services->find('all')
                ->where(["lower(name) = :search"])
                ->bind(":search", strtolower($jsonData['name']), 'string')->first();
            
                $this->set(compact('service'));
            $this->set('_serialize', ['service']);  
        } else {
            return $this->redirect(['action' => 'index']);
        }
    }

    public function modifyLink($func)
    {
        $this->getRequest()->allowMethod(['post', 'delete']);

        $service = "";
        $room = "";
        $success = false;
        if ($this->isApi()){
            $jsonData = $this->getRequest()->input('json_decode', true);
            $service = $this->Services->get($jsonData['service']);
            $room = $this->Services->Rooms->get($jsonData['room']);
        } else {
            $service = $this->Services->get($this->getRequest()->getQuery('service'));
            $room = $this->Services->Rooms->get($this->getRequest()->getQuery('room'));
        }

        if($func == 'link'){
            if ($this->Services->Rooms->link($service, [$room])) {
                if($this->isApi()){
                    $success = true;
                } else {
                    $this->Flash->success(__('The association has been created.'));
                }
            } else {
                if($this->isApi()){
                    $success = false;
                } else {
                    $this->Flash->error(__('The association could not be created. Please, try again.'));
                }
            }
        } else if($func == 'unlink'){
            if ($this->Services->Rooms->unlink($service, [$room])) {
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
        }

        if($this->isApi()){
            $this->set(compact('success'));
            $this->set('_serialize', ['success']);
        } else {
            return $this->redirect(['action' => 'consult', $service->id]);
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
        if($this->isApi()){
            $this->getRequest()->allowMethod('post');
        } else {
            $this->getRequest()->allowMethod(['ajax', 'get']);
        }
   
        $keyword = "";
        $sort_field = "";
        $sort_dir = "";
        $room_id = "";

        if ($this->isApi()){
            $jsonData = $this->getRequest()->input('json_decode', true);
            $keyword = $jsonData['keyword'] != null ? $jsonData['keyword'] : '';
            $sort_field = $jsonData['sort_field'] != null ? $jsonData['sort_field'] : 'name';
            $sort_dir = $jsonData['sort_dir'] != null ? $jsonData['sort_dir'] : 'asc';
            $room_id = $jsonData['room_id'] != null ? $jsonData['room_id'] : '';
        } else {
            $keyword = $this->getRequest()->getQuery('keyword') != null ? $this->getRequest()->getQuery('keyword') : '';
            $sort_field = $this->getRequest()->getQuery('sort_field') != null ? $this->getRequest()->getQuery('sort_field') : 'name';
            $sort_dir = $this->getRequest()->getQuery('sort_dir') != null ? $this->getRequest()->getQuery('sort_dir') : 'asc';
            $room_id = $this->getRequest()->getQuery('room_id') != null ? $this->getRequest()->getQuery('room_id') : '';
        }
        
        if($keyword == '')
        {
            $query = $this->Services->find('all');
        }
        else
        {
            $query = $this->Services->find('all')
                ->where(["match (name, description) against(:search in boolean mode)"])
                ->bind(":search", $keyword . '*', 'string');
        }

        if($room_id != '')
        {
            $roomServices = $this->Services->Rooms->find('all')
                ->select('Services.id')
                ->where('Rooms.id = :id')
                ->bind(':id', $room_id)
                ->innerJoinWith('Services');
            $query->where(["Services.id not in" => $roomServices]);
        }

        $query->order([$sort_field => $sort_dir]);
        
        $this->set('services', $query);
        $this->set('_serialize', ['services']);
    }

    public function isAuthorized($user)
    {
        return $this->Auth->user('admin_status') == 'admin';
    }
}
