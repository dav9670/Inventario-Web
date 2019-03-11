<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\I18n\Time;

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
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    /*public function add()
    {
        $licence = $this->Licences->newEntity();
        $success = false;
        if ($this->request->is('post'))
        {
            $data = $this->request->getData();
            $data['start_time'] = $data['start_time'] . " 00:00:00";
            if ($data['end_time'] != "")
            {
                $data['end_time'] = $data['end_time'] . " 00:00:00";
            }

            if (is_array($data["products"]["_ids"]))
            {
                if(!$this->isApi())
                {
                    $image = $data['image'];
                    if($image['tmp_name'] != '')
                    {
                        $imageData  = file_get_contents($image['tmp_name']);
                        $b64   = base64_encode($imageData);
                        $data['image'] = $b64;
                    }
                }
                $licence = $this->Licences->patchEntity($licence, $data);

                if ($this->Licences->save($licence))
                {
                    if($this->isApi())
                    {
                        $success = true;
                    }
                    else
                    {
                        $this->Flash->success(__('The licence has been saved.'));
                        return $this->redirect(['action' => 'index']);
                    }
                }
                else if($this->isApi())
                {
                    $success = false;
                }
                else
                {
                    $this->Flash->error(__('The licence could not be saved. Please, try again.'));
                }
            }
            else
            {
                $this->Flash->error(__('At least one Product is required. Please, try again.'));
            }
        }

        if($this->isApi())
        {
            $this->set(compact('success'));
            $this->set('_serialize', ['success']);
        } 
        else
        {
            $this->set(compact('licence'));
        }
    }*/

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $licence = $this->Licences->newEntity();
        $success = false;
        if ($this->request->is('post')) {
            $data = $this->request->getData();

            if (is_array($data["products"]["_ids"])){
                $data['start_time'] = $data['start_time'] . " 00:00:00";
                if ($data['end_time'] != ""){
                    $data['end_time'] = $data['end_time'] . " 00:00:00";
                }

                if(!$this->isApi()){
                    $image = $data['image'];
                    if($image['tmp_name'] != '') {
                        $imageData  = file_get_contents($image['tmp_name']);
                        $b64   = base64_encode($imageData);
                        $data['image'] = $b64;
                    }
                }
                $licence = $this->Licences->patchEntity($licence, $data);
                if ($this->Licences->save($licence)) {
                    $success = true;

                    $this->Flash->success(__('The licence has been saved.'));
                    return $this->redirect(['action' => 'index']);
                } else {
                    $success = false;

                    $this->Flash->error(__('The licence could not be saved. Please, try again.'));
                }
            } else {
                $success = false;

                $this->Flash->error(__('At least one Product is required. Please, try again.'));
            }
        }

        $this->set(compact('licence', 'success'));
        $this->set('_serialize', ['success']);
    }

    /*public function consult($id = null)
    {
        $licence = $this->Licences->get($id, [
            'contain' => ['Products']
        ]);
        if($this->request->is(['patch', 'post', 'put']))
        {
            $data = $this->request->getData();
            $data['start_time'] = $data['start_time'] . " 00:00:00";
            if ($data['end_time'] != "")
            {
                $data['end_time'] = $data['end_time'] . " 00:00:00";
            }

            $image = $data['image'];
            if($image['tmp_name'] != '')
            {
                $imageData  = file_get_contents($image['tmp_name']);
                $b64   = base64_encode($imageData);
                $data['image'] = $b64;
            }
            else
            {
                $data['image'] = $licence->image;
            }

            $licence = $this->Licences->patchEntity($licence, $data);
            if ($this->Licences->save($licence))
            {
                $this->Flash->success(__('The licence has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            else
            {
                $this->Flash->error(__('The licence could not be saved. Please, try again.'));
            }
        }
        $products = $this->Licences->Products->find('list', ['limit' => 200]);
        $this->set(compact('licence', 'products'));
    }*/

    public function consult($id = null)
    {
        $licence = $this->Licences->get($id, [
            'contain' => ['Products']
        ]);
        $success = false;

        if($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            $data['start_time'] = $data['start_time'] . " 00:00:00";
            if ($data['end_time'] != "")
            {
                $data['end_time'] = $data['end_time'] . " 00:00:00";
            }

            if(!$this->isApi()){
                $image = $data['image'];
                if($image['tmp_name'] != '') {
                    $imageData  = file_get_contents($image['tmp_name']);
                    $b64   = base64_encode($imageData);
                    $data['image'] = $b64;
                } else {
                    $data['image'] = $licence->image;
                }
            }
            $licence = $this->Licences->patchEntity($licence, $data);

            if ($this->Licences->save($licence)) {
                $success = true;

                $this->Flash->success(__('The licence has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $success = false;

                $this->Flash->error(__('The licence could not be saved. Please, try again.'));
            }
            
        }

        $this->set(compact('licence', 'success'));
        $this->set('_serialize', ['success']);
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
        $this->getRequest()->allowMethod(['get', 'post', 'delete']);
        $licence = $this->Licences->get($id);
        $success = false;
        if ($this->Licences->delete($licence)) {
            $success = true;
            $this->Flash->success(__('The licence has been deleted.'));
        } else {
            $success = false;
            $this->Flash->error(__('The licence could not be deleted. Please, try again.'));
        }

        $this->set(compact('success'));
        $this->set('_serialize', ['success']);
        return $this->redirect(['action' => 'index']);
    }

    /**
     * function deactivate
     * désactive une licence, set sa date de delete à now
     */
    public function deactivate($id = null)
    {
        $this->setDeleted($id, Time::now());
    }

    /**
     * function reactivate
     * reactive une licence, set sa valeur deleted à null.
     */
    public function reactivate($id = null)
    {
        $this->setDeleted($id, null);
    }

    private function setDeleted($id, $deleted)
    {
        $this->getRequest()->allowMethod(['get', 'post']);
        $licence = $this->Licences->get($id);
        $licence->deleted = $deleted;
        $success = false;

        if ($this->Licences->save($licence)) {
            $success = true;
            if($deleted == null){
                $this->Flash->success(__('The licence has been reactivated.'));
            } else {
                $this->Flash->success(__('The licence has been deactivated.'));
            }
        } else {
            $success = false;
            if($deleted == null){
                $this->Flash->error(__('The licence could not be reactivated. Please, try again.'));
            } else {
                $this->Flash->error(__('The licence could not be deactivated. Please, try again.'));
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
        if ($this->isApi())
        {
            $jsonData = $this->getRequest()->input('json_decode', true);
            $licence = $this->Licences->find('all')
                ->where(["lower(name) = :search AND lower(platform) = :platform"])
                ->bind(":search", strtolower($jsonData['name']), 'string')
                ->bind(":platform", strtolower($jsonData['platform']), 'string')->first();
            
                $this->set(compact('licence'));
            $this->set('_serialize', ['licence']);  
        }
        else
        {
            return $this->redirect(['action' => 'index']);
        }
    }

    /**
     * fonction search qui est appelée par la ajax request de la page licences/index
     * en fonction des requetes ajax retourne différentes liste de licences
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
        $search_licences = true;
        $search_products = true;

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
            $search_licences = isset($filters['search_licences']) ? $filters['search_licences'] == 'true' : true;
            $search_products = isset($filters['search_products']) ?  $filters['search_products'] == 'true' : true;
            $start_time_available = isset($filters['start_time_available']) ? $filters['start_time_available'] : null;
            $end_time_available = isset($filters['end_time_available']) ? $filters['end_time_available'] : null;
        }
        
        $query = null;
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
                    ->where(["match (Licences.name, Licences.description) against(:search in boolean mode)"])
                    ->bind(":search", $keyword . '*', 'string');
                $queryProducts = $this->Licences->find('all', [
                    'contain' => ['Products']
                ])
                    ->innerJoinWith('Products')
                    ->where(["match (Products.name, Products.platform, Products.description) against(:search in boolean mode)"])
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
                    ->where(["match (Products.name, Products.platform, Products.description) against(:search in boolean mode)"])
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
                    ->where(["match (Products.name, Products.platform, Products.description) against(:search in boolean mode)"])
                    ->bind(":search", $keyword . '*', 'string');
            }

            
        }

        if(!is_null($query))
        {
            $query->order(['Licences.'.$sort_field => $sort_dir]);
        }
        
        $licences = [];
        $archivedLicences = [];
        $allLicences = $this->paginate($query);
        foreach ($allLicences as $licence){
            $withTime = (isset($start_time_available) && isset($end_time_available));
            $isValidAvailable = false;
            if($withTime){
                $isValidAvailable = (($search_available && $licence->isAvailableBetween($start_time_available, $end_time_available)) || ($search_unavailable && !$licence->isAvailableBetween($start_time_available, $end_time_available)));
            } else {
                $isValidAvailable = (($search_available && $licence->available) || ($search_unavailable && !$licence->available));
            }
            
            if($isValidAvailable){
                if($withTime){
                    $licence['available_between'] = $licence->isAvailableBetween($start_time_available, $end_time_available);
                }
                if ($licence->deleted != null && $licence->deleted != "") {
                    
                    if (!in_array($licence,$archivedLicences))
                    {
                        array_push($archivedLicences, $licence);
                    }
                    
                } else {
                    
                    if (!in_array($licence,$licences))
                    {
                        array_push($licences, $licence);
                    }
                }
            }
        }
        
        $this->set(compact('licences', 'archivedLicences'));
        $this->set('_serialize', ['licences', 'archivedLicences']);
    }
}
