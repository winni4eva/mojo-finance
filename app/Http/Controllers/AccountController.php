<?php

namespace App\Http\Controllers;

use App\Filters\AccountFilters;
use App\Http\Requests\StoreAccountRequest;
use App\Http\Resources\AccountResource;
use App\Models\Account;
use App\Traits\HttpResponseTrait;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AccountController extends Controller
{
    use HttpResponseTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(AccountFilters $filters)
    {
        $accounts = Account::where('user_id', Auth::user()->id)
            ->filter($filters)
            ->latest()
            ->paginate(request()->query('perPage', 10), columns: ['*'], pageName: 'page', page: request()->query('page', 1));

        return AccountResource::collection($accounts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \App\Http\Resources\AccountResource
     */
    public function store(StoreAccountRequest $request)
    {
        $request->validated($request->all());

        $account = Account::create([
            'user_id' => Auth::user()->id,
            'amount' => $request->amount,
        ]);

        return new AccountResource($account);
    }

    /**
     * Display the specified resource.
     *
     * @return \App\Http\Resources\AccountResource|\Illuminate\Http\JsonResponse
     */
    public function show(Account $account)
    {
        $this->authorize('view', $account);

        return new AccountResource($account);
    }

    /**
     * Update the specified resource in storage.
     *
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Account $account)
    {
        $account->delete();

        return $this->success(null, 'Account deleted successfully', Response::HTTP_NO_CONTENT);
    }
}
