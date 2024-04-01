<?php
namespace App\Http\Controllers;

use App\Models\AqiWorldLocations;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AqiWorldLocationController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function list(Request $request): View
    {

        $data = AqiWorldLocations::where('date', '!=', null)->orderByDesc('date')->orderByDesc('time');

        if ($request->has('date') && $request->date != '') {
            $date = $request->date;
            $data = $data->where('date', 'LIKE', $date.'%');
        }

        if ($request->has('date') && $request->date != '') {
            $date = $request->date;
            $data = $data->where('date', 'LIKE', $date.'%');
        }

        if ($request->has('city_name') && $request->city_name != '') {
            $city_name = $request->city_name;
            $data = $data->where('city_name', 'LIKE', $city_name.'%');
        }

        if ($request->has('state_name') && $request->state_name != '') {
            $state_name = $request->state_name;
            $data = $data->where('state_name', 'LIKE', $state_name.'%');
        }

        if ($request->has('country_name') && $request->country_name != '') {
            $country_name = $request->country_name;
            $data = $data->where('country_name', 'LIKE', $country_name.'%');
        }

        $data = $data->paginate(50);
        $filters = $request->all();

        return view('data.listing', [
            'data' => $data,
            'filters' => $filters,
        ]);
    }
}
