<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * LicencesFixture
 *
 */
class LicencesFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'name' => ['type' => 'string', 'length' => 50, 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'key_text' => ['type' => 'string', 'length' => 50, 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'description' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'image' => ['type' => 'text', 'length' => 16777215, 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null],
        'start_time' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'end_time' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'deleted' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'name' => ['type' => 'fulltext', 'columns' => ['name', 'description'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'latin1_swedish_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Init method
     *
     * @return void
     */
    public function init()
    {
        $this->records = [
            [
                'id' => 1,
                'name' => 'Office 365 (Mac)',
                'key_text' => 'LHZVS-V3P00-ZWRFL-0LH8T-67EZO',
                'description' => 'Microsoft Office Suite for Mac.',
                'image' => 'iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAMAAABHPGVmAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAMAUExURQAAAPM7API7AfI8AfI8AvI9A/M+A/M+BPI+BfI/BvNABvJAB/JACPJBCfNCCfJCCvNEDPJEDfNGD/JHEPNIEfNIEvNJE/NLFfNLFvNMFvNOGPNPGvROGfNQHPNRHfNSHvNSH/RQG/RQHPNXJfRUIPRUIfRWI/RYJvRaKfRcK/RdLPRdLfReLvRgMPRhMvRiM/RjNPVkNvVnOfVnOvVoO/VqPfVqPvVrP/VsP/VsQPVtQfVuQ/VvRPVwRvVxR/ZySPZzSfZzSvV1TPV2TfZ5UPZ5UfV6U/Z7VPZ8VfZ9VvZ+V/Z/WPaAWvaBW/aBXPaCXfaFYPaFYfaGYvaHY/eHZPeIZPeJZveLaPeLafeObPiObPeQb/eTc/eUdfeafPiRcPiVdviWdviXePiYefiafPibfficfvicf/idgPifgfmfgvifg/mgg/ighPikiPiligAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAOrMOZgAAAEAdFJOU////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////wBT9wclAAAACXBIWXMAAA7DAAAOwwHHb6hkAAAAGHRFWHRTb2Z0d2FyZQBwYWludC5uZXQgNC4xLjVkR1hSAAACbElEQVRoQ+3Z2VMTMRzAccu22qqAFY963yegAl7F+8SjFgHvWwRFEf3/n2LSfB227GY3yXZ8cPJ5oZv9Jd8ZpryQdeIfCBEnRSKtWmlwis+ZvCNf9pS06NQiS0aekfNlEtq2FuvpfCLTA5wdVxn7xusk58jCMU5N0ZhmaA3HyDjHGUXjy4zGuESebOKkHDvn2PCXdeTrAY6wUmmyrcMy0uz+Ntlgp2ITmRlknxM2K7mRxf1scsV+JSdyKWKLO05QsiJP+5n3wiGKMfL9KMO+OEcxRCb7GM2wZXLpHB/TcJKSFpnN/zYN3e5MTvGYpjOgJSI/8r5N5X2PGPWNXMz+oysfmWFQ84i8qvM+VWX0LXOrPCKbeZ20ceIzM916FqlfWeF9Ui8ifY27vDLoQSRi3SxEYkJEY0QJES1EYkJEY0QJES1EYkJEY0QJES1EYkJEY0QJES1EYkJEY0QJES1EYkJEY0QxRypXWc5QMHL4A4uZikSqd1jJ4x85zqMFz8hAmwcrXpFhPtnyiLzgpz2PiBC/ni3wyY5XRDoTRf0j997xlMM3IsQnrgSqe6+9YcnEPyK1Y5cbGxoXZk3/4y4UkZprbrOi7WdbidvdohEhllNveevDD98z0IuINLeVHQm1E7deC3GZpzQcoWRHpOtVNrliv5IbEeL3iNdlI7sVi4j0cTc7HbBVsYtI9y3vyVE+zT7FOiKN2f7adjxgB1wiQswf4hiz8sQSw6vcIlI74wqv7+BLpro5R6RmhUO71G/yOsknIsTKSU7G+tF53qTyi0jPhwiUdj1mycg7It2olmrNnzxkKRKx9r9EhPgDYzlh8loXLigAAAAASUVORK5CYII=',
                'start_time' => '2018-12-12 12:00:00',
                'end_time' => '2022-12-12 12:00:00',
                'created' => '2019-02-16 17:18:43',
                'modified' => '2019-02-16 17:18:43'
            ],
            [
                'id' => 2,
                'name' => 'Office 365 (Mac)',
                'key_text' => '4ZSVU-5IJ6W-DYEI8-6ADKC-BPPS7',
                'description' => 'Microsoft Office Suite for Mac.',
                'image' => 'iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAMAAABHPGVmAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAMAUExURQAAAPM7API7AfI8AfI8AvI9A/M+A/M+BPI+BfI/BvNABvJAB/JACPJBCfNCCfJCCvNEDPJEDfNGD/JHEPNIEfNIEvNJE/NLFfNLFvNMFvNOGPNPGvROGfNQHPNRHfNSHvNSH/RQG/RQHPNXJfRUIPRUIfRWI/RYJvRaKfRcK/RdLPRdLfReLvRgMPRhMvRiM/RjNPVkNvVnOfVnOvVoO/VqPfVqPvVrP/VsP/VsQPVtQfVuQ/VvRPVwRvVxR/ZySPZzSfZzSvV1TPV2TfZ5UPZ5UfV6U/Z7VPZ8VfZ9VvZ+V/Z/WPaAWvaBW/aBXPaCXfaFYPaFYfaGYvaHY/eHZPeIZPeJZveLaPeLafeObPiObPeQb/eTc/eUdfeafPiRcPiVdviWdviXePiYefiafPibfficfvicf/idgPifgfmfgvifg/mgg/ighPikiPiligAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAOrMOZgAAAEAdFJOU////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////wBT9wclAAAACXBIWXMAAA7DAAAOwwHHb6hkAAAAGHRFWHRTb2Z0d2FyZQBwYWludC5uZXQgNC4xLjVkR1hSAAACbElEQVRoQ+3Z2VMTMRzAccu22qqAFY963yegAl7F+8SjFgHvWwRFEf3/n2LSfB227GY3yXZ8cPJ5oZv9Jd8ZpryQdeIfCBEnRSKtWmlwis+ZvCNf9pS06NQiS0aekfNlEtq2FuvpfCLTA5wdVxn7xusk58jCMU5N0ZhmaA3HyDjHGUXjy4zGuESebOKkHDvn2PCXdeTrAY6wUmmyrcMy0uz+Ntlgp2ITmRlknxM2K7mRxf1scsV+JSdyKWKLO05QsiJP+5n3wiGKMfL9KMO+OEcxRCb7GM2wZXLpHB/TcJKSFpnN/zYN3e5MTvGYpjOgJSI/8r5N5X2PGPWNXMz+oysfmWFQ84i8qvM+VWX0LXOrPCKbeZ20ceIzM916FqlfWeF9Ui8ifY27vDLoQSRi3SxEYkJEY0QJES1EYkJEY0QJES1EYkJEY0QJES1EYkJEY0QJES1EYkJEY0QJES1EYkJEY0QxRypXWc5QMHL4A4uZikSqd1jJ4x85zqMFz8hAmwcrXpFhPtnyiLzgpz2PiBC/ni3wyY5XRDoTRf0j997xlMM3IsQnrgSqe6+9YcnEPyK1Y5cbGxoXZk3/4y4UkZprbrOi7WdbidvdohEhllNveevDD98z0IuINLeVHQm1E7deC3GZpzQcoWRHpOtVNrliv5IbEeL3iNdlI7sVi4j0cTc7HbBVsYtI9y3vyVE+zT7FOiKN2f7adjxgB1wiQswf4hiz8sQSw6vcIlI74wqv7+BLpro5R6RmhUO71G/yOsknIsTKSU7G+tF53qTyi0jPhwiUdj1mycg7It2olmrNnzxkKRKx9r9EhPgDYzlh8loXLigAAAAASUVORK5CYII=',
                'start_time' => '2018-11-12 12:00:00',
                'end_time' => '2022-11-12 12:00:00',
                'created' => '2019-02-16 17:18:43',
                'modified' => '2019-02-16 17:18:43'
            ],
            [
                'id' => 3,
                'name' => 'Office 365 (Mac)',
                'key_text' => 'DVMOT-J3BBW-B0808-TS8TY-9QCPH',
                'description' => 'Microsoft Office Suite for Mac.',
                'image' => 'iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAMAAABHPGVmAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAMAUExURQAAAPM7API7AfI8AfI8AvI9A/M+A/M+BPI+BfI/BvNABvJAB/JACPJBCfNCCfJCCvNEDPJEDfNGD/JHEPNIEfNIEvNJE/NLFfNLFvNMFvNOGPNPGvROGfNQHPNRHfNSHvNSH/RQG/RQHPNXJfRUIPRUIfRWI/RYJvRaKfRcK/RdLPRdLfReLvRgMPRhMvRiM/RjNPVkNvVnOfVnOvVoO/VqPfVqPvVrP/VsP/VsQPVtQfVuQ/VvRPVwRvVxR/ZySPZzSfZzSvV1TPV2TfZ5UPZ5UfV6U/Z7VPZ8VfZ9VvZ+V/Z/WPaAWvaBW/aBXPaCXfaFYPaFYfaGYvaHY/eHZPeIZPeJZveLaPeLafeObPiObPeQb/eTc/eUdfeafPiRcPiVdviWdviXePiYefiafPibfficfvicf/idgPifgfmfgvifg/mgg/ighPikiPiligAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAOrMOZgAAAEAdFJOU////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////wBT9wclAAAACXBIWXMAAA7DAAAOwwHHb6hkAAAAGHRFWHRTb2Z0d2FyZQBwYWludC5uZXQgNC4xLjVkR1hSAAACbElEQVRoQ+3Z2VMTMRzAccu22qqAFY963yegAl7F+8SjFgHvWwRFEf3/n2LSfB227GY3yXZ8cPJ5oZv9Jd8ZpryQdeIfCBEnRSKtWmlwis+ZvCNf9pS06NQiS0aekfNlEtq2FuvpfCLTA5wdVxn7xusk58jCMU5N0ZhmaA3HyDjHGUXjy4zGuESebOKkHDvn2PCXdeTrAY6wUmmyrcMy0uz+Ntlgp2ITmRlknxM2K7mRxf1scsV+JSdyKWKLO05QsiJP+5n3wiGKMfL9KMO+OEcxRCb7GM2wZXLpHB/TcJKSFpnN/zYN3e5MTvGYpjOgJSI/8r5N5X2PGPWNXMz+oysfmWFQ84i8qvM+VWX0LXOrPCKbeZ20ceIzM916FqlfWeF9Ui8ifY27vDLoQSRi3SxEYkJEY0QJES1EYkJEY0QJES1EYkJEY0QJES1EYkJEY0QJES1EYkJEY0QJES1EYkJEY0QxRypXWc5QMHL4A4uZikSqd1jJ4x85zqMFz8hAmwcrXpFhPtnyiLzgpz2PiBC/ni3wyY5XRDoTRf0j997xlMM3IsQnrgSqe6+9YcnEPyK1Y5cbGxoXZk3/4y4UkZprbrOi7WdbidvdohEhllNveevDD98z0IuINLeVHQm1E7deC3GZpzQcoWRHpOtVNrliv5IbEeL3iNdlI7sVi4j0cTc7HbBVsYtI9y3vyVE+zT7FOiKN2f7adjxgB1wiQswf4hiz8sQSw6vcIlI74wqv7+BLpro5R6RmhUO71G/yOsknIsTKSU7G+tF53qTyi0jPhwiUdj1mycg7It2olmrNnzxkKRKx9r9EhPgDYzlh8loXLigAAAAASUVORK5CYII=',
                'start_time' => '2018-10-12 12:00:00',
                'end_time' => '2022-10-12 12:00:00',
                'created' => '2019-02-16 17:18:43',
                'modified' => '2019-02-16 17:18:43'
            ],
            [
                'id' => 4,
                'name' => 'Office 365 (Win 10)',
                'key_text' => 'EARBL-O6CPC-6IT7N-HKTY2-59BDD',
                'description' => 'Microsoft Office Suite for Windows 10.',
                'image' => 'iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAMAAABHPGVmAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAMAUExURQAAAPM7API7AfI8AfI8AvI9A/M+A/M+BPI+BfI/BvNABvJAB/JACPJBCfNCCfJCCvNEDPJEDfNGD/JHEPNIEfNIEvNJE/NLFfNLFvNMFvNOGPNPGvROGfNQHPNRHfNSHvNSH/RQG/RQHPNXJfRUIPRUIfRWI/RYJvRaKfRcK/RdLPRdLfReLvRgMPRhMvRiM/RjNPVkNvVnOfVnOvVoO/VqPfVqPvVrP/VsP/VsQPVtQfVuQ/VvRPVwRvVxR/ZySPZzSfZzSvV1TPV2TfZ5UPZ5UfV6U/Z7VPZ8VfZ9VvZ+V/Z/WPaAWvaBW/aBXPaCXfaFYPaFYfaGYvaHY/eHZPeIZPeJZveLaPeLafeObPiObPeQb/eTc/eUdfeafPiRcPiVdviWdviXePiYefiafPibfficfvicf/idgPifgfmfgvifg/mgg/ighPikiPiligAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAOrMOZgAAAEAdFJOU////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////wBT9wclAAAACXBIWXMAAA7DAAAOwwHHb6hkAAAAGHRFWHRTb2Z0d2FyZQBwYWludC5uZXQgNC4xLjVkR1hSAAACbElEQVRoQ+3Z2VMTMRzAccu22qqAFY963yegAl7F+8SjFgHvWwRFEf3/n2LSfB227GY3yXZ8cPJ5oZv9Jd8ZpryQdeIfCBEnRSKtWmlwis+ZvCNf9pS06NQiS0aekfNlEtq2FuvpfCLTA5wdVxn7xusk58jCMU5N0ZhmaA3HyDjHGUXjy4zGuESebOKkHDvn2PCXdeTrAY6wUmmyrcMy0uz+Ntlgp2ITmRlknxM2K7mRxf1scsV+JSdyKWKLO05QsiJP+5n3wiGKMfL9KMO+OEcxRCb7GM2wZXLpHB/TcJKSFpnN/zYN3e5MTvGYpjOgJSI/8r5N5X2PGPWNXMz+oysfmWFQ84i8qvM+VWX0LXOrPCKbeZ20ceIzM916FqlfWeF9Ui8ifY27vDLoQSRi3SxEYkJEY0QJES1EYkJEY0QJES1EYkJEY0QJES1EYkJEY0QJES1EYkJEY0QJES1EYkJEY0QxRypXWc5QMHL4A4uZikSqd1jJ4x85zqMFz8hAmwcrXpFhPtnyiLzgpz2PiBC/ni3wyY5XRDoTRf0j997xlMM3IsQnrgSqe6+9YcnEPyK1Y5cbGxoXZk3/4y4UkZprbrOi7WdbidvdohEhllNveevDD98z0IuINLeVHQm1E7deC3GZpzQcoWRHpOtVNrliv5IbEeL3iNdlI7sVi4j0cTc7HbBVsYtI9y3vyVE+zT7FOiKN2f7adjxgB1wiQswf4hiz8sQSw6vcIlI74wqv7+BLpro5R6RmhUO71G/yOsknIsTKSU7G+tF53qTyi0jPhwiUdj1mycg7It2olmrNnzxkKRKx9r9EhPgDYzlh8loXLigAAAAASUVORK5CYII=',
                'start_time' => '2018-12-17 12:00:00',
                'end_time' => '2022-12-17 12:00:00',
                'created' => '2019-02-16 17:18:43',
                'modified' => '2019-02-16 17:18:43'
            ],
            [
                'id' => 5,
                'name' => 'Office 365 (Win 10)',
                'key_text' => 'NRIAH-M22OK-2BGT0-3KMVH-1H10E',
                'description' => 'Microsoft Office Suite for Windows 10.',
                'image' => 'iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAMAAABHPGVmAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAMAUExURQAAAPM7API7AfI8AfI8AvI9A/M+A/M+BPI+BfI/BvNABvJAB/JACPJBCfNCCfJCCvNEDPJEDfNGD/JHEPNIEfNIEvNJE/NLFfNLFvNMFvNOGPNPGvROGfNQHPNRHfNSHvNSH/RQG/RQHPNXJfRUIPRUIfRWI/RYJvRaKfRcK/RdLPRdLfReLvRgMPRhMvRiM/RjNPVkNvVnOfVnOvVoO/VqPfVqPvVrP/VsP/VsQPVtQfVuQ/VvRPVwRvVxR/ZySPZzSfZzSvV1TPV2TfZ5UPZ5UfV6U/Z7VPZ8VfZ9VvZ+V/Z/WPaAWvaBW/aBXPaCXfaFYPaFYfaGYvaHY/eHZPeIZPeJZveLaPeLafeObPiObPeQb/eTc/eUdfeafPiRcPiVdviWdviXePiYefiafPibfficfvicf/idgPifgfmfgvifg/mgg/ighPikiPiligAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAOrMOZgAAAEAdFJOU////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////wBT9wclAAAACXBIWXMAAA7DAAAOwwHHb6hkAAAAGHRFWHRTb2Z0d2FyZQBwYWludC5uZXQgNC4xLjVkR1hSAAACbElEQVRoQ+3Z2VMTMRzAccu22qqAFY963yegAl7F+8SjFgHvWwRFEf3/n2LSfB227GY3yXZ8cPJ5oZv9Jd8ZpryQdeIfCBEnRSKtWmlwis+ZvCNf9pS06NQiS0aekfNlEtq2FuvpfCLTA5wdVxn7xusk58jCMU5N0ZhmaA3HyDjHGUXjy4zGuESebOKkHDvn2PCXdeTrAY6wUmmyrcMy0uz+Ntlgp2ITmRlknxM2K7mRxf1scsV+JSdyKWKLO05QsiJP+5n3wiGKMfL9KMO+OEcxRCb7GM2wZXLpHB/TcJKSFpnN/zYN3e5MTvGYpjOgJSI/8r5N5X2PGPWNXMz+oysfmWFQ84i8qvM+VWX0LXOrPCKbeZ20ceIzM916FqlfWeF9Ui8ifY27vDLoQSRi3SxEYkJEY0QJES1EYkJEY0QJES1EYkJEY0QJES1EYkJEY0QJES1EYkJEY0QJES1EYkJEY0QxRypXWc5QMHL4A4uZikSqd1jJ4x85zqMFz8hAmwcrXpFhPtnyiLzgpz2PiBC/ni3wyY5XRDoTRf0j997xlMM3IsQnrgSqe6+9YcnEPyK1Y5cbGxoXZk3/4y4UkZprbrOi7WdbidvdohEhllNveevDD98z0IuINLeVHQm1E7deC3GZpzQcoWRHpOtVNrliv5IbEeL3iNdlI7sVi4j0cTc7HbBVsYtI9y3vyVE+zT7FOiKN2f7adjxgB1wiQswf4hiz8sQSw6vcIlI74wqv7+BLpro5R6RmhUO71G/yOsknIsTKSU7G+tF53qTyi0jPhwiUdj1mycg7It2olmrNnzxkKRKx9r9EhPgDYzlh8loXLigAAAAASUVORK5CYII=',
                'start_time' => '2018-15-12 12:00:00',
                'end_time' => '2022-15-12 12:00:00',
                'created' => '2019-02-16 17:18:43',
                'modified' => '2019-02-16 17:18:43'
            ],
            [
                'id' => 6,
                'name' => 'Balsamiq (Mac)',
                'key_text' => 'eNrzzU/OLi0odswsqnHLSSzOqDGoca',
                'description' => 'Balsamiq Suite for Mac.',
                'image' => 'iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAMAAABHPGVmAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAMAUExURQAAADs7Ozw8PD09PT4+Pj8/P0BAQEFBQUJCQkNDQ0REREVFRUZGRkdHR0hISElJSUpKSktLS0xMTE1NTU5OTk9PT1BQUFFRUVJSUlNTU1RUVFVVVVZWVldXV1hYWFlZWVpaWltbW1xcXF1dXV5eXl9fX2BgYGFhYWJiYmNjY2RkZGVlZWZmZmdnZ2hoaGlpaWpqamtra2xsbG1tbW5ubm9vb3BwcHFxcXJycnNzc3V1dXZ2dnd3d3h4eHl5eXp6ent7e3x8fH19fX5+fn9/f4CAgIGBgYKCgoODg4SEhIWFhYaGhoeHh4iIiImJiYqKiouLi4yMjI2NjY6Ojo+Pj5CQkJGRkZKSkpOTk5SUlJWVlZaWlpeXl5iYmJmZmZqampubm5ycnJ2dnZ6enp+fn6GhoaKioqOjo6SkpKampqioqKqqqqurq6ysrK2tra6urq+vr7CwsLGxsbKysrOzs7S0tLa2tre3t7i4uLm5ubu7u7y8vL29vb6+vr+/v8DAwMHBwcLCwsPDw8TExMXFxcbGxsjIyMnJycrKyszMzM3Nzc/Pz9DQ0NHR0dLS0tPT09TU1NXV1dbW1tfX19jY2NnZ2dra2tvb29zc3N3d3d7e3t/f3+Dg4OHh4eLi4uPj4+Tk5OXl5ebm5ufn5+jo6Onp6erq6uvr6+zs7O3t7e7u7u/v7/Dw8PHx8fLy8vPz8/T09PX19fb29vf39/j4+Pn5+fr6+vv7+/z8/P39/f7+/v///wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAEH3mHsAAAEAdFJOU////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////wBT9wclAAAACXBIWXMAAA7DAAAOwwHHb6hkAAAAGHRFWHRTb2Z0d2FyZQBwYWludC5uZXQgNC4xLjVkR1hSAAAHqElEQVRoQ+2Z53sVRRjFJTs3CTchCVVKAIGEGgSkQ6gaAgkEkGYoEooCkqhIMSQkUqSHJqiAEiIqVemkDH/cdeedM7uzu7O3JY+f+H24z92Zd87ZKTtl973Y/8A7k5RIz6RiQCQPf5MhHZOPGTEYl4lJx6RUmjA2BAmJSNVkU9XSWZOjMGGs3w6kxyUpky3lU4fbwpYFaZ2cNQiKQwKTNSV9IBZOVjmCQ4ln8gFUEpJVgRIhhJrUmKoQMTeZ3WgoZSbMxF+LjIIpy3d923j2wsXG3aUFBqdiFDQRYpKNopJRaxraOjh/C3j7ufLeyHKJoqgBo8l2lBNEZtbec/UV/NH2PAS4hI4zk8kOFLIHbfG+BwED8O/2XEQ5jIGAH5MJirDMspYwB+LRempVyx0ifaDgw2CSiRKLWuNaCO5V5heuvPDmwBAUYZnQ8BI06SfD85sSWri83uv0UA1kdAImM2XsyFaUT5LHyyKyINsGIY2AiYwseojCyXOlWBZlENLwm+RT3LD7KJkK7Z9TWWZBysVnUk5hubdQLjV4o3yGsyDm4DOhkWUdTaHPdfi5HHIZADWF12QOxSxN08PmnKzLVOgBrwmNkPxHKJEOx+Qggx7wmCyggP2ITwu+kzSyoSjxmFCPFL5BfHp0ziaXKZAkdJNqkWsdRnS6PCggGWgSuglNKIPbEZw2h2lJGwpRgW5CmXvSH1qga7LQ0fteM6E9W/ZjhHaDFrpbbYOpmdCDtKTbFbFH2CyhpFVFMxEZ1nEEdouzVJUJ0NVNloqMvNeI6xZdI4SWWxXXpK9I78aMosF3kclGKGsmlH4EYd2kjdprEJR9JtYDRLm0bFl94BX+B+EvjO3LRws1p70cE5oNRgVaq5aSg95EV/247Jwyw6DnNHk4671jQpuoVX6TJ/IoMt/YVbyaWmXUM1xrnKRiasZ3TCj1IGIcGiiZsT9x7eEl9qqbg7fwkOx7Q9sxEakZvyLGgVrLxvj4/JYhMxfgWqPrfZGhZkllsk4k9g70cJNItmnCtYc2mFQGa8Kniwy/yQyRODoQ/VRuQa02XHvooEmdsQZca/BVlLNeiiuTgSJtYfCWGmghKzN3/AaRxyZ24FpHbtrnSXFlQlrViNC5vnLSxJqQxfLZWLuSJcbxLTtztBRXJpRWhwgvwdOJw6vDu0914r+XIyRYIMWVCQ25ZkT0AEdp14LTl25iXUFED9BAJjhJwKRGJPW6i4ge4HshqGYvmKwVKRmpb+VD+YY8IlIdJmUiKfMpInqAPQaT+SKptz5t/30Of9KDZhC1v4fJRyIpqj8O09hV/EsHvpBM8KICJh9Skr6vG8c2hD8fCeFFZDJQqsOkRCRF9eaawfqnsqm4egd/JK/lOjReqsNkkkjKeoEQG76SsX34nwQ3M0s99f5dTtCLpTpMplDaE4QIvrZPdUmfgF+XsOUek0Okx6qlOkxopu91DyGCu/YcUGRYWE10LvOdakQ7CKS4MqF9cMYviBFw0U1TknLpsh/ljJu4ILC78y1aFZR4CjFEvZjOxuqVC6F9hR05sgtXRKvsEt8av5ESv0MM0UmjsG9zooH8kJp6L64kX4kkxkqkuDKR68k2xEhaaCK1lhh3Kgp+gtbUAs+MxOUJhW2FuDKh9cR7buC7KZFFPwt/P3FnEYVYtbiW/EGJ7pHOY+LbQHbhfTnLXn7R0+SKG8uzZMB0z/LI8VbOefupTKhpsrWnUdC+mIJtrKEbTj9/694Df8tv7x2PTFboPfl30KbL2Ua4JnRwCCyNHXJAEFbmuBVf1p+/cevmT2cObisdINtSMNg7pbxtlFnuAViZ0DQcnEh4A/ZWkgizMmxcfcEY3zDnE2R6LqRdk82UPjc4Xu/TehaHypeIVJzGPayAtGsiez7XcBLhl+dSGSNW0Xn/fXWproKwjWMiX0KdRKQH/vOn+d4WUkxuDu66aKawmQVhG8dE+pcH24t49WPVCJ9PZELNbUP088HIh67AMdlKOTmhewnO75/6omJ20dCBg4YUz6zcc+Yf8/3I/bE2fm0cEzmxsNqQqki4jfo1c4ma3Z0bCddkJGUWmrboyfOskFQY83yGck1QFc9MnCr8EynCxkFTopnIE+Cg5yiQBnwfBofvm41mskIGrI7bK3E5hQ7RR5ZAM1FfAhpRJGUuyxe2zNoEQYVuItdgFr2EQilyTU1zS6HnoJvgHTrLu4xiKdGiPjxojzrwmGCAsZwfUu+XBvUZdTa0NLwmzlfdLSm+7uxwPoOVQkrHaxIbhlA2/noqlflrGoqpk7sXn4nqFnv+qwp5NRSks0591fK+2Hbwm8TcL3vRdYk/atnwM85aPxwafgImcrWXROYdT3R86Gie6iwBy6AQIGgSk/tYiZVfeeJZaH06r28b4lgMQ3EDBpPYPBQDWZOrG1vbfU78xbW6skHuOhZVu0UTJhO8xtPJyCkqXbuz7vDRpmP1h/ZvXzVnWBbeQxGZ8T/Jm01i8lzpJxKx94D04yHb3ZeYCTFRZ68k6G/4sOgj1CQWW4CNbjyy5yM4LnFMbIrVAmHC6rMIYYmIb2KzaKC/C4gCwzwYSkITonz68L5Ru1aRzD4DRs6pQmrSJGfSTd6ZpEAs9h8OOJl9PoVRlwAAAABJRU5ErkJggg==',
                'start_time' => '2018-12-18 12:00:00',
                'created' => '2019-02-16 17:18:43',
                'modified' => '2019-02-16 17:18:43'
            ],
            [
                'id' => 7,
                'name' => 'Balsamiq (Mac)',
                'key_text' => '7JKCkpsNLXLy8v1ytJTczVLUotKNFL',
                'description' => 'Balsamiq Suite for Mac.',
                'image' => 'iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAMAAABHPGVmAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAMAUExURQAAADs7Ozw8PD09PT4+Pj8/P0BAQEFBQUJCQkNDQ0REREVFRUZGRkdHR0hISElJSUpKSktLS0xMTE1NTU5OTk9PT1BQUFFRUVJSUlNTU1RUVFVVVVZWVldXV1hYWFlZWVpaWltbW1xcXF1dXV5eXl9fX2BgYGFhYWJiYmNjY2RkZGVlZWZmZmdnZ2hoaGlpaWpqamtra2xsbG1tbW5ubm9vb3BwcHFxcXJycnNzc3V1dXZ2dnd3d3h4eHl5eXp6ent7e3x8fH19fX5+fn9/f4CAgIGBgYKCgoODg4SEhIWFhYaGhoeHh4iIiImJiYqKiouLi4yMjI2NjY6Ojo+Pj5CQkJGRkZKSkpOTk5SUlJWVlZaWlpeXl5iYmJmZmZqampubm5ycnJ2dnZ6enp+fn6GhoaKioqOjo6SkpKampqioqKqqqqurq6ysrK2tra6urq+vr7CwsLGxsbKysrOzs7S0tLa2tre3t7i4uLm5ubu7u7y8vL29vb6+vr+/v8DAwMHBwcLCwsPDw8TExMXFxcbGxsjIyMnJycrKyszMzM3Nzc/Pz9DQ0NHR0dLS0tPT09TU1NXV1dbW1tfX19jY2NnZ2dra2tvb29zc3N3d3d7e3t/f3+Dg4OHh4eLi4uPj4+Tk5OXl5ebm5ufn5+jo6Onp6erq6uvr6+zs7O3t7e7u7u/v7/Dw8PHx8fLy8vPz8/T09PX19fb29vf39/j4+Pn5+fr6+vv7+/z8/P39/f7+/v///wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAEH3mHsAAAEAdFJOU////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////wBT9wclAAAACXBIWXMAAA7DAAAOwwHHb6hkAAAAGHRFWHRTb2Z0d2FyZQBwYWludC5uZXQgNC4xLjVkR1hSAAAHqElEQVRoQ+2Z53sVRRjFJTs3CTchCVVKAIGEGgSkQ6gaAgkEkGYoEooCkqhIMSQkUqSHJqiAEiIqVemkDH/cdeedM7uzu7O3JY+f+H24z92Zd87ZKTtl973Y/8A7k5RIz6RiQCQPf5MhHZOPGTEYl4lJx6RUmjA2BAmJSNVkU9XSWZOjMGGs3w6kxyUpky3lU4fbwpYFaZ2cNQiKQwKTNSV9IBZOVjmCQ4ln8gFUEpJVgRIhhJrUmKoQMTeZ3WgoZSbMxF+LjIIpy3d923j2wsXG3aUFBqdiFDQRYpKNopJRaxraOjh/C3j7ufLeyHKJoqgBo8l2lBNEZtbec/UV/NH2PAS4hI4zk8kOFLIHbfG+BwED8O/2XEQ5jIGAH5MJirDMspYwB+LRempVyx0ifaDgw2CSiRKLWuNaCO5V5heuvPDmwBAUYZnQ8BI06SfD85sSWri83uv0UA1kdAImM2XsyFaUT5LHyyKyINsGIY2AiYwseojCyXOlWBZlENLwm+RT3LD7KJkK7Z9TWWZBysVnUk5hubdQLjV4o3yGsyDm4DOhkWUdTaHPdfi5HHIZADWF12QOxSxN08PmnKzLVOgBrwmNkPxHKJEOx+Qggx7wmCyggP2ITwu+kzSyoSjxmFCPFL5BfHp0ziaXKZAkdJNqkWsdRnS6PCggGWgSuglNKIPbEZw2h2lJGwpRgW5CmXvSH1qga7LQ0fteM6E9W/ZjhHaDFrpbbYOpmdCDtKTbFbFH2CyhpFVFMxEZ1nEEdouzVJUJ0NVNloqMvNeI6xZdI4SWWxXXpK9I78aMosF3kclGKGsmlH4EYd2kjdprEJR9JtYDRLm0bFl94BX+B+EvjO3LRws1p70cE5oNRgVaq5aSg95EV/247Jwyw6DnNHk4671jQpuoVX6TJ/IoMt/YVbyaWmXUM1xrnKRiasZ3TCj1IGIcGiiZsT9x7eEl9qqbg7fwkOx7Q9sxEakZvyLGgVrLxvj4/JYhMxfgWqPrfZGhZkllsk4k9g70cJNItmnCtYc2mFQGa8Kniwy/yQyRODoQ/VRuQa02XHvooEmdsQZca/BVlLNeiiuTgSJtYfCWGmghKzN3/AaRxyZ24FpHbtrnSXFlQlrViNC5vnLSxJqQxfLZWLuSJcbxLTtztBRXJpRWhwgvwdOJw6vDu0914r+XIyRYIMWVCQ25ZkT0AEdp14LTl25iXUFED9BAJjhJwKRGJPW6i4ge4HshqGYvmKwVKRmpb+VD+YY8IlIdJmUiKfMpInqAPQaT+SKptz5t/30Of9KDZhC1v4fJRyIpqj8O09hV/EsHvpBM8KICJh9Skr6vG8c2hD8fCeFFZDJQqsOkRCRF9eaawfqnsqm4egd/JK/lOjReqsNkkkjKeoEQG76SsX34nwQ3M0s99f5dTtCLpTpMplDaE4QIvrZPdUmfgF+XsOUek0Okx6qlOkxopu91DyGCu/YcUGRYWE10LvOdakQ7CKS4MqF9cMYviBFw0U1TknLpsh/ljJu4ILC78y1aFZR4CjFEvZjOxuqVC6F9hR05sgtXRKvsEt8av5ESv0MM0UmjsG9zooH8kJp6L64kX4kkxkqkuDKR68k2xEhaaCK1lhh3Kgp+gtbUAs+MxOUJhW2FuDKh9cR7buC7KZFFPwt/P3FnEYVYtbiW/EGJ7pHOY+LbQHbhfTnLXn7R0+SKG8uzZMB0z/LI8VbOefupTKhpsrWnUdC+mIJtrKEbTj9/694Df8tv7x2PTFboPfl30KbL2Ua4JnRwCCyNHXJAEFbmuBVf1p+/cevmT2cObisdINtSMNg7pbxtlFnuAViZ0DQcnEh4A/ZWkgizMmxcfcEY3zDnE2R6LqRdk82UPjc4Xu/TehaHypeIVJzGPayAtGsiez7XcBLhl+dSGSNW0Xn/fXWproKwjWMiX0KdRKQH/vOn+d4WUkxuDu66aKawmQVhG8dE+pcH24t49WPVCJ9PZELNbUP088HIh67AMdlKOTmhewnO75/6omJ20dCBg4YUz6zcc+Yf8/3I/bE2fm0cEzmxsNqQqki4jfo1c4ma3Z0bCddkJGUWmrboyfOskFQY83yGck1QFc9MnCr8EynCxkFTopnIE+Cg5yiQBnwfBofvm41mskIGrI7bK3E5hQ7RR5ZAM1FfAhpRJGUuyxe2zNoEQYVuItdgFr2EQilyTU1zS6HnoJvgHTrLu4xiKdGiPjxojzrwmGCAsZwfUu+XBvUZdTa0NLwmzlfdLSm+7uxwPoOVQkrHaxIbhlA2/noqlflrGoqpk7sXn4nqFnv+qwp5NRSks0591fK+2Hbwm8TcL3vRdYk/atnwM85aPxwafgImcrWXROYdT3R86Gie6iwBy6AQIGgSk/tYiZVfeeJZaH06r28b4lgMQ3EDBpPYPBQDWZOrG1vbfU78xbW6skHuOhZVu0UTJhO8xtPJyCkqXbuz7vDRpmP1h/ZvXzVnWBbeQxGZ8T/Jm01i8lzpJxKx94D04yHb3ZeYCTFRZ68k6G/4sOgj1CQWW4CNbjyy5yM4LnFMbIrVAmHC6rMIYYmIb2KzaKC/C4gCwzwYSkITonz68L5Ru1aRzD4DRs6pQmrSJGfSTd6ZpEAs9h8OOJl9PoVRlwAAAABJRU5ErkJggg==',
                'start_time' => '2018-12-18 12:00:00',
                'created' => '2019-02-16 17:18:43',
                'modified' => '2019-02-16 17:18:43'
            ]
        ];
        parent::init();
    }

    public function create($db)
    {
        parent::create($db);
        $db->execute("
            drop procedure if exists licences_report;
            create procedure licences_report(start datetime, end datetime)
            begin
                set @query = concat('
                    SELECT
                        products.name AS product,
                        products.platform AS platform,
                        licences.key_text AS licence,
                        CASE WHEN licences.end_time IS NOT NULL AND licences.end_time <= \"', end, '\"
                            THEN 1
                            ELSE 0
                        END AS expired,
                        COUNT(CASE WHEN licences_loans.start_time <= \"', end, '\" AND (licences_loans.returned >= \"', start, '\" OR licences_loans.returned IS NULL)
                                THEN 1
                                ELSE NULL
                            END) AS uses
                    FROM licences_products
                        INNER JOIN licences ON licences_products.licence_id = licences.id
                        INNER JOIN products ON licences_products.product_id = products.id
                        LEFT  JOIN (SELECT * FROM loans WHERE loans.item_type = \"licences\") AS licences_loans
                            ON licences.id = licences_loans.item_id
                    GROUP BY product, platform, licence
                    ORDER BY product, platform'
                );
                
                PREPARE stmt FROM @query;
                EXECUTE stmt;
                DEALLOCATE PREPARE stmt;
            end;", 
            array('log' => false));
        
    }
}
