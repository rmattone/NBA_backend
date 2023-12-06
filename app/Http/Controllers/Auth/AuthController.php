<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Mail\TestEmail;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;

class AuthController extends Controller
{

    public function register(RegisterUserRequest $request)
    {
        $this->validate($request, [
            'name' => 'required|min:4',
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $token = $user->createToken('LaravelAuthApp')->accessToken;

        return response()->json(['token' => $token], 200);
    }


    public function loginCemiterio(LoginRequest $request)
    {
        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (auth()->attempt($data)) {
            
            app()->log->info('Inicio de sessão', [
                'user' => $data['email'],
                'date' => Carbon::now()->format('Y-m-d h:i:s')
            ]);

            $token = auth()->user()->createToken('LaravelAuthApp')->accessToken;
            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }

    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();
        $response = ['message' => 'You have been successfully logged out!'];
        return response($response, 200);
    }

    public function resetPassword(Request $request)
    {
        try {
            Mail::to('rafaelfmattone@gmail.com', 'Nome do Destinatário')
                        ->send(new TestEmail());
            return $this->success('Email enviado com sucesso');
        } catch (\Throwable $th) {
            dd($th);
            return $this->error($th->getMessage());
        }
    }
}
