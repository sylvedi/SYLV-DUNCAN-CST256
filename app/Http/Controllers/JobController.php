<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobModel;
use App\Services\Business\JobService;
use App\Services\Business\SecurityService;
use App\Services\Utility\DatabaseException;
use App\Services\Utility\ILoggerService;

/**
 * Manages tasks relating to job posting and application
 *
 */
class JobController extends Controller
{

    private $logger;
    
    /**
     * Instantiates the object with a database connection
     *
     * @param ILoggerService $logger
     */
    public function __construct(ILoggerService $logger)
    {
        $this->logger = $logger;
    }
    
    /**
     * Display all of the jobs in the database
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function displayJobs(Request $request)
    {
        $this->logger->info("Enter JobController.displayJobs()");

        // Get all jobs (matching criteria if keywords present)
        $service = new JobService($this->logger);
        $keywords = $request->input('keywords');
        if($keywords){
            $this->logger->info("Searching jobs since keywords are present.");
            $jobs = $service->searchJobs($keywords);
        } else {
            $this->logger->info("Retrieving all jobs.");
            $jobs = $service->getJobs();
        }

        // Return jobs view with data
        $this->logger->info("Exit JobController.displayJobs()");
        return view('welcome')->with([
            'jobs' => $jobs,
            'keywords' => ($keywords ? $keywords : "")
        ]);
    }
    
    /**
     * Display a single job
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function displayJob(Request $request)
    {
        $this->logger->info("Enter JobController.displayJob()");
        
        // Get the job by id
        $service = new JobService($this->logger);
        $id = $request->id;
        $job = $service->getJob($id);
        
        // Return job view with data
        $this->logger->info("Exit JobController.displayJob()");
        return view('jobview')->with([
            'job' => $job,
        ]);
    }
    
    /**
     * Emulate applying for a job
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function applyToJob(Request $request)
    {
        $this->logger->info("Enter JobController.applyToJob()");
        
        // Get the job by id
        $service = new JobService($this->logger);
        $id = $request->id;
        $job = $service->getJob($id);
        
        // Return job view with data
        $this->logger->info("Exit JobController.applyToJob()");
        return view('jobview')->with([
            'job' => $job,
            'message' => 'Successfully applied to job.'
        ]);
    }

    /**
     * Display a single job for editing
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function displayJobForEdit(Request $request)
    {
        $this->logger->info("Enter JobController.displayJobForEdit()");

        // Request parameters
        $id = $request->id;

        // Check if user is an administrator
        $sService = new SecurityService($this->logger);
        $isAdmin = $sService->isAdmin($request->session()
            ->get('UserID'));

        // Verify that user is admin
        if ($isAdmin)
        {

            // Get job data
            $jService = new JobService($this->logger);
            $job = $jService->getJob($id);

            // Return edit form with data
            $this->logger->info("Exit JobController.displayJobForEdit() with success.", array(
                "jobId" => $job->getId(),
                "UserID" => $request->session()->get('UserID')
            ));
            return view("addJob")->with([
                'job' => $job,
                'editing' => true
            ]);
        }
        else
        {
            // Return profile page with error
            $this->logger->info("Exit JobController.displayJobForEdit() with failure: no permissions to edit job.", array(
                "jobId" => $id,
                "UserID" => $request->session()->get('UserID')
            ));
            $w = new WelcomeController($this->logger);
            return $w->index($request)->with([
                'message' => "No permissions to edit job."
            ]);
        }
    }

    /**
     * Create a job from a form
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createJob(Request $request)
    {
        $this->logger->info("Enter JobController.createJob()");

        try
        {
            // Validate against model rules
            $request->validate(JobModel::getRules());

            // Request parameters
            $title = $request->input('title');
            $description = $request->input('description');

            // Check if user is an administrator
            $sService = new SecurityService($this->logger);
            $isAdmin = $sService->isAdmin($request->session()
                ->get('UserID'));

            // Verify that user is admin
            if ($isAdmin)
            {

                // Perform job insert operation
                $job = new JobModel(null, null, $title, $description);
                $jService = new JobService($this->logger);
                $result = $jService->createJob($job);

                // Verify operation result
                if ($result)
                {
                    // Return admin view
                    $this->logger->info("Exit JobController.createJob() with success.");
                    $a = new AdministrationController($this->logger);
                    return $a->displayAdminPage($request)->with([
                        'viewJob' => true,
                        'message' => 'Job created.'
                    ]);
                }
                else
                {
                    // Return admin view with error
                    $this->logger->info("Exit JobController.createJob() with failure: there was an error creating the job.", array(
                        "data" => $job
                    ));
                    $a = new AdministrationController($this->logger);
                    return $a->displayAdminPage($request)->with([
                        'viewJob' => true,
                        'message' => 'There was an error creating the job.'
                    ]);
                }
            } else {
                $w = new WelcomeController($this->logger);
                return $w->index($request)->with([
                    'message' => 'No permissions to add job.'
                ]);
            }
        }
        catch (DatabaseException $e)
        {
            // Log database exception
            $this->logger->error("Exception: ", array(
                "message" => $e->getMessage()
            ));
            
            // Return admin view with error
            $a = new AdministrationController($this->logger);
            return $a->displayAdminPage($request)->with([
                'viewJob' => true,
                'message' => 'There was an error creating the job.'
            ]);
        }
    }

    /**
     * Update a job from a form
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateJob(Request $request)
    {
        $this->logger->info("Enter JobController.updateJob()");

        try
        {
            // Validate against model rules
            $request->validate(JobModel::getRules());

            // Request parameters
            $id = $request->input('id');
            $companyid = $request->input('companyid');
            $title = $request->input('title');
            $description = $request->input('description');

            // Check if user is an administrator
            $sService = new SecurityService($this->logger);
            $isAdmin = $sService->isAdmin($request->session()
                ->get('UserID'));

            // Verify that user is an admin
            if ($isAdmin)
            {

                // Perform job update operation
                $job = new JobModel($id, $companyid, $title, $description);
                $jService = new JobService($this->logger);
                $result = $jService->updateJob($job);

                // Verify operation result
                if ($result)
                {
                    // Return admin view
                    $this->logger->info("Exit JobController.updateJob() with success.");
                    $a = new AdministrationController($this->logger);
                    return $a->displayAdminPage($request)->with([
                        'viewJob' => true,
                        'message' => 'Job updated.'
                    ]);
                }
                else
                {
                    // Return admin view with error
                    $this->logger->info("Exit JobController.updateJob() with failure: there was an error creating the job.", array(
                        "data" => $job
                    ));
                    $a = new AdministrationController($this->logger);
                    return $a->displayAdminPage($request)->with([
                        'viewJob' => true,
                        'message' => 'There was an error updating the job.'
                    ]);
                }
            } else {
                $w = new WelcomeController($this->logger);
                return $w->index($request)->with([
                    'message' => 'No permissions to update job.'
                ]);
            }
        }
        catch (DatabaseException $e)
        {
            // Log database exception
            $this->logger->error("Exception: ", array(
                "message" => $e->getMessage()
            ));
            
            // Return admin view with error
            $a = new AdministrationController($this->logger);
            return $a->displayAdminPage($request)->with([
                'viewJob' => true,
                'message' => 'There was an error updating the job.'
            ]);
        }
    }

    /**
     * Delete a job by an id
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteJob(Request $request)
    {
        $this->logger->info("Enter JobController.deleteJob()");

        try
        {
            // Request parameters
            $id = $request->id;

            // Check if user is an administrator
            $sService = new SecurityService($this->logger);
            $isAdmin = $sService->isAdmin($request->session()
                ->get('UserID'));

            // Verify that user is an admin
            if ($isAdmin)
            {

                // Perform job delete operation
                $jService = new JobService($this->logger);
                $result = $jService->deleteJob($id);

                // Verify operation result
                if ($result)
                {
                    // Return admin view
                    $this->logger->info("Exit JobController.deleteJob() with success.");
                    $a = new AdministrationController($this->logger);
                    return $a->displayAdminPage($request)->with([
                        'viewJob' => true,
                        'message' => 'Job deleted.'
                    ]);
                }
                else
                {
                    // Return admin view with error
                    $this->logger->info("Exit JobController.deleteJob() with failure: there was an error deleting the job.", array(
                        "id" => $id
                    ));
                    $a = new AdministrationController($this->logger);
                    return $a->displayAdminPage($request)->with([
                        'viewJob' => true,
                        'message' => 'There was an error deleting the job.'
                    ]);
                }
            } else {
                $w = new WelcomeController($this->logger);
                return $w->index($request)->with([
                    'message' => 'No permissions to delete job.'
                ]);
            }
        }
        catch (DatabaseException $e)
        {
            // Log database exception
            $this->logger->error("Exception: ", array(
                "message" => $e->getMessage()
            ));
            
            // Return admin view with error
            $a = new AdministrationController($this->logger);
            return $a->displayAdminPage($request)->with([
                'viewJob' => true,
                'message' => 'There was an error deleting the job.'
            ]);
        }
    }
    
}
