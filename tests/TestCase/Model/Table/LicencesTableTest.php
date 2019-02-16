<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\LicencesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\LicencesTable Test Case
 */
class LicencesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\LicencesTable
     */
    public $Licences;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Licences',
        'app.Products'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Licences') ? [] : ['className' => LicencesTable::class];
        $this->Licences = TableRegistry::getTableLocator()->get('Licences', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Licences);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
