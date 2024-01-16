<?php
 
namespace App\Http\Controllers\Api\Admin;
 
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
 
class UserController extends Controller
{
    public function index()
    {
       $users = User::all(); 
          
       // Return Json Response
       return response()->json([
            'results' => $users
       ],200);
    }
   
    public function store(UserStoreRequest $request)
    {
        try {
            // Create User
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password
            ]);
 
            // Return Json Response
            return response()->json([
                'message' => "User successfully created."
            ],200);
        } catch (\Exception $e) {
            // Return Json Response
            return response()->json([
                'message' => "Something went really wrong!"
            ],500);
        }
    }
   
    public function show($id)
    {
       // User Detail 
       $users = User::find($id);
       if(!$users){
         return response()->json([
            'message'=>'User Not Found.'
         ],404);
       }
       
       // Return Json Response
       return response()->json([
          'users' => $users
       ],200);
    }
   
    public function update(UserStoreRequest $request, $id)
    {
        try {
            // Find User
            $users = User::find($id);
            if(!$users){
              return $users()->json([
                'message'=>'User Not Found.'
              ],404);
            }
       
            //echo "request : $request->image";
            $users->name = $request->name;
            $users->email = $request->email;
       
            // Update User
            $users->save();
       
            // Return Json Response
            return response()->json([
                'message' => "User successfully updated."
            ],200);
        } catch (\Exception $e) {
            // Return Json Response
            return response()->json([
                'message' => "Something went really wrong!"
            ],500);
        }
    }
   
    public function destroy($id)
    {
        // Detail 
        $users = User::find($id);
        if(!$users){
          return response()->json([
             'message'=>'User Not Found.'
          ],404);
        }
         
        // Delete User
        $users->delete();
       
        // Return Json Response
        return response()->json([
            'message' => "User successfully deleted."
        ],200);
    }
}