<?php
namespace App\Services\Business;

use App\Services\Data\JobDAO;
use App\Services\Utility\ILoggerService;
use App\Models\JobModel;
use App\Services\Utility\DatabaseException;
use PDOException;

/**
 * Contains methods for managing job postings and applications
 *
 *        
 */
class JobService
{

    private $db;
    private $logger;
    
    /**
     * Instantiates the object with a database connection
     *
     * @param ILoggerService $logger
     */
    public function __construct($logger)
    {
        $this->db = DataService::connect();
        $this->logger = $logger;
    }

    /**
     * Get an array of all job postings
     *
     * @return array|boolean|array
     */
    public function getJobs()
    {
        
        $this->logger->info("Enter JobService.getJobs()");
        
        // Perform read operation
        $service = new JobDAO($this->db, $this->logger);
        $result = $service->readAll();
        
        // Verify operation result
        if (! $result)
        {
            $this->logger->warn("Exit JobService.getJobs() with no data");
            return array();
        }
        else
        {
            $this->logger->info("Exit JobService.getJobs()", array(
                $result
            ));
            return $result;
        }
    }

    /**
     * Get a job posting by id
     *
     * @param int $id
     * @return \App\Models\JobModel|boolean|\App\Models\JobModel
     */
    public function getJob($id)
    {
        
        $this->logger->info("Enter JobService.getJob($id)");
        
        // Perform read operation
        $service = new JobDAO($this->db, $this->logger);
        $result = $service->readById($id);
        
        // Verify operation result
        if (! $result)
        {
            $this->logger->warn("Exit JobService.getJob() with no data");
            return new JobModel(null, null, null, null);
        }
        else
        {
            $this->logger->info("Exit JobService.getJob()", ['job'=>$result]);
            return $result;
        }
    }
    
    /**
     * Get an array of all job postings matching keyword criteria
     *
     * @return array|boolean|array
     */
    public function searchJobs($keywords)
    {
        
        $this->logger->info("Enter JobService.searchJobs()");
        
        // Perform read operation
        $service = new JobDAO($this->db, $this->logger);
        $model = new JobModel(null, null, $keywords, $keywords);
        $result = $service->searchByModelPartial($model);
        
        // Verify operation result
        if (! $result)
        {
            $this->logger->warn("Exit JobService.searchJobs() with no data");
            return array();
        }
        else
        {
            $this->logger->info("Exit JobService.gesearchJobstJobs()", array(
                $result
            ));
            return $result;
        }
    }

    /**
     * Create a job posting from a form
     *
     * @param JobModel $job
     * @return boolean
     */
    public function createJob($job)
    {

        $this->logger->info("Enter JobService.createJob()");
        
        // Begin SQL transaction
        $this->db->beginTransaction();

        try
        {

            // Perform create operation
            $service = new JobDAO($this->db, $this->logger);
            $result = $service->create($job);

            // Verify operation result
            if ($result)
            {
                // Commit SQL changes
                $this->logger->info("Exit JobService.createJob() with success.");
                $this->db->commit();
                return true;
            }
            else
            {
                // Rollback SQL changes
                $this->logger->warn("Exit JobService.createJob() with failure: job creation failed.");
                $this->db->rollBack();
                return false;
            }
        }
        catch (PDOException $e)
        {

            // Rollback SQL changes
            $this->db->rollBack();
            
            // Log database exception
            $this->logger->error("Exception: ", array(
                "message" => $e->getMessage()
            ));
            throw new DatabaseException("Database Exception: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Update a job posting from a form
     *
     * @param JobModel $job
     * @return boolean
     */
    public function updateJob($job)
    {
        
        $this->logger->info("Enter JobService.updateJob()");
        
        // Begin SQL transaction
        $this->db->beginTransaction();

        try
        {

            // Perform update operation
            $service = new JobDAO($this->db, $this->logger);
            $result = $service->update($job);

            // Verify operation result
            if ($result)
            {
                // Commit SQL changes
                $this->logger->info("Exit JobService.updateJob() with success.");
                $this->db->commit();
                return true;
            }
            else
            {
                // Rollback SQL changes
                $this->logger->warn("Exit JobService.updateJob() with failure: job update failed.");
                $this->db->rollBack();
                return false;
            }
        }
        catch (PDOException $e)
        {

            // Rollback SQL changes
            $this->db->rollBack();
            
            // Log database exception
            $this->logger->error("Exception: ", array(
                "message" => $e->getMessage()
            ));
            throw new DatabaseException("Database Exception: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Delete a job posting by id
     *
     * @param int $id
     * @return boolean
     */
    public function deleteJob($id)
    {
        
        $this->logger->info("Enter JobService.deleteJob()");
        
        // Begin SQL transaction
        $this->db->beginTransaction();

        try
        {

            // Perform delete operation
            $service = new JobDAO($this->db, $this->logger);
            $result = $service->delete($id);

            // Verify operation result
            if ($result)
            {
                // Commit SQL changes
                $this->logger->info("Exit JobService.deleteJob() with success.");
                $this->db->commit();
                return true;
            }
            else
            {
                // Rollback SQL changes
                $this->logger->warn("Exit JobService.deleteJob() with failure: job deletion failed.");
                $this->db->rollBack();
                return false;
            }
        }
        catch (PDOException $e)
        {

            // Rollback SQL changes
            $this->db->rollBack();
            
            // Log database exception
            $this->logger->error("Exception: ", array(
                "message" => $e->getMessage()
            ));
            throw new DatabaseException("Database Exception: " . $e->getMessage(), 0, $e);
        }
    }

}

