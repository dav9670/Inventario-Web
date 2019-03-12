<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Skills Controller
 *
 * @property \App\Model\Table\SkillsTable $Skills
 *
 * @method \App\Model\Entity\Skill[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SkillsController extends AppController
{

    public function initialize()
    {
        parent::initialize();
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->set('skills', $this->Skills->find('all')->order(['name' => 'asc']));
        $this->set('_serialize', ['skills']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $skill = $this->Skills->newEntity();
        $success = false;
        if ($this->getRequest()->is('post')) {
            $skill = $this->Skills->patchEntity($skill, $this->getRequest()->getData());
            if ($this->Skills->save($skill)) {
                    $success = true;

                    $this->Flash->success(__('The skill has been saved.'));
                    if (!$this->isApi()) {
                        return $this->redirect(['action' => 'index']);
                    }
            } else {
                $success = false;

                $this->Flash->error(__('The skill could not be saved. Please, try again.'));
            }
        }

        $this->set(compact('skill', 'success'));
        $this->set('_serialize', ['success']);
    }

    /**
     * Consult method
     *
     * @param string|null $id Skill id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function consult($id = null)
    {
        $skill = $this->Skills->get($id, [
            'contain' => ['Mentors']
        ]);
        $success = false;
        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
            $skill = $this->Skills->patchEntity($skill, $this->getRequest()->getData());
            if ($this->Skills->save($skill)) {
                $success = true;

                $this->Flash->success(__('The skill has been saved.'));
                if (!$this->isApi()) {
                    return $this->redirect(['action' => 'consult', $skill->id]);
                }
            } else {
                $success = false;

                $this->Flash->error(__('The skill could not be saved. Please, try again.'));
            }            
        }
        $this->set(compact('skill', 'success'));
        $this->set('_serialize', ['skill', 'success']);
    }
    

    /**
     * Delete method
     *
     * @param string|null $id Skill id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->getRequest()->allowMethod(['get', 'post', 'delete']);
        $skill = $this->Skills->get($id);
        $success = false;
        if ($this->Skills->delete($skill)) {
            $success = true;
            $this->Flash->success(__('The skill has been deleted.'));
        } else {
            $success = false;
            $this->Flash->error(__('The skill could not be deleted. Please, try again.'));
        }

        $this->set(compact('success'));
        $this->set('_serialize', ['success']);
        if (!$this->isApi()) {
            return $this->redirect(['action' => 'index']);
        }
    }

    public function isTaken()
    {
        if ($this->isApi()){
            $jsonData = $this->getRequest()->input('json_decode', true);
            $skill = $this->Skills->find('all')
                ->where(["lower(name) = :search"])
                ->bind(":search", strtolower($jsonData['name']), 'string')->first();
            
                $this->set(compact('skill'));
            $this->set('_serialize', ['skill']);  
        } else {
            return $this->redirect(['action' => 'index']);
        }
    }

    private function modifyLink($func)
    {
        $this->getRequest()->allowMethod(['post', 'delete']);

        $skill = "";
        $mentor = "";
        $success = false;
        if($this->isApi()){
            $jsonData = $this->getRequest()->input('json_decode', true);
            $skill = $this->Skills->get($jsonData['skill']);
            $mentor = $this->Skills->Mentors->get($jsonData['mentor']);
        } else {
            $skill = $this->Skills->get($this->getRequest()->getQuery('skill'));
            $mentor = $this->Skills->Mentors->get($this->getRequest()->getQuery('mentor'));
        }

        $state = $func == 'link' ? 'created' : 'deleted';

        if($func == 'link' && $this->Skills->Mentors->link($skill, [$mentor]) || $func == 'unlink' && $this->Skills->Mentors->unlink($skill, [$mentor])){
            $success = true;
            //$this->Flash->success(__('The association has been ' . $state . '.'));
        } else {
            $success = false;
            //$this->Flash->error(__('The association could not be ' . $state . '. Please, try again.'));
        }

        if($this->isApi()){
            $this->set(compact('success'));
            $this->set('_serialize', ['success']);
        } else {
            $this->autoRender = false;
            return /*$this->redirect(['action' => 'consult', $skill->id])*/;
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
        $this->getRequest()->allowMethod(['post', 'ajax', 'get']);
   
        $keyword = "";
        $sort_field = "";
        $sort_dir = "";
        $mentor_id = "";

        if ($this->isApi()){
            $jsonData = $this->getRequest()->input('json_decode', true);
            $keyword = $jsonData['keyword'] != null ? $jsonData['keyword'] : '';
            $sort_field = $jsonData['sort_field'] != null ? $jsonData['sort_field'] : 'name';
            $sort_dir = $jsonData['sort_dir'] != null ? $jsonData['sort_dir'] : 'asc';
            $mentor_id = $jsonData['mentor_id'] != null ? $jsonData['mentor_id'] : '';
        } else {
            $keyword = $this->getRequest()->getQuery('keyword') != null ? $this->getRequest()->getQuery('keyword') : '';
            $sort_field = $this->getRequest()->getQuery('sort_field') != null ? $this->getRequest()->getQuery('sort_field') : 'name';
            $sort_dir = $this->getRequest()->getQuery('sort_dir') != null ? $this->getRequest()->getQuery('sort_dir') : 'asc';
            $mentor_id = $this->getRequest()->getQuery('mentor_id') != null ? $this->getRequest()->getQuery('mentor_id') : '';
        }
        
        if($keyword == '')
        {
            $query = $this->Skills->find('all');
        }
        else
        {
            $query = $this->Skills->find('all')
                ->where(["match (name, description) against(:search in boolean mode)
                or name like :like_search or description like :like_search"])
                ->bind(":search", $keyword, 'string')
                ->bind(":like_search", '%' . $keyword . '%', 'string');
        }

        if($mentor_id != '')
        {
            $mentorSkills = $this->Skills->Mentors->find('all')
                ->select('Skills.id')
                ->where('Mentors.id = :id')
                ->bind(':id', $mentor_id)
                ->innerJoinWith('Skills');
            $query->where(["Skills.id not in" => $mentorSkills]);
        }

        $query->order([$sort_field => $sort_dir]);
        
        $this->set('skills', $query);
        $this->set('_serialize', ['skills']);
    }

    public function isAuthorized($user)
    {
        return $this->Auth->user('admin_status') == 'admin';
    }
}
