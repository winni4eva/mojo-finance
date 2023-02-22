<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAccountRequest;
use App\Http\Resources\AccountResource;
use App\Models\Account;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    use HttpResponses;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return AccountResource::collection(Account::where('user_id', Auth::user()->id)->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreAccountRequest  $request
     * @return \App\Http\Resources\AccountResource
     */
    public function store(StoreAccountRequest $request)
    {
        $request->validated($request->all());

        $account = Account::create([
            'user_id' => Auth::user()->id,
            'amount' => $request->amount
        ]);

        return new AccountResource($account);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Account $account
     * @return \App\Http\Resources\AccountResource|\Illuminate\Http\JsonResponse
     */
    public function show(Account $account)
    {
        return new AccountResource($account);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\StoreAccountRequest  $request
     * @param  \App\Models\Account $account
     * @return \App\Http\Resources\AccountResource|\Illuminate\Http\JsonResponse
     */
    public function update(StoreAccountRequest $request, Account $account)
    {
        $request->validated($request->all());

        $account->update($request->all());

        return new AccountResource($account);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Account $account
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Account $account)
    {
        $account->delete();

        return $this->success(null, 'Account deleted successfully', self::NO_RESPONSE_CODE);
    }
}
