<?php
namespace App\Controller;

use App\Controller\AppController;

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
        $licences = $this->paginate($this->Licences);

        $this->set(compact('licences'));
    }

    /**
     * View method
     *
     * @param string|null $id Licence id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $licence = $this->Licences->get($id, [
            'contain' => ['Products']
        ]);

        $this->set('licence', $licence);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $licence = $this->Licences->newEntity();
        if ($this->request->is('post')) {
            $licence = $this->Licences->patchEntity($licence, $this->request->getData());
            if ($this->Licences->save($licence)) {
                $this->Flash->success(__('The licence has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The licence could not be saved. Please, try again.'));
        }
        $products = $this->Licences->Products->find('list', ['limit' => 200]);
        $this->set(compact('licence', 'products'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Licence id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $licence = $this->Licences->get($id, [
            'contain' => ['Products']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $licence = $this->Licences->patchEntity($licence, $this->request->getData());
            if ($this->Licences->save($licence)) {
                $this->Flash->success(__('The licence has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The licence could not be saved. Please, try again.'));
        }
        $products = $this->Licences->Products->find('list', ['limit' => 200]);
        $this->set(compact('licence', 'products'));
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
        $this->request->allowMethod(['post', 'delete']);
        $licence = $this->Licences->get($id);
        if ($this->Licences->delete($licence)) {
            $this->Flash->success(__('The licence has been deleted.'));
        } else {
            $this->Flash->error(__('The licence could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
