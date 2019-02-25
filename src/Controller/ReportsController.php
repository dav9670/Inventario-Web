<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\I18n\Time;

/**
 * Reports Controller
 */
class ReportsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        /*ini_set('memory_limit', '-1');
        $licences = $this->Licences->find('all', [
            'contain' => ['Products']
        ])->where('deleted is null')->order(['name' => 'asc']);
        $archivedLicences = $this->Licences->find('all', [
            'contain' => ['Products']
        ])->where('deleted is not null')->order(['name' => 'asc']);
        $this->set(compact('licences', 'archivedLicences'));
        $this->set('_serialize', ['licences', 'archivedLicences']);*/
    }

    public function isAuthorized($user)
    {
        return $this->Auth->user('admin_status') == 'admin';
    }
}
