<?php
namespace App\Test\TestCase\Controller;

use App\Controller\LoansController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;

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
        'app.MentorsSkills',
        'app.Skills',
        'app.Rooms',
        'app.RoomsServices',
        'app.Services',
        'app.Licences',
        'app.LicencesProducts',
        'app.Products',
        'app.Equipments',
        'app.EquipmentsCategories',
        'app.Categories'
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
        //Positive test
        $loansTable = TableRegistry::get('Loans');
        $expectedLoans = $loansTable->find('all')
            ->where('id IN (10,19)')
            ->toList();

        $this->get('/loans/search.json?keyword=comp&sort_field=item&sort_dir=asc&filters[search_items]=true');

        $receivedLoansDecoded = json_decode((string)$this->_response->getBody());
        $receivedLoans = [];

        foreach($receivedLoansDecoded->loans as $loan){
            array_push($receivedLoans, $loansTable->get($loan->id));
        }

        $this->assertEquals($expectedLoans, $receivedLoans);

        //Negative test
        $loansTable = TableRegistry::get('Loans');

        $this->get('/loans/search.json?keyword=zzzzzzzzzzzzzzzzz&sort_field=item&sort_dir=asc&filters[search_items]=true');

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
        $now = new Time('2019-03-07 20:00:01');
        Time::setTestNow($now);

        //Positive test
        $post = [
            'id' => 21,
            'start_time' => '2019-03-08 12:00:00',
            'end_time' => '2019-03-09 12:00:00',
            'user_id' => 3,
            'item_type' => 'mentors',
            'item_id' => 2
        ];

        $loans = TableRegistry::get('Loans');
        $loan = $loans->find()->where(['id' => 21])->first();
        $this->assertNull($loan);

        $this->post('/loans/add', $post);

        $loans = TableRegistry::get('Loans');
        $loan = $loans->find()->where(['id' => 21])->first();
        $this->assertNotNull($loan);


        //Negative test
        $post = [
            'id' => 22,
            'start_time' => '2019-03-14 12:00:00',
            'end_time' => '2019-03-13 12:00:00',
            'user_id' => 3,
            'item_type' => 'mentors',
            'item_id' => 2
        ];

        $loans = TableRegistry::get('Loans');
        $loan = $loans->find()->where(['id' => 22])->first();
        $this->assertNull($loan);

        $this->post('/loans/add', $post);

        $loans = TableRegistry::get('Loans');
        $loan = $loans->find()->where(['id' => 22])->first();
        $this->assertNull($loan);
    }

    /**
     * Test return method
     *
     * @return void
     */
    public function testReturn()
    {
        $now = new Time('2019-03-07 20:00:01');
        Time::setTestNow($now);

        //Positive test
        $post = [
            'start_time' => '2019-02-23 12:00:00',
            'end_time' => '2019-03-03 12:00:00',
            'returned' => Time::now()
        ];

        $loans = TableRegistry::get('Loans');
        $loan = $loans->find()->where(['id' => 19])->first();
        $this->assertNotNull($loan);
        $this->assertEquals('2019-02-23 12:00:00', $loan->start_time);
        $this->assertEquals('2019-03-03 12:00:00', $loan->end_time);
        $this->assertNull($loan->returned);

        $this->post('/loans/return/19', $post);

        $loans = TableRegistry::get('Loans');
        $loan = $loans->find()->where(['id' => 19])->first();
        $this->assertNotNull($loan);
        $this->assertEquals('2019-02-23 12:00:00', $loan->start_time);
        $this->assertEquals('2019-03-03 12:00:00', $loan->end_time);
        $this->assertNotNull($loan->returned);


        //Negative test
        $post = [
            'start_time' => '2019-03-03 12:00:00',
            'end_time' => '2019-03-06 12:00:00',
            'returned' => null
        ];

        $loans = TableRegistry::get('Loans');
        $loan = $loans->find()->where(['id' => 10])->first();
        $this->assertNotNull($loan);
        $this->assertEquals('2019-03-03 12:00:00', $loan->start_time);
        $this->assertEquals('2019-03-06 12:00:00', $loan->end_time);
        $this->assertNull($loan->returned);

        $this->post('/loans/return/10', $post);

        $success = $this->viewVariable('success');
        $this->assertEquals(false, $success);

        $loans = TableRegistry::get('Loans');
        $loan = $loans->find()->where(['id' => 10])->first();
        $this->assertNotNull($loan);
        $this->assertEquals('2019-03-03 12:00:00', $loan->start_time);
        $this->assertEquals('2019-03-06 12:00:00', $loan->end_time);
        $this->assertNull($loan->returned);
    }
}
