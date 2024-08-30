<?php

namespace App\Controllers;

use App\Models\CarsModel;

class Cars extends BaseController
{


    public function index()
    {
        $model = new CarsModel();
        $data = [
            'cars' => $model->getAllCars(),
        ];

        return view('cars/index', $data);
    }

    public function searchById(int $car = 1)
    {
        $model = new CarsModel();
        $data = ['car' => $model->getCarById($car)];
        return view('cars/car', $data);
    }

    public function deleteById(int $car)
    {
        $model = new CarsModel();
        $success = $model->deleteCarById($car);

        $data = ['success' => $success];

        return view('cars/deleted', $data);
    }
}
