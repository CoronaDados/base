<?php

namespace App\Http\Controllers\Company\Auth;

use App\Http\Controllers\Controller;
use App\Model\Company\Company;
use App\Model\Company\CompanyUser;
use App\Model\Person\Person;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:company');
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:company_users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'razao' => ['required', 'string', 'min:8'],
            'cnpj' => ['required', 'string', 'min:8',  'unique:companies'],
            'cpf' => ['required', 'string', 'min:11',  'unique:persons'],
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
        $company = Company::create([
            'razao' => $data['razao'],
            'cnpj' => $data['cnpj'],
        ]);
        $person = Person::create([
            'name' => $data['name'],
            'cpf' => $this->removePunctuation($data['cpf']),
        ]);
        $user = CompanyUser::create([
            'company_id' => $company->id,
            'person_id' => $person->id,
            'email' => $data['email'],
            'is_admin' => true,
            'password' => Hash::make($data['password']),
        ]);
        $user->assignRole('Admin');
        return $user;
    }

    protected function guard()
    {
        return auth('company');
    }

    public function showRegistrationForm()
    {
        return view('company.auth.register', ['url' => 'company']);
    }

    private function removePunctuation($string)
    {
        return preg_replace('/[^0-9]/', '', $string);
    }
}
