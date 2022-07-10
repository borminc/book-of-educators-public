<?php

namespace App\Http\Controllers;

use App\Models\City;
use Exception;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!request('country_id')) {
            // temp
            throw new Exception('There are A LOT of cities. Provide a country_id.');
        }

        $data = City::query()
            ->when(request('country_id'), fn ($q) => $q->where('country_id', request('country_id')))
            ->get();

        return $data;
    }
}
