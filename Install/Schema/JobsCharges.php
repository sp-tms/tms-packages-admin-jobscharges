<?php

namespace Apps\Tms\Packages\Jobs\Charges\Install\Schema;

use Phalcon\Db\Column;
use Phalcon\Db\Index;

class JobsCharges
{
    public function columns()
    {
        return
        [
           'columns' => [
                new Column(
                    'id',
                    [
                        'type'          => Column::TYPE_INTEGER,
                        'notNull'       => true,
                        'autoIncrement' => true,
                        'primary'       => true,
                    ]
                ),
                new Column(
                    'lr_id',
                    [
                        'type'          => Column::TYPE_INTEGER,
                        'notNull'       => true,
                    ]
                ),
                new Column(
                    'charge_id',
                    [
                        'type'          => Column::TYPE_TINYINTEGER,
                        'notNull'       => true,
                    ]
                ),
                new Column(
                    'quantity',
                    [
                        'type'          => Column::TYPE_FLOAT,
                        'notNull'       => false,
                    ]
                ),
                new Column(
                    'quantity_uom_id',
                    [
                        'type'          => Column::TYPE_TINYINTEGER,
                        'notNull'       => false,
                    ]
                ),
                new Column(
                    'rate',
                    [
                        'type'          => Column::TYPE_FLOAT,
                        'notNull'       => false,
                    ]
                ),
                new Column(
                    'rate_uom_id',
                    [
                        'type'          => Column::TYPE_TINYINTEGER,
                        'notNull'       => false,
                    ]
                ),
                new Column(
                    'amount',
                    [
                        'type'          => Column::TYPE_FLOAT,
                        'notNull'       => false,
                    ]
                ),
                new Column(//1 - visible on LR, 2 - visible on Invoice, 3 - Visible on both
                    'visibility',
                    [
                        'type'          => Column::TYPE_TINYINTEGER,
                        'notNull'       => false,
                    ]
                )
            ],
            'indexes' => [
                new Index(
                    'column_UNIQUE',
                    [
                        'charge_id',
                        'lr_no'
                    ],
                    'UNIQUE'
                )
            ],
            'options' => [
                'TABLE_COLLATION' => 'utf8mb4_general_ci'
            ]
        ];
    }

    public function indexes()
    {
        return
        [
            new Index(
                'column_INDEX',
                [
                    'charge_id',
                    'lr_no'
                ],
                'INDEX'
            )
        ];
    }
}
