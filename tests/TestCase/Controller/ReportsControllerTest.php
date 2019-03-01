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
        'app.Licences',
        'app.Products',
        'app.LicencesProducts',
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
     * Test metors report method
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
}
