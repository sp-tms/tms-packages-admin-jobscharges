<?php

namespace Apps\Tms\Packages\Jobs\Charges\Model;

use System\Base\BaseModel;

class AppsTmsJobsCharges extends BaseModel
{
    public $id;

    public $lr_no;

    public $charge_id;

    public $quantity;

    public $quantity_uom_id;

    public $rate;

    public $rate_uom_id;

    public $amount;
}