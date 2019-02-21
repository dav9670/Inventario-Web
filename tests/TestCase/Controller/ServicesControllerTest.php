<?php
namespace App\Test\TestCase\Controller;

use App\Controller\ServicesController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;
use Cake\ORM\TableRegistry;

/**
 * App\Controller\ServicesController Test Case
 */
class ServicesControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Services',
        'app.Rooms',
        'app.RoomsServices',
        'app.Loans'
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
     * Test add method
     *
     * @return void
     */
    public function testAdd()
    {
        //Positive test
        $post = [
            'id' => 11,
            'name' => 'Ethernet Cables',
            'description' => 'Cables to connect to the Internet.'
        ];

        $services = TableRegistry::get('Services');
        $service = $services->find()->where(['id' => 11])->first();
        $this->assertNull($service);

        $this->post('/services/add', $post);

        $services = TableRegistry::get('Services');
        $service = $services->find()->where(['id' => 11])->first();
        $this->assertNotNull($service);


        //Negative test
        $post = [
            'id' => 12,
            'name' => 'Phone',
            'description' => 'This is a name that is already taken.'
        ];

        $services = TableRegistry::get('Services');
        $service = $services->find()->where(['id' => 12])->first();
        $this->assertNull($service);

        $this->post('/services/add', $post);

        $services = TableRegistry::get('Services');
        $service = $services->find()->where(['id' => 12])->first();
        $this->assertNull($service);
    }

    /**
     * Test consult method
     *
     * @return void
     */
    public function testConsult()
    {
        //Positive test
        $post = [
            'id' => 1,
            'name' => 'Ethernet Cables',
            'description' => 'Cables to connect to the Internet.'
        ];

        $services = TableRegistry::get('Services');
        $service = $services->find()->where(['id' => 1])->first();
        $this->assertNotNull($service);
        $this->assertEquals('Projector', $service->name);
        $this->assertEquals('BENQ projector, can be opened by pressing a button at the entrance of the room', $service->description);

        $this->post('/services/1', $post);

        $services = TableRegistry::get('Services');
        $service = $services->find()->where(['id' => 1])->first();
        $this->assertNotNull($service);
        $this->assertEquals('Ethernet Cables', $service->name);
        $this->assertEquals('Cables to connect to the Internet.', $service->description);


        //Negative test
        $post = [
            'id' => 2,
            'name' => 'Phone',
            'description' => 'This is a name that is already taken.'
        ];

        $services = TableRegistry::get('Services');
        $service = $services->find()->where(['id' => 2])->first();
        $this->assertNotNull($service);
        $this->assertEquals('Whiteboard', $service->name);
        $this->assertEquals('Pencil and eraser included', $service->description);

        $this->post('/services/2', $post);

        $services = TableRegistry::get('Services');
        $service = $services->find()->where(['id' => 2])->first();
        $this->assertNotNull($service);
        $this->assertEquals('Whiteboard', $service->name);
        $this->assertEquals('Pencil and eraser included', $service->description);
    }

    /**
     * Test delete method
     *
     * @return void
     */
    public function testDelete()
    {
        //Positive test
        $post = [
            'id' => 1
        ];

        $services = TableRegistry::get('Services');
        $service = $services->find()->where(['id' => 1])->first();
        $this->assertNotNull($service);

        $this->post('/services/delete/1', $post);

        $services = TableRegistry::get('Services');
        $service = $services->find()->where(['id' => 1])->first();
        $this->assertNull($service);


        //Negative test
        $post = [
            'id' => -1
        ];

        $services = TableRegistry::get('Services');
        $service = $services->find()->where(['id' => -1])->first();
        $this->assertNull($service);

        $this->post('/services/delete/-1', $post);

        $services = TableRegistry::get('Services');
        $service = $services->find()->where(['id' => -1])->first();
        $this->assertNull($service);

        $this->assertResponseError();
    }
}
