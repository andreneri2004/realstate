<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\User;
use App\Api\ApiMessages;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function index()
    {
        $user = $this->user->paginate(10);
        return Response()->json(['data'  => $user], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $data = $request->all();

        /**
         * Verifica se existe o campo password existe e se tem valor, caso contrário retorna um erro personalizado.
         */

        if (!$request->has('password')  || !$request->get('password')) {
            $message = new ApiMessages('É necessário informar uma senha para usuário..');
            return response()->json($message->getMessage(), 401);
        }

        Validator::make($data, [
            'phone' => 'required|string|max:15',
            'mobile_phone' => 'required|string|max:15'
        ])->validate();

        try {
            $data['password'] = bcrypt($data['password']);

            $user = $this->user->create($data);
            $user->userProfile()->create(
                [
                    'phone' => $data['phone'],
                    'mobile_phone' => $data['mobile_phone']
                ]
            );
            return Response()->json([
                'success' => 'Usuário salvo com sucesso!',
                'data' => $user
            ], 200);
        } catch (\Throwable $th) {
            $message = new ApiMessages($th->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $user = $this->user->with('userProfile')->findOrFail($id);
            $user->userProfile->social_networks = unserialize($user->userProfile->social_networks);
            return Response()->json(['data' => $user], 200);
        } catch (\Throwable $th) {
            $message = new ApiMessages($th->getMessage());
            return response()->json($message->getMessage(), 401);
        }
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
        $data = $request->all();

        if ($request->has('password') || $request->get('password')) {
            $data['Password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        Validator::make($data, [
            'userProfile.phone' => 'required|string|max:15',
            'userProfile.mobile_phone' => 'required|string|max:15'
        ])->validate();

        try {

            $userProfile = $data['userProfile'];
            $userProfile['social_networks'] = serialize($userProfile['social_networks']);

            $user = $this->user->findOrFail($id);
            $user->update($data);
            $user->userProfile()->update($userProfile);

            return Response()->json([
                'success' => 'Usuário salvo com sucesso!',
                'data' => $user
            ], 200);
        } catch (\Throwable $th) {
            $message = new ApiMessages($th->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {


        try {
            $user = $this->user->findOrFail($id);
            $user->delete();
            return Response()->json([
                'msg' => 'Usuário removido com sucesso!'
            ], 200);
        } catch (\Throwable $th) {
            $message = new ApiMessages($th->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }
}
