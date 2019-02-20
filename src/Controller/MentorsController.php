<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\I18n\Time;
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

        $mentors = $this->paginate($this->Mentors);
        $this->set(compact('mentors'));
    }

    /**
     * View method
     *
     * @param string|null $id Mentor id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $mentor = $this->Mentors->get($id, [
            'contain' => ['Skills']
        ]);

        $this->set('mentor', $mentor);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $mentor = $this->Mentors->newEntity();
        if ($this->request->is('post')) {
            $mentor = $this->Mentors->patchEntity($mentor, $this->request->getData());
            if ($this->Mentors->save($mentor)) {
                $this->Flash->success(__('The mentor has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The mentor could not be saved. Please, try again.'));
        }
        $skills = $this->Mentors->Skills->find('list', ['limit' => 200]);
        $this->set(compact('mentor', 'skills'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Mentor id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $mentor = $this->Mentors->get($id, [
            'contain' => ['Skills']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $mentor = $this->Mentors->patchEntity($mentor, $this->request->getData());
            if ($this->Mentors->save($mentor)) {
                $this->Flash->success(__('The mentor has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The mentor could not be saved. Please, try again.'));
        }
        $skills = $this->Mentors->Skills->find('list', ['limit' => 200]);
        $this->set(compact('mentor', 'skills'));
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
        $this->request->allowMethod(['post', 'delete']);
        $mentor = $this->Mentors->get($id);
        if ($this->Mentors->delete($mentor)) {
            $this->Flash->success(__('The mentor has been deleted.'));
        } else {
            $this->Flash->error(__('The mentor could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
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
        $this->request->allowMethod('ajax');
   
        $keyword = $this->request->query('keyword');
        $fieldAvai = json_decode($this->request->query('fieldsAvai'));
        $fieldLabel = $this->request->query('fieldsLabel');
        /**
        * On traite en fonction des paramètres reçu.
        * fieldAvai correspond aux deux checkbox pour available.
        * fieldLabel correspond aux checkbox Mentors et Competencies
        * 
        * Il faut exclure les mentors qui ont été "supprimés" car ça n'est pas encore implémenté.
        */

        if($keyword == '' && sizeof($fieldAvai) == 2)
        {
            //si les deux available et unavailable sont cochés et que le text est vide on retourne tout.
            $query = $this->Mentors->find('all')->where(['deleted'=> 'null']);
        }
        elseif($keyword == '' && $fieldAvai[0] == "available")
        {
            //si le text est vide et que available est coché, on retourne les mentors qui n'ont pas de loans en ce moment.
            $query = $this->Mentors->find('all')->matching('Loans', function ($q) {
                return $q->where(['Loans.start_time <=' => Time::now(), 'Loans.end_time >=' => Time::now(),'Loans.deleted'=>'null',]);
           });
           $query->where(['Mentors.deleted'=>'null']);
        }elseif($keyword == '' && $fieldAvai[0] == "unavailable")
        {
            //si unavailable est coché on retourne les mentors qui ont un loans en ce moment. La query n'est pas fonctionnelle, elle ne retourne pas tous les cas. Voir comment les OR fonctionnent.
            $query = $this->Mentors->find('all')->matching('Loans', function ($q) {
                return $q->where(['OR'=>['Loans.start_time >=' => Time::now(), 'Loans.end_time >=' => Time::now()], 'OR' =>['Loans.start_time <=' => Time::now(), 'Loans.end_time <=' => Time::now()]]);
           });
           //trouver le moyen de faire un distinct.
        }
        else
        {
            //si du texte est passé en paramètre on retourne les mentors correspondant idépendament des checkbox
            //faire en fonction des checkboxes par la suite.
            $query = $this->Mentors->find('all')
                ->where(["match (email, first_name, last_name, description) against(:search in boolean mode)"])
                ->bind(":search", $keyword . '*', 'string');
        }
        
        $this->set('mentors', $this->paginate($query));
        $this->set('_serialize', ['mentors']);
    }

}
