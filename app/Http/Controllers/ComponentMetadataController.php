<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Component;
use App\Timelog;
class ComponentMetadataController extends Controller
{
	/**
     * An endpoint at /component-metadata which will return JSON describing how many users worked on the component, 
     * and how many seconds were logged in total.
     *
     * @retrun json
     */
    public function index()
    {
        $data = Component::issues()->get();
        $data = $data->filter(function($val,$key){
             unset($val['iss_id']);
             return $val;
        });
        return response()->json($data)->setEncodingOptions(JSON_PRETTY_PRINT);
    }
}
