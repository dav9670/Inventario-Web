<?php
namespace App\Test\TestCase\Controller;

use App\Controller\LoansController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\LoansController Test Case
 */
class LoansControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Loans',
        'app.Users',
        'app.Mentors',
        'app.Rooms',
        'app.Licences',
        'app.Equipments'
    ];

    public function setUp()
    {
        parent::setUp();

        $this->enableCsrfToken();
        $this->enableSecurityToken();

        // Set session data
        $this->session([
            'Auth' => [
                'User' => [
                    'id' => 1,
                    'email' => 'david@gmail.com',
                    'password' => '$2y$10$Cx3OagaMhBWn64l.MiHpRu36oBdp7J3GCg/cjHpKfTY8CVBxWzloy',
                    'admin_status' => 'admin'
                ]
            ]
        ]);
    }

    /**
     * Test search method
     *
     * @return void
     */
    public function testSearch()
    {
        $this->get('/loans/search.json?keyword=comp&sort_field=item&sort_dir=asc&filters[search_items]=true');
        $this->assertResponseOk();
        $user = $this->viewVariable('loans');
        var_dump($user);
        //Positive test
        $loansTable = TableRegistry::get('Loans');
        $expectedLoans = $loansTable->find('all')
            ->where('name like "First aid" or name like "Haskell"')
            ->order(['name' => 'asc'])
            ->toList();

        $this->get('/loans/search.json?keyword=ha&sort_field=name&sort_dir=asc');

        $receivedLoansDecoded = json_decode((string)$this->_response->getBody());
        $receivedLoans = [];

        foreach($receivedLoansDecoded->loans as $loan){
            array_push($receivedLoans, $loansTable->get($loan->id));
        }

        $this->assertEquals($expectedLoans, $receivedLoans);

        //Negative test
        $loansTable = TableRegistry::get('Loans');

        $this->get('/loans/search.json?keyword=zzzzzzzzzzzzzzzzz&sort_field=name&sort_dir=asc');

        $receivedLoansDecoded = json_decode((string)$this->_response->getBody());

        $this->assertCount(0, $receivedLoansDecoded->loans);
    }

    /**
     * Test add method
     *
     * @return void
     */
    public function testAdd()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
