<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Component extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    /*  
    	on mysql 8+
    	REGEXP_REPLACE(components,'(,|]|\\[)','-')
    */
	/* 
		select id as component_id,
		(select count(components) from issues where replace(replace(replace(components,'[','-'),',','-'),']','-') like concat('%-',component_id,'-%') ) as number_of_issues,
		(select concat('-',GROUP_CONCAT(id SEPARATOR '-'),'-') from issues where components like concat('%',c.id,'%')) as iss_id,
		(select sum(seconds_logged) from timelogs  where iss_id like concat('%-',issue_id,'-%') ) as timelogs
		from components  as c 
	*/

    public function scopeIssues(){
    	return $this->select('id as component_id')
    	->selectSub(function($q){
    		$q->from('issues')
    		->select(DB::raw('count(components)'))
    		->where(DB::raw("replace(replace(replace(components,'[','-'),',','-'),']','-')"), 'LIKE', DB::raw("concat('%-',component_id,'-%')"));
    	},'number_of_issues')
    	->selectSub(function($q){	
    		$q->from('issues')
    		->select(DB::raw("concat('-',GROUP_CONCAT(id SEPARATOR '-'),'-')"))
    		->where(DB::raw("replace(replace(replace(components,'[','-'),',','-'),']','-')"), 'LIKE', DB::raw("concat('%-',component_id,'-%')"));
    	},'iss_id')
    	->selectSub(function($q){	
    		$q->from('timelogs')
    		->select(DB::raw("sum(seconds_logged)"))
    		->where('iss_id', 'LIKE', DB::raw("concat('%-',issue_id,'-%')"));
    	},'timelogs');
	}
}