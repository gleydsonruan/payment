<?php
namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractRepository
{
    /**      
     * @var Model      
     */     
    protected $model;       

    /**      
     * BaseRepository constructor.      
     *      
     * @param Model $model      
     */     
    public function __construct()     
    {         
        $this->model = $this->resolveModel();
    }
 
    /**
    * @param array $attributes
    * @return Model
    */
    public function create(array $attributes) : Model
    {
        return $this->model->create($attributes);
    }
 
    /**
    * @param $id
    * @return Model
    */
    public function find($id, $fail = true) : ?Model
    {
        return $fail ? $this->model->findOrFail($id) : $this->model->find($id);
    }

    /**
     *
     * @return Collection
     */
    public function all() : ?Collection
    {
        return $this->model->all();
    }

    protected function resolveModel() : Model
    {
        return app($this->model);
    }
}
