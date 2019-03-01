<?php
namespace App\Test\TestCase\Controller;

use App\Controller\MentorsController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;
use Cake\ORM\TableRegistry;

/**
 * App\Controller\MentorsController Test Case
 */
class MentorsControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Mentors',
        'app.Skills',
        'app.MentorsSkills',
        'app.Loans',
        'app.Rooms',
        'app.Services',
        'app.RoomsServices'
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
     * Test delete method
     *
     * @return void
     */
    public function testMentorsReport()
    {
        //Positive test
        $this->get('/reports/mentors_report.json?start_date=2018-10-01&end_date=2018-10-10&sort_field=email&sort_dir=asc');
        $response = json_decode((string)$this->_response->getBody());
        $first = $response[0];
        $this->assertEquals('jam@peanutButter.com', $first->email);
        $this->assertEquals('204', $first->hours_loaned);
        $this->assertEquals('1', $first->times_loaned);
        
        //Negative test
        $this->get('/reports/mentors_report.json?start_date=2100-10-01&end_date=2100-10-10&sort_field=email&sort_dir=asc');
        $response = json_decode((string)$this->_response->getBody());
        $this->assertCount(0, $response);
    }

    public function testRoomsReport()
    {
        //Positive test
        $this->get('/reports/rooms_report.json?start_date=2019-02-01&end_date=2019-03-01&sort_field=name&sort_dir=asc');
        $response = json_decode((string)$this->_response->getBody());
        $first = $response[0];
        $this->assertEquals('1133A', $first[0]);
        $this->assertEquals('28', $first[1]);
        $this->assertEquals('4', $first[2]);
        $this->assertEquals('3', $first[3]);
        $this->assertEquals('3', $first[4]);
        $this->assertEquals('3', $first[5]);
        $this->assertEquals('3', $first[6]);
        $this->assertEquals('3', $first[7]);
        $this->assertEquals('3', $first[8]);
        $this->assertEquals('3', $first[9]);
        $this->assertEquals('3', $first[10]);
        $this->assertEquals('', $first[11]);
        $this->assertEquals('Projector; Lab Linux; Office chairs; Basic tables; Phone; ', $first[12]);

        //Negative test
        $this->get('/reports/rooms_report.json?start_date=2100-10-01&end_date=2100-10-10&sort_field=name&sort_dir=asc');
        $response = json_decode((string)$this->_response->getBody());
        $this->assertCount(0, $response);
    }
}
