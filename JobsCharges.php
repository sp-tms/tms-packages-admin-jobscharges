<?php

namespace Apps\Tms\Packages\Jobs\Charges;

use Apps\Tms\Packages\Jobs\Charges\Model\AppsTmsJobsCharges;
use System\Base\BasePackage;

class JobsCharges extends BasePackage
{
    protected $modelToUse = AppsTmsJobsCharges::class;

    protected $packageName = 'jobscharges';

    public $jobscharges;

    public function init()
    {
        parent::init();

        return $this;
    }

    public function getJobsChargesByLrnoAndChargesId($lrno, $chargeId)
    {
        if ($this->config->databasetype === 'db') {
            $params =
                [
                    'conditions'    => 'lr_no = :lr_no: AND charge_id = :charge_id:',
                    'bind'          =>
                        [
                            'lr_no'          => $lrno,
                            'charge_id'      => $chargeId,
                        ]
                ];
        } else {
            $params = ['conditions' => [['lr_no', '=', $lrno], ['charge_id', '=', $chargeId]]];
        }

        $chargeArr = $this->getByParams($params);

        if ($chargeArr && count($chargeArr) > 0) {
            return $chargeArr[0];
        }

        return false;
    }

    public function getJobsChargesById($id)
    {
        //
    }

    public function addJobsCharge($data)
    {
        if ($this->add($data)) {
            $newCharge = $this->packagesData->last;

            $this->addResponse('Charge added!', 0, ['newCharge' => $newCharge]);

            return false;
        }

        $this->addResponse('Unable to add charge', 1);

        return false;
    }

    public function updateJobsCharge($data)
    {
        $charge = $this->getById((int) $data['id']);

        if (!$charge) {
            $this->addResponse('Charge with ID not found!', 1);

            return false;
        }

        if ($this->update($data)) {
            $updatedCharge = $this->packagesData->last;

            $this->addResponse('Charge updated!', 0, ['updatedCharge' => $updatedCharge]);

            return false;
        }

        $this->addResponse('Unable to update charge', 1);

        return false;
    }

    public function removeJobsCharge($data)
    {
        $charge = $this->getById((int) $data['id']);

        if (!$charge) {
            $this->addResponse('Charge with ID not found!', 1);

            return false;
        }

        if ($this->remove((int) $data['id'])) {
            $this->addResponse('Charge removed!');

            return false;
        }

        $this->addResponse('Unable to remove charge', 1);

        return false;
    }
}