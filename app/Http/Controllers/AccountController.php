<?php

namespace App\Http\Controllers;

use App\Filters\AccountFilters;
use App\Http\Requests\StoreAccountRequest;
use App\Http\Resources\AccountResource;
use App\Models\Account;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(AccountFilters $filters)
    {
        $accounts = Account::where('user_id', auth()->user()->id)
            ->filter($filters)
            ->latest()
            ->paginate(
                perPage: request()->query('perPage', config('mojo.perPage')),
                columns: ['*'],
                pageName: 'page',
                page: request()->query('page', 1)
            );

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
            'user_id' => $request->user()->id,
            'amount' => $request->amount,
            'account_type_id' => $request->account_type,
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
}
