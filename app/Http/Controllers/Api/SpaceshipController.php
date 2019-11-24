<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Response;
use App\Http\Resources\ResSpaceshipCollection;
use App\Http\Resources\ResSpaceship;
use App\Spaceship;
use App\Armament;
use App\AccessToken;

/***********************
 * This controller contains all the REST endpoint related to Spaceship
 * heatbeat: retun database connction status
 * index: return all the spaceship list
 * store: create spaceship
 * show: return a speceship details
 * update: update database record
 * destroy: delete spaceship from database * 
 */
class SpaceshipController extends Controller
{
    /*************************************************
     * Check Heatbeat and Database connetion status **
     ************************************************/
    public function heartBeat() {
        try {      
            $count = AccessToken::where([
                ['id','=',"1"]
                ])->count();
                $response = Response::json(array(
                    "Http Server status"=>"Running",
                    "MySQL Databse Connection"=>'Success'               
                ));
        } catch(QueryException $ex){ 
            $response = Response::json(array(
                "Http Server status"=>"Running",
                "MySQL Databse Connection"=>'Failed'               
            ));
        }

        return $response;
    }

    /*************************************************
     * Return all Spaceship data *********************
     ************************************************/

    public function index(Request $request)
    {
        if($this->isValidUser($request)) {
            $spanceShips = Spaceship::select('id','name','status')->get();       
            return new ResSpaceshipCollection($spanceShips);
        }
        else {
            $response = Response::json(array(
                "status"=>"Unauthorised Access - Invalid username and access token",
                "status_code"=>"401"               
            ));
            return $response;
        }       
    }

    /*************************************************
     * Create new spaceship in database table Spaceships
     * and Armaments
     ************************************************/
    public function store(Request $request)
    {
        if($this->isValidUser($request)) {

            $validatedData = $request->validate([
                'name' => 'required|max:255',
                'class' => 'required',
                'crew' => 'required',
                'image' => 'required',
                'value' => 'required',
                'status' => 'required',
            ]);
          
           $spaceShip = new Spaceship();
           $spaceShip->name = $request->input('name');
           $spaceShip->class = $request->input('class');
           $spaceShip->crew = $request->input('crew');
           $spaceShip->image = $request->input('image');
           $spaceShip->value = $request->input('value');
           $spaceShip->status = $request->input('status');
           
           // save data in spaceship table in database
           $spaceShip->save();
           
          if($spaceShip->id>0) {
                if($request->input('armaments')!=''){
                    $count = 0;
                    foreach($request->input('armaments') as $record) {
                        $armament = new Armament();
                        $armament->spaceship_id = $spaceShip->id;
                        $armament->title = $record['title'];
                        $armament->qty = $record['qty'];
                        //save data in armament table in database
                        $armament->save();                        
                    }   
                    $response = Response::json(array(
                        "success"=>"true",
                        "Spaceship ID"=>$spaceShip->id
                    ));            
                }
                
            }
            else {
                $response = Response::json(array("success"=>"false"));
            }

            //return new ResSpaceship($spaceShip);
        }
        else {
            $response = Response::json(array(
                "status"=>"Unauthorised Access - Invalid username and access token",
                "status_code"=>"401"               
            ));            
        }  
        return $response;
    }

   
    /*************************************************
     * Return Spaceship Details with Armaments**
     * Path parameter $id is Spaceship ID
     ************************************************/
    public function show(Request $request, $id) {        

        if($this->isValidUser($request)) {
            return new ResSpaceship(Spaceship::with(
                    array('armaments'=>function($query){
                         $query->select('spaceship_id','title','qty');
                    })
                )->find($id));
        }
        else {
            $response = Response::json(array(
                "status"=>"Unauthorised Access - Invalid username and access token",
                "status_code"=>"401"               
            ));
            return $response;
        }  
    }

    /*************************************************
     * Update Spaceship details
     * Delete Armament records and insert newly 
     * parameter $id is spaceship ID
     ************************************************/
    public function update(Request $request, $id) {

        if($this->isValidUser($request)) {          
            $spaceShip = Spaceship::find($id);

            if(empty($spaceShip)) {
                $response = Response::json(array(
                    "success"=>"false",
                    "message"=>"Record not found"
                )); 
            }
            else {
                $spaceShip->name = $request->input('name');
                $spaceShip->class = $request->input('class');
                $spaceShip->crew = $request->input('crew');
                $spaceShip->image = $request->input('image');
                $spaceShip->value = $request->input('value');
                $spaceShip->status = $request->input('status');
                $spaceShip->update();
                $spaceShip->armaments()->delete();
           
                if($spaceShip->id>0) {
                    if(!empty($request->input('armaments'))) {                        
                        foreach($request->input('armaments') as $record) {
                            $armament = new Armament();
                            $armament->spaceship_id = $spaceShip->id;
                            $armament->title = $record['title'];
                            $armament->qty = $record['qty'];
                            $armament->save();                        
                        }   
                        $response = Response::json(array("success"=>"true"));            
                    }                 
                }
                else {
                    $response = Response::json(array("success"=>"false"));
                }                
            }           
         }
         else {
             $response = Response::json(array(
                 "status"=>"Unauthorised Access - Invalid username and access token",
                 "status_code"=>"401"               
             ));            
         }  
         return $response;
    }

    /*************************************************
     * Delete Record from Spaceship and Armaments
     * Parameter $id is Spaceship ID
     ************************************************/
    public function destroy(Request $request, $id)
    {
        if($this->isValidUser($request)) {
            $deletedRows = Spaceship::where('id', $id)->delete();
            if($deletedRows>0) {
                $response = Response::json(array("success"=>"true"));
            }
            else {
                $response = Response::json(array("success"=>"false"));
            }
        }
        else {
            $response = Response::json(array(
                "status"=>"Unauthorised Access - Invalid username and access token",
                "status_code"=>"401"               
            ));            
        }  
        return $response;
    }

    /*************************************************
     * Check user details is validity
     * Read username and access_token from request header
     * and validate in database access_token table.
     * return true when record exist in databse for that
     * username and access_token
     ************************************************/
 function isValidUser(Request $request) {
        $username = $request->header('username');
        $access_token = $request->header('access_token');
        $count = AccessToken::where([
            ['username','=',$username],
            ['token','=',$access_token],
            ])->count();
        if($count>0) {
            return true;
        }
        else {
            return false;
        }
    }
}
