<?php

namespace Apps\Tms\Packages\Jobs\Charges;

use System\Base\BasePackage;

class JobsCharges extends BasePackage
{
    //protected $modelToUse = ::class;

    protected $packageName = 'jobscharges';

    public $jobscharges;

    public function init()
    {
        //Note: If you want to use init function, you need to run parent::init as well.
        //It is used by the use app database feature of the app.
        //if you remove the init() function from this class, it is also fine.
        parent::init();

        return $this;
    }

    public function getJobsChargesById($id)
    {
        $jobscharges = $this->getById($id);

        if ($jobscharges) {
            //
            $this->addResponse('Success');

            return;
        }

        $this->addResponse('Error', 1);
    }

    public function addJobsCharges($data)
    {
        //
    }

    public function updateJobsCharges($data)
    {
        $jobscharges = $this->getById($id);

        if ($jobscharges) {
            //
            $this->addResponse('Success');

            return;
        }

        $this->addResponse('Error', 1);
    }

    public function removeJobsCharges($data)
    {
        $jobscharges = $this->getById($id);

        if ($jobscharges) {
            //
            $this->addResponse('Success');

            return;
        }

        $this->addResponse('Error', 1);
    }
}