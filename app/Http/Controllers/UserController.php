<?php

namespace App\Http\Controllers;

use App\Http\Validators\UserLoginValidator;
use App\Http\Validators\UserRegisterValidator;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /*private UserRegisterValidator $userRegisterValidator;
    private UserLoginValidator $userLoginValidator;

    public function __construct(UserRegisterValidator $userRegisterValidator, UserLoginValidator $userLoginValidator)
    {
        $this->userRegisterValidator = $userRegisterValidator;
        $this->userLoginValidator = $userLoginValidator;
    }

    public function register1(Request $request): JsonResponse
    {
        $registerData = $request->all();
        if (!$this->userRegisterValidator->validate($registerData)) {
            return response()->json(
                ['error' =>
                    [
                        'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
                        "message" => 'Validation error',
                        "errors" => $this->userRegisterValidator->getMessages()
                    ]
                ]
            );
        }

        $user = new User();
        $user->first_name = $registerData['first_name'];
        $user->last_name = $registerData['last_name'];
        $user->phone = $registerData['phone'];
        $user->document_number = $registerData['document_number'];
        $user->password = $registerData['password'];
        $user->save();

        return response()->json()->setStatusCode(Response::HTTP_NO_CONTENT);
    }

    public function login(Request $request): JsonResponse
    {
        $loginData = $request->all();
        if (!$this->userLoginValidator->validate($loginData)) {
            return response()->json(
                ['error' =>
                    [
                        'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
                        "message" => 'Validation error',
                        "errors" => $this->userLoginValidator->getMessages()
                    ]
                ]
            );
        }

        if ($user = User::where('phone', $loginData['phone'])->where('password', $loginData['password'])->first()) {
            $token = Hash::make(rand(0, 1000000000));
            $user->api_token = $token;
            $user->save();

            return response()->json(['data' => ['token' => $token]])->setStatusCode(Response::HTTP_OK);
        }

        return response()->json(
            [
                'error' =>
                [
                    'code' => Response::HTTP_UNAUTHORIZED,
                    'message' => 'Unauthorized',
                    'errors' => [
                        'phone' => [ "phone or password incorrect" ]
                    ]
                ]
            ]
        );
    }*/

    private UserRegisterValidator $userRegisterValidator;
    private UserLoginValidator $userLoginValidator;

    /**
     * @param UserRegisterValidator $userRegisterValidator
     */
    public function __construct(UserRegisterValidator $userRegisterValidator, UserLoginValidator $userLoginValidator)
    {
        $this->userRegisterValidator = $userRegisterValidator;
        $this->userLoginValidator = $userLoginValidator;
    }

    public function register(Request $request): JsonResponse
    {
        $userData = $request->all();
        if (!$this->userRegisterValidator->validate($userData)) {
            return response()->json(
                ['error' =>
                    [
                        'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
                        "message" => 'Validation error',
                        "errors" => $this->userRegisterValidator->getMessages()
                    ]
                ]
            );
        }

        $user = new User();
        $user->first_name = $userData['first_name'];
        $user->last_name = $userData['last_name'];
        $user->phone = $userData['phone'];
        $user->document_number = $userData['document_number'];
        $user->password = $userData['password'];
        $user->save();

        return response()->json()->setStatusCode(Response::HTTP_NO_CONTENT);
    }

    public function login(Request $request): JsonResponse
    {
        $loginData = $request->all();
        if (!$this->userLoginValidator->validate($loginData)) {
            return response()->json(
                ['error' =>
                    [
                        'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
                        "message" => 'Validation error',
                        "errors" => $this->userLoginValidator->getMessages()
                    ]
                ]
            );
        }

        if ($user = User::where('phone', $loginData['phone'])->where('password', $loginData['password'])->first()) {
            $token = Hash::make(rand(0, 1000000000));
            $user->api_token = $token;
            $user->save();

            return response()->json(['data' => ['token' => $token]])->setStatusCode(Response::HTTP_OK);
        }

        return response()->json(
            [
                'error' =>
                    [
                        'code' => Response::HTTP_UNAUTHORIZED,
                        'message' => 'Unauthorized',
                        'errors' => [
                            'phone' => ["phone or password incorrect"]
                        ]
                    ]
            ]
        );
    }
}
