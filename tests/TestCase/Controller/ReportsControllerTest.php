<?php
namespace App\Test\TestCase\Controller;

use App\Controller\MentorsController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;
use Cake\ORM\TableRegistry;

/**
 * App\Controller\MentorsController Test Case
 */
class ReportControllerTest extends TestCase
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
        'app.Licences',
        'app.Products',
        'app.LicencesProducts',
        'app.Loans',
        'app.Rooms',
        'app.Services',
        'app.RoomsServices',
        'app.Equipments',
        'app.Categories',
        'app.EquipmentsCategories'
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
     * Test mentors report method
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

    /**
     * Test equipments report method
     *
     * @return void
     */
    public function testEquipmentsReport()
    {
        //Positive test
        $this->get('/reports/equipments_report.json?start_date=2018-10-01&end_date=2018-10-10&sort_field=cat&sort_dir=asc');
        $response = (array) json_decode((string)$this->_response->getBody());
        $first = $response[0];
        

        $this->assertEquals('4K', $first->cat);
        $this->assertEquals('0', $first->time_loans);
        $this->assertEquals('0', $first->hour_loans);
        $this->assertEquals('0', $first->late_loans);
        $this->assertEquals('1', $first->available);
        
        //Negative test
        //can't fo negative test there is always a value.
    }
     * Test licences report method
     *
     * @return void
     */
    public function testLicencesReport()
    {
        //Positive test
        $this->get('/reports/licences_report.json?start_date=2018-10-01&end_date=2019-01-01');
        $response = json_decode((string)$this->_response->getBody());
        
        $first = $response[0];
        $this->assertEquals('Mockups', $first->product);
        $this->assertEquals('Mac', $first->platform);
        $this->assertEquals(0, $first->used);
        $this->assertEquals(0, $first->expired);
        $this->assertEquals(0, $first->uses);
        $this->assertEquals("0%", $first->percent_used);
    }

    /**
     * Test rooms report method
     *
     * @return void
     */
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
