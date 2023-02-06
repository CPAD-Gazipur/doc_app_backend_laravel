<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Login
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $request->validate([
                'email'=>'required|email',
                'password'=>'required',
            ]);

        $user = User::where('email',$request->email)->first();

        if(!$user) {
            return response()->json(['success'=>false, 'message' => 'There is no user register using this email.']);
         }
        if(!Hash::check($request->password, $user->password)){
           return response()->json(['success'=>false, 'message' => 'Passsword you entered is incorrect.']);
        }

        return  response()->json(['success'=>true, 'message' => $user->createToken($request->email)->plainTextToken]);
    }

    /**
     * Login
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $request->validate([
                'name'=>'required|string',
                'email'=>'required|email',
                'password'=>'required',
            ]);

            $user = User::where('email',$request->email)->first();

            if($user){
                return response()->json([
                    'success'  => false,
                    'message' => 'The email has already been taken.',
                ],422);
            }

            try{

                $user = User::create([
                    'name'=>$request->name,
                    'email'=>$request->email,
                    'type'=>'user',
                    'password'=>Hash::make($request->password),
                ]);
        
                $userInfo = UserDetails::create([
                    'user_id'=>$user->id,
                    'status'=>'active',
                ]);
        
                return response()->json([        
                    'success'  => true,
                    'message' => 'Account created succefully.',
                    'data' => $user,
                ]);
            }
            catch(Exception $exception){

                return response()->json([
                    'success'  => false,
                    'message' => $exception->getMessage(),
                ]);
            }



    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
