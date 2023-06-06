<?php

namespace DTApi\Http\Controllers;

use DTApi\Models\Job;
use DTApi\Http\Requests;
use DTApi\Models\Distance;
use Illuminate\Http\Request;
use DTApi\Repository\BookingRepository;

/**
 * Class BookingController
 * @package DTApi\Http\Controllers
 */
class BookingController extends Controller
{

    /**
     * @var BookingRepository
     */
    protected $repository;

    /**
     * BookingController constructor.
     * @param BookingRepository $bookingRepository
     */
    public function __construct(BookingRepository $bookingRepository)
    {
        $this->repository = $bookingRepository;
    }

    /**
     * @param Request $request
     * @return mixed
     */


    //  Refactor suggestions by Saad

    public function index(RequestValidator $request)
    {
        if ($user_id = $request->get('user_id')) {

            $response = $this->repository->getUsersJobs($user_id);
        }
        // It is always better to use config rather than direct env parameters

        elseif ($request->__authenticatedUser->user_type == config('USER.ADMIN_ROLE_ID') || $request->__authenticatedUser->user_type == config('USER.SUPERADMIN_ROLE_ID')) {
            $response = $this->repository->getAll($request);
        } else {
            $response = 'Not matched to any criteria';
        }

        return response($response);
    }

    // End Refactor suggestions   

    /**
     * @param $id
     * @return mixed
     */

    //  Refactore Code here

    public function show($id)
    {
        $job = $this->repository->with('translatorJobRel.user')->find($id);
        return response($job);
    }

    // End here
    /**
     * @param Request $request
     * @return mixed
     */

    //  Refactor code here
    public function store(StoreRequestValidator $request)
    {

        $response = $this->repository->store($request->all());
        return response($response);
    }
    // End here



    /**
     * @param $id
     * @param Request $request
     * @return mixed
     */


    //  Refactore code here
    public function update($id, UpdateRequestValidator $request)
    {

        // Skinny Controller code is always better
        // Replace array_except() Function: Instead of using array_except(), consider using the Arr::except() helper function provided by Laravel. This provides a cleaner and more readable way to exclude specific elements from an array.
        $response = $this->repository->updateJob($id, Arr::except($request->all(), ['_token', 'submit']), $request->__authenticatedUser);
        return response($response);
    }
    // End refactor code here



    /**
     * @param Request $request
     * @return mixed
     */

    //  Refactor here
    public function immediateJobEmail(EmailRequestValidator $request)
    {

        $response = $this->repository->storeJobEmail($request->all());
        return response($response);
    }
    // End here


    /**
     * @param Request $request
     * @return mixed
     */


    //  Refactor code here

    public function getHistory(HistoryRequestValidator $request)
    {
        // Validation request will do eveything for you
        // There is no need to send a request parameter as well as Whole request parameter at the same time. Just Send request all data
        $response = $this->repository->getUsersJobsHistory($request->all());
        return response($response);
    }

    // End refactor code 

    /**
     * @param Request $request
     * @return mixed
     */


    //  Refactor code here
    public function acceptJob(RequestValidator $request)
    {
        $response = $this->repository->acceptJob($request->all());
        return response($response);
    }
    // End here




    // Refactor code here

    public function acceptJobWithId(RequestValidator $request)
    {
        $response = $this->repository->acceptJobWithId($request->get('job_id'), $request->__authenticatedUser);
        return response($response);
    }


    // End here



    /**
     * @param Request $request
     * @return mixed
     */

    //  Refactor code here

    public function cancelJob(CancelRequestValidator $request)
    {

        $response = $this->repository->cancelJobAjax($request->all());
        return response($response);
    }

    // End here

    /**
     * @param Request $request
     * @return mixed
     */

    //  Refactor code here

    public function endJob(RequestValidator $request)
    {
        $response = $this->repository->endJob($request->all());
        return response($response);
    }

    //  End here







    // Refactor code here
    public function customerNotCall(RequestValidator $request)
    {
        $response = $this->repository->customerNotCall($request->all());
        return response($response);
    }
    // End here


    /**
     * @param Request $request
     * @return mixed
     */

    //  Refactor code here

    public function getPotentialJobs(RequestVAidation $request)
    {
        $response = $this->repository->getPotentialJobs($request->__authenticatedUser);
        return response($response);
    }

    // End here



    // Refactore code here

    public function distanceFeed(DistanceRequestValidator $request)
    {
        $data = $request->all();

        $distance = isset($data['distance']) ? $data['distance'] : "";
        $time = isset($data['time']) ? $data['time'] : "";
        $jobid = isset($data['jobid']) ? $data['jobid'] : "";
        $session = isset($data['session_time']) ? $data['session_time'] : "";
        $flagged = $data['flagged'] ? "yes" : "no";
        if ($data['flagged']) {
            if (!$data['admincomment']) return "Please, add comment";
        }
        $manually_handled = $data['manually_handled'] ? "yes" : "no";
        $by_admin = $data['by_admin'] ? "yes" : "no";
        $admincomment = isset($data['admincomment']) && $data['admincomment'] ? $data['admincomment'] : "";
        if ($time || $distance) {
            Distance::where('job_id', '=', $jobid)->update(array('distance' => $distance, 'time' => $time));
        }
        if ($admincomment || $session || $flagged || $manually_handled || $by_admin) {
            Job::where('id', '=', $jobid)->update(array('admin_comments' => $admincomment, 'flagged' => $flagged, 'session_time' => $session, 'manually_handled' => $manually_handled, 'by_admin' => $by_admin));
        }
        return response('Record updated!');
    }

    // End here



    // Refactor here
    public function reopen(RequestValidate $request)
    {
        $response = $this->repository->reopen($request->all());
        return response($response);
    }
    // End here




    // Refactor here
    public function resendNotifications(RequestValidation $request)
    {
        $job = $this->repository->findorfail($request['jobid']);
        $job_data = $this->repository->jobToData($job);
        $this->repository->sendNotificationTranslator($job, $job_data, '*');
        return response(['success' => 'Push sent']);
    }

    // End here



    /**
     * Sends SMS to Translator
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */


    //  Refactor here

    public function resendSMSNotifications(RequeRequestValidationst $request)
    {
        $job = $this->repository->find($request['jobid']);
        try {
            $this->repository->sendSMSNotificationToTranslator($job);
            return response(['success' => 'SMS sent']);
        } catch (\Exception $e) {
            return response(['Error' => $e->getMessage()]);
        }
    }

    // End here

}
