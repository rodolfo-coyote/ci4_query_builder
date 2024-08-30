<?php

namespace App\Models;

use CodeIgniter\Model;

class CarsModel extends Model
{
    protected $table = 'cars';
    protected $pk = 'id';
    protected $allowedFields = ['name', 'model', 'cost', 'stock'];

    protected function getBuilder()
    {
        return $this->db->table($this->table);
    }

    protected function affectedRows()
    {
        return $this->db->affectedRows();
    }
    public function getAllCars()
    {
        $builder = $this->getBuilder();
        $query = $builder->get();
        return $query->getResultObject();
    }

    public function getCarById($carId = 1)
    {
        $builder = $this->getBuilder();
        $query = $builder->getWhere(['id' => $carId]);
        return $query->getResultObject();
    }

    public function deleteCarById($carId)
    {
        $builder = $this->getBuilder();
        $query = $builder->delete(['id' => $carId]);
        $affectedRows = $this->AffectedRows();

        return ['query' => $query, 'affectedRows' => $affectedRows];
    }
}
