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


 
 
class AuthController extends Controller 
{
  public $successStatus = 200;
  public $host = "http://localhost:70/Projects/laravel/laravel_auth";

    
  public function user_detail() 
  { 
 // $user = 'tedt';
     $user = Auth::user();
     return response()->json(['success' => $user], 200); 
  } 


  /* --------------------------------------- */

    public function login() { 
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) { 
            $oClient = OClient::where('password_client', 1)->first();
            return $this->getTokenAndRefreshToken($oClient, request('email'), request('password'));
        } 
        else { 
            return response()->json(['error'=>'Unauthorised'], 401); 
        } 
    }

    public function register(Request $request) { 
        $validator = Validator::make($request->all(), [ 
            'name' => 'required', 
            'email' => 'required|email|unique:users', 
            'password' => 'required', 
        ]);

        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }

        $password = $request->password;
        $input = $request->all(); 
        $input['password'] = bcrypt($input['password']); 
        $user = User::create($input); 
        $oClient = OClient::where('password_client', 1)->first();
        return $this->getTokenAndRefreshToken($oClient, $user->email, $password);
    }

    public function refresh_based_access(Request $request) { 
        $data = $request->validate([
            'refresh_token' => 'required', 
        ]);

        $validator = Validator::make($request->all(), [ 
            'refresh_token' => 'required', 
        ]);

        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
        
        $oClient = OClient::where('password_client', 1)->first();
        return $this->getRefreshTokenbasedAccess($oClient,$data['refresh_token']);
    }

    public function getTokenAndRefreshToken(OClient $oClient, $email, $password) { 
        $oClient = OClient::where('password_client', 1)->first();
        $http = new Client;
        $response = $http->request('POST', $this->host.'/oauth/token', [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => $oClient->id,
                'client_secret' => $oClient->secret,
                'username' => $email,
                'password' => $password,
                'scope' => '*',
            ],
        ]);

        $result = json_decode((string) $response->getBody(), true);
        return response()->json($result, $this->successStatus);
    }

    public function getRefreshTokenbasedAccess(OClient $oClient, $refresh_token) { 
        $oClient = OClient::where('password_client', 1)->first();
        $http = new Client;
        $response = $http->request('POST', $this->host.'/oauth/token', [
            'form_params' => [
                'grant_type' => 'refresh_token',
                'client_id' => $oClient->id,
                'client_secret' => $oClient->secret,
                'refresh_token' => $refresh_token,
                'scope' => '*',
            ],
        ]);

        $result = json_decode((string) $response->getBody(), true);
        return response()->json($result, $this->successStatus);
    }
 
}
?>