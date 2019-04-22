<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Component;

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

		$sql = " SELECT components.id as component_id , issues.id as issue_id, components, timelogs.seconds_logged
					FROM `components` 
					INNER JOIN `issues` 
					ON issues.components LIKE CONCAT('%', components.id, '%')
					INNER JOIN `timelogs` 
					ON issues.id = timelogs.issue_id";

		$components = app('db')->select($sql);

		$components = collect($components)->groupBy('component_id');

		$result = [];

    	foreach ($components as $component) 
    	{
    		$result[] = [
    			'component_id' => $component[0]->component_id,
    			'number_of_issues' => $component->count('issue_id') - 1,
    			'seconds_logged' => $component->sum('seconds_logged')
    		];
    	}

		return response()->json($result)->setEncodingOptions(JSON_PRETTY_PRINT);
    }
}
