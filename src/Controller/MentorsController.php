<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\I18n\Time;
use Cake\Datasource\ConnectionManager;
/**
 * Mentors Controller
 *
 * @property \App\Model\Table\MentorsTable $Mentors
 *
 * @method \App\Model\Entity\Mentor[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class MentorsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        ini_set('memory_limit', '-1');
        $mentors = $this->Mentors->find('all', [
            'contain' => ['Skills']
        ])->where('deleted is null')->order(['email' => 'asc']);
        $archivedMentors = $this->Mentors->find('all', [
            'contain' => ['Skills']
        ])->where('deleted is not null')->order(['email' => 'asc']);

        $this->set(compact('mentors', 'archivedMentors'));
        $this->set('_serialize', ['mentors', 'archivedMentors']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $mentor = $this->Mentors->newEntity();
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
            $mentor = $this->Mentors->patchEntity($mentor, $data);
            if ($this->Mentors->save($mentor)) {
                $success = true;

                $this->Flash->success(__('The mentor has been saved.'));
                if (!$this->isApi()) {
                    return $this->redirect(['action' => 'index']);
                }
            } else {
                $success = false;

                $this->Flash->error(__('The mentor could not be saved. Please, try again.'));
            }
        }

        $this->set(compact('mentor', 'success'));
        $this->set('_serialize', ['success']);
    }

    public function consult($id = null)
    {
        $mentor = $this->Mentors->get($id, [
            'contain' => ['Skills']
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
                    $data['image'] = $mentor->image;
                }
            }
            $mentor = $this->Mentors->patchEntity($mentor, $data);
            if ($this->Mentors->save($mentor)) {
                $success = true;

                $this->Flash->success(__('The mentor has been saved.'));
                if (!$this->isApi()) {
                    return $this->redirect(['action' => 'consult', $mentor->id]);
                }
            } else {
                $success = false;

                $this->Flash->error(__('The mentor could not be saved. Please, try again.'));
            }
            
        }

        $this->set(compact('mentor', 'success'));
        $this->set('_serialize', ['mentor', 'success']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Mentor id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->getRequest()->allowMethod(['get', 'post', 'delete']);
        $mentor = $this->Mentors->get($id);
        $success = false;
        if ($this->Mentors->delete($mentor)) {
            $success = true;
            $this->Flash->success(__('The mentor has been deleted.'));
        } else {
            $success = false;
            $this->Flash->error(__('The mentor could not be deleted. Please, try again.'));
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
        $mentor = $this->Mentors->get($id);
        $mentor->deleted = $deleted;
        $success = false;

        if ($this->Mentors->save($mentor)) {
            $success = true;
            if($deleted == null){
                $this->Flash->success(__('The mentor has been reactivated.'));
            } else {
                $this->Flash->success(__('The mentor has been deactivated.'));
            }
        } else {
            $success = false;
            if($deleted == null){
                $this->Flash->error(__('The mentor could not be reactivated. Please, try again.'));
            } else {
                $this->Flash->error(__('The mentor could not be deactivated. Please, try again.'));
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
            $mentor = $this->Mentors->find('all')
                ->where(["lower(email) = :search"])
                ->bind(":search", strtolower($jsonData['email']), 'string')->first();
            
                $this->set(compact('mentor'));
            $this->set('_serialize', ['mentor']);  
        } else {
            return $this->redirect(['action' => 'index']);
        }
    }

    public function isAuthorized($user)
    {
        return $this->Auth->user('admin_status') == 'admin';
    }

    /**
     * fonction search qui est appelée par la ajax request de la page mentors/index
     * en fonction des requetes ajax retourne différentes liste de mentors
     */

    public function search()
    {
        ini_set('memory_limit', '-1');

        $this->getRequest()->allowMethod(['post', 'ajax', 'get']);
   
        $keyword = "";
        $sort_field = "email";
        $sort_dir = "asc";

        $search_available = true;
        $search_unavailable = true;
        $search_mentors = true;
        $search_skills = true;

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
            $search_mentors = isset($filters['search_mentors']) ? $filters['search_mentors'] == 'true' : true;
            $search_skills = isset($filters['search_skills']) ?  $filters['search_skills'] == 'true' : true;
            $start_time_available = isset($filters['start_time_available']) ? $filters['start_time_available'] : null;
            $end_time_available = isset($filters['end_time_available']) ? $filters['end_time_available'] : null;
        }

        $query = null;

        $options = [];
        if($this->isApi())
        {
            $options = ['contain' => ['Skills']];
        }
        
        if($keyword == '')
        {
            $query = $this->Mentors->find('all', $options);
        }
        else
        {
            $union_query = null;

            if($search_mentors)
            {
                $query = $this->Mentors->find('all', $options)
                    ->where(["match (Mentors.email, Mentors.first_name, Mentors.last_name, Mentors.description) against(:search in boolean mode)
                        or Mentors.email like :like_search or Mentors.first_name like :like_search or Mentors.last_name like :like_search or Mentors.description like :like_search"])
                    ->bind(":search", $keyword, 'string')
                    ->bind(":like_search", '%' . $keyword . '%', 'string');
            }
            if($search_skills)
            {
                if($query != null){
                    $union_query = $query;
                }   

                $query = $this->Mentors->find('all')
                    ->innerJoinWith('Skills')
                    ->where(["match (Skills.name, Skills.description) against(:search in boolean mode)
                        or Skills.name like :like_search or Skills.description like :like_search"])
                    ->bind(":search", $keyword, 'string')
                    ->bind(":like_search", '%' . $keyword . '%', 'string');
                
                if($union_query != null){
                    $query->union($union_query);
                }
            }
        }

        if ($query != null)
        {
            $query->order(["Mentors.".$sort_field => $sort_dir]);
        }
        
        $mentors = [];
        $archivedMentors = [];
        $allMentors = $query->toList();
        foreach ($allMentors as $mentor){
            $withTime = (isset($start_time_available) && isset($end_time_available));
            $isValidAvailable = false;
            if($withTime){
                $isValidAvailable = (($search_available && $mentor->isAvailableBetween($start_time_available, $end_time_available)) || ($search_unavailable && !$mentor->isAvailableBetween($start_time_available, $end_time_available)));
            } else {
                $isValidAvailable = (($search_available && $mentor->available) || ($search_unavailable && !$mentor->available));
            }
            
            if($isValidAvailable){
                if($withTime){
                    $mentor['available_between'] = $mentor->isAvailableBetween($start_time_available, $end_time_available);
                }
                
                if ($mentor->deleted != null && $mentor->deleted != "") {
                    if (!in_array($mentor,$archivedMentors))
                    {
                        array_push($archivedMentors, $mentor);
                    }
                } else {
                    if (!in_array($mentor,$mentors))
                    {
                        array_push($mentors, $mentor);
                    }
                }
            }
        }
        
        $this->set(compact('mentors', 'archivedMentors'));
        $this->set('_serialize', ['mentors', 'archivedMentors']);
    }

}
