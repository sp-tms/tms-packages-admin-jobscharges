<?php

namespace Apps\Tms\Packages\Jobs\Charges;

use Apps\Tms\Packages\Jobs\Charges\Model\AppsTmsJobsCharges;
use Apps\Tms\Packages\Tools\Charges\ToolsCharges;
use Apps\Tms\Packages\Tools\Uom\ToolsUom;
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
        if (isset($data['charge_id_new'])) {
            $charges = $this->addNewCharge($data);
        }

        if (!isset($data['charge_id']) ||
            (isset($data['charge_id']) && $data['charge_id'] === '')
        ) {
            $this->addResponse('Please provide charge', 1);

            return false;
        }

        if (isset($data['quantity_uom_id_new']) || isset($data['rate_uom_id_new'])) {
            $uoms = $this->addNewUom($data);
        }

        if ($this->add($data)) {
            $newCharge = $this->packagesData->last;

            $responseData['newCharge'] = $newCharge;

            if (isset($charges)) {
                $responseData['charges'] = $charges;
            }
            if (isset($uoms)) {
                $responseData['uoms'] = $uoms;
            }

            $this->addResponse('Charge added!', 0, $responseData);

            return true;
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

        if (isset($data['charge_id_new'])) {
            $charges = $this->addNewCharge($data);
        }

        if (isset($data['quantity_uom_id_new']) || isset($data['rate_uom_id_new'])) {
            $uoms = $this->addNewUom($data);
        }

        if ($this->update($data)) {
            $updatedCharge = $this->packagesData->last;

            $responseData['updatedCharge'] = $updatedCharge;

            if (isset($charges)) {
                $responseData['charges'] = $charges;
            }
            if (isset($uoms)) {
                $responseData['uoms'] = $uoms;
            }

            $this->addResponse('Charge updated!', 0, $responseData);

            return true;
        }

        $this->addResponse('Unable to update charge', 1);

        return false;
    }

    protected function addNewCharge(&$data)
    {
        $toolsChargesPackage = $this->usePackage(ToolsCharges::class);

        $newToolsCharge = [];

        $newToolsCharge['type'] = 1;
        if (str_starts_with(strtolower($data['charge_id']), 'charge')) {
            $newToolsCharge['type'] = 2;
        }

        if (str_contains($data['charge_id'], ':')) {
            $chargeIdArr = explode(':', $data['charge_id']);
            $data['charge_id'] = strtoupper(trim($chargeIdArr[1]));
        }

        $newToolsCharge['name'] = strtoupper($data['charge_id']);
        $newToolsCharge['archived'] = 0;
        $newToolsCharge['description'] = strtoupper($data['charge_id']);

        if ($toolsChargesPackage->addCharge($newToolsCharge)) {
            $data['charge_id'] = $toolsChargesPackage->packagesData->last['id'];
        } else {
            $data['charge_id'] = '';
        }

        $charges = $toolsChargesPackage->getAll()->toolscharges;
        if (count($charges) > 0) {
            $charges = msort(array: $charges, key: 'type', preserveKey: true);

            foreach ($charges as &$charge) {
                if ($charge['type'] == 1) {
                    $charge['display_name'] = 'PRODUCT: ' . $charge['name'];
                } else if ($charge['type'] == 2) {
                    $charge['display_name'] = 'CHARGES: ' . $charge['name'];
                }
            }
        }

        return $charges;
    }

    protected function addNewUom(&$data)
    {
        $toolsUomPackage = $this->usePackage(ToolsUom::class);

        if (isset($data['quantity_uom_id_new'])) {
            if ($toolsUomPackage->addUom(['name' => $data['quantity_uom_id'], 'archived' => '0', 'description' => $data['quantity_uom_id']])) {
                $data['quantity_uom_id'] = $toolsUomPackage->packagesData->last['id'];
            } else {
                $data['quantity_uom_id'] = '';
            }
        }
        if (isset($data['rate_uom_id_new'])) {
            if ($toolsUomPackage->addUom(['name' => $data['rate_uom_id'], 'archived' => '0', 'description' => $data['rate_uom_id']])) {
                $data['rate_uom_id'] = $toolsUomPackage->packagesData->last['id'];
            } else {
                $data['rate_uom_id'] = '';
            }
        }

        return $toolsUomPackage->getAll()->toolsuom;
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