<?php
namespace App\Http\Controllers\API;
 
use App\User; 
use Validator;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth; 
use Symfony\Component\HttpFoundation\Response;
use Laravel\Passport\Client as OClient;

use Exception;
use GuzzleHttp\Client;

use App\Corona;

 
 
class CoranaController extends Controller 
{
  public $successStatus = 200;
  public $host = "http://localhost:70/Projects/laravel/laravel_auth_passport-master";



  public function all_detail() 
  { 
     $coronacases = Corona::all();;
     return response()->json(['success' => $coronacases], 200); 
  } 
  /* --------------------------------------- */

  public function add_corana_detail(Request $request) 
  { 
        $validator_data = $request->validate([
            'country_name' => 'required|max:255',
            'symptoms' => 'required',
            'cases' => 'required|numeric',
        ]);

        $validator = Validator::make($request->all(), [ 
            'country_name' => 'required|max:255', 
            'symptoms' => 'required', 
            'cases' => 'required|numeric', 
        ]);

        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }

        $show = Corona::create($validator_data);

        return response()->json(['success' => 'Corona Case is successfully saved'], 200);

  } 


  public function edit_corana_detail(Request $request) 
  { 
        $edit_id = $request->id;

        if(empty($edit_id)){
                return response()->json(['error'=>'Need Proper Id'], 401);
            }

        $validator_data = $request->validate([
            'country_name' => 'required|max:255',
            'symptoms' => 'required',
            'cases' => 'required|numeric',
        ]);

        $validator = Validator::make($request->all(), [ 
            'country_name' => 'required|max:255', 
            'symptoms' => 'required', 
            'cases' => 'required|numeric', 
        ]);

        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }

        $data = Corona::whereId($edit_id)->update($validator_data);

        return response()->json(['success' => 'Corona Case is successfully Updated'], 200);

   }  

   public function delete_corana_detail(Request $request) 
      { 
            $delete_id = $request->id;

            if(empty($delete_id)){
                return response()->json(['error'=>'Need Proper Id'], 401);
            }

            $coronacase = Corona::findOrFail($delete_id);
            $coronacase->delete();

            return response()->json(['success' => 'Corona Case is successfully Deleted'], 200);

       }  

    public function delete_corana_detail_without_query_string(Request $request,$id) 
      { 

            // $delete_id = request('id');

            $delete_id = $id;

            if(empty($delete_id)){
                return response()->json(['error'=>'Need Proper Id'], 401);
            }

            $coronacase = Corona::findOrFail($delete_id);
            $coronacase->delete();

            return response()->json(['success' => 'Corona Case is successfully Deleted'], 200);

       }             

}
?>