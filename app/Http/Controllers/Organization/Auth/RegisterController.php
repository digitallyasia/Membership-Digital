<?php

namespace App\Http\Controllers\Organization\Auth;

use App\Http\Controllers\Controller;
use App\Organization;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected function redirectTo()
    {
        return route('organization.dashboard');
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:organization');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'logo' => ['required', 'image'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:organizations'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
          
            'phone' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            
            
            'auto_join' => ['required', 'numeric', 'min:0', 'max:1'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        do {
            $refrence_id = mt_rand( 1000000000, 9999999999 );
         } while (Organization::where('referral_code', $refrence_id)->exists());
        return Organization::create([
            'logo' => Storage::disk('images')->put(
                time() . $data['logo']->getClientOriginalExtension(),
                $data['logo']
            ),
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            
            'phone' => $data['phone'],
            'city' => $data['city'],
            'state' => $data['state'],
            'postal_code' => $data['postal_code'],
            'description' => $data['description'],
            'referred_by' => $data['referred_by'],
            'auto_join' => $data['auto_join'],
            'referral_code'=>$refrence_id
        ]);
    }

    public function showRegistrationForm()
    {
        return Inertia::render('Auth/Register');
    }

    public function register(Request $request)
    {
        if(empty($request->referred_by)){
            $this->validator($request->all())->validate();
            $organization = $this->create($request->all());
            $this->guard()->login($organization);
        }
       else if (Organization::where('referral_code', $request->referred_by)->exists()) {
           
            $this->validator($request->all())->validate();
            $organization = $this->create($request->all());
            $this->guard()->login($organization);

        
         }
         else{
           $this->validator($request->all())->validate();
            $message = "Unable to find (referral by) code in our database";
            return response(json_encode($message));

         }
       
    }

    protected function guard()
    {
        return Auth::guard('organization');
    }

    protected function registered(Request $request, $organization)
    {
        return redirect($this->redirectPath());
    }
}
