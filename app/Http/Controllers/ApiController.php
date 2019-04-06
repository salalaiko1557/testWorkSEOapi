<?php

namespace App\Http\Controllers;

use App\Tasks;
use App\RestClient as RestClient;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    /**
    *
    * Create auth object
    * returned user data (obj)
    *
    */
   public static function auth(){
       try {
           $client = new RestClient('https://api.dataforseo.com/', null, 'challenger22@rankactive.info', 'v0B4XUN4f');
           return $client;
       } catch (RestClientException $e) {
            return response($e->getMessage(), $e->getStatus());
            exit();
       }
   }
    /**
    * Acepts user string and Task model
    * Set post query by user string
    * returned api request (json)
    *
    */
   public function set(Request $request, Tasks $tasks){
       if(isset($request->data)){
        $post_array[] = [
            "priority" => 1,
            "site" => "dataforseo.com",
            "se_name" => "google.co.uk",
            "se_language" => "English",
            "loc_name_canonical"=> "London,England,United Kingdom",
            "key" => mb_convert_encoding($request->data, "UTF-8")
           ];
       };
       if (count($post_array) > 0) {
           try {
                $result = Self::auth()->post('v2/rnk_tasks_post', ['data' => $post_array]);
                if($result){
                //get task_id from result json
                      $task_id = null;
                       if(isset($result['results'])){
                           foreach($result['results'] as $upkey => $value){
                               foreach($value as $unkey => $item){
                                   if(isset($value['task_id'])){
                                    $task_id = $value['task_id'];
                                   }
                               }
                           }
                       }
                ///
                    try {
                        //save data to DB
                        $tasks->create([
                            'task_id' => $task_id
                        ]);
                        return response('Data was added', 200);
                    }
                    catch (Exception $e) {
                        return response($e->getMessage(), $e->getStatus());
                        exit();
                    }
                }
            }
            catch (RestClientException $e) {
                return response($e->getMessage(), $e->getStatus());
                exit();
           }
       }
    }
    /**
    *
    * Acepts task_id from front-end
    * Get row from database by task_id
    * returned api request (json)
    *
    */
    public function single(Request $request){
        if($request->data){
            try {
                //get single task from DB
                return Self::auth()->get('v2/rnk_tasks_get/'.$request->data);

            } catch (RestClientException $e) {
                return response($e->getMessage(), $e->getStatus());
                exit();
            }
        }else{
            return response('Failed task ID', 500);
        }
    }
    /**
     *
     * Acepts Task model
     * Get all tasks from database
     * returned array with tasks from database
     *
     */
    public function get(Tasks $tasks){
        try{
            //get all tasks from DB
            return Tasks::all();
        }
        catch (Exception $e) {
            return response($e->getMessage(), $e->getStatus());
        }
    }
}
