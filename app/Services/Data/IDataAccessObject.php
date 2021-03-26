<?php
namespace App\Services\Data;

/**
 * Provides standard CRUD methods for implementation in Data Access Objects
 *
 */
interface IDataAccessObject
{

    /**
     * Create a new row in the database using the provided model data.
     * Returns the last insert id.
     *
     * @param mixed $model
     */
    public function create($model);

    /**
     * Read all rows from the database
     */
    public function readAll();

    /**
     * Read a row from the database matching the input id and return an appropriate model
     *
     * @param int $id
     */
    public function readById($id);

    /**
     * Read a row from the database matching the model data and return a filled out model
     *
     * @param mixed $model
     */
    public function readByModel($model);

    /**
     * Update a row in the database with the provided model data
     *
     * @param mixed $model
     */
    public function update($model);

    /**
     * Delete a row in the database matching the input id
     *
     * @param int $id
     */
    public function delete($id);
}

