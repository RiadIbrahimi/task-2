<?php

namespace App;

use App\Timelog;
use App\Component;
use App\Issue;
use App\User;
use Illuminate\Support\Facades\Hash;

class ImportDataEndpoints
{
	/**
     * The Client from GuzzleHttp.
     *
     * @var string
     */
	protected $client;

	public function __construct()
	{
		$this->client = new \GuzzleHttp\Client();
	}

    public function run()
    {
        $this->import_users();
        $this->import_components();
        $this->import_issues();
        $this->import_timelogs();
    }
	/**
     * Method which import users, insert or update
     *
     * @return mixed
     */
    public function import_users()
    {
		$users_url = 'http://my-json-server.typicode.com/bomoko/algm_assessment/users';
		$response = $this->client->request('GET', $users_url);

        if ($response->getStatusCode() === 200) 
        {
        	$users_list = json_decode($response->getBody());

	        foreach ($users_list as $userData) {

	        	$userObj = User::find($userData->id);
	        	// update user if exist
	        	if ($userObj) {
					$userObj->touch();
	        		$userObj->name = $userData->name;
	        		$userObj->email = $userData->email;
	        		$userObj->update();
	        	} 
	        	// create user if does not exist
	        	else {
	        		User::create([
			            'name' => $userData->name,
			            'email' => $userData->email,
			            'password' => Hash::make('password')
			        ]);
	        	}
	        }
        }
    }
    /**
     * Method which import components, insert or update
     *
     * @return mixed
     */
    public function import_components()
    {
    	$components_url = 'http://my-json-server.typicode.com/bomoko/algm_assessment/components';
		$response = $this->client->request('GET', $components_url);

		if ($response->getStatusCode() === 200) 
        {
        	$components_list = json_decode($response->getBody());

	        foreach ($components_list as $componentData) {

	        	$componentObj = Component::find($componentData->id);
	        	// update component if exist
	        	if ($componentObj) {
					$componentObj->touch();
	        		$componentObj->name = $componentData->name;
	        		$componentObj->update();
	        	} 
	        	// create component if does not exist
	        	else {
	        		Component::create([
			            'name' => $componentData->name
			        ]);
	        	}
	        }
        }
    }
    /**
     * Method which import issues, insert or update
     *
     * @return mixed
     */
    public function import_issues()
    {
    	$issues_url = 'http://my-json-server.typicode.com/bomoko/algm_assessment/issues';
		$response = $this->client->request('GET', $issues_url);

		if ($response->getStatusCode() === 200) 
        {
        	$issues_list = json_decode($response->getBody());

	        foreach ($issues_list as $issueData) {

	        	$issueObj = Issue::find($issueData->id);
	        	// update issue if exist
	        	if ($issueObj) {
					$issueObj->touch();
	        		$issueObj->code = $issueData->code;
	        		$issueObj->components = json_encode($issueData->components);
	        		$issueObj->update();
	        	} 
	        	// create issue if does not exist
	        	else {
	        		Issue::create([
			            'code' => $issueData->code,
			            'components' => json_encode($issueData->components)
			        ]);
	        	}
	        }
        }
    }
    /**
     * Method which import timelogs, insert or update
     *
     * @return mixed
     */
    public function import_timelogs()
    {
    	$timelogs_url = 'http://my-json-server.typicode.com/bomoko/algm_assessment/timelogs';
		$response = $this->client->request('GET', $timelogs_url);

		if ($response->getStatusCode() === 200) 
        {
        	$timelogs_list = json_decode($response->getBody());

	        foreach ($timelogs_list as $timelogData) {

	        	$timelogObj = Timelog::find($timelogData->id);
	        	// update timelog if exist
	        	if ($timelogObj) {
					$timelogObj->touch();
	        		$timelogObj->issue_id = $timelogData->issue_id;
	        		$timelogObj->user_id = $timelogData->user_id;
	        		$timelogObj->seconds_logged = $timelogData->seconds_logged;
	        		$timelogObj->update();
	        	} 
	        	// create timelog if does not exist
	        	else {
	        		Timelog::create([
			            'issue_id' => $timelogData->issue_id,
			            'user_id' => $timelogData->user_id,
			            'seconds_logged' => $timelogData->seconds_logged
			        ]);
	        	}
	        }
        }
    }
}
