<?php

namespace App\Http\Controllers;

use App\Filters\TransactionFilters;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Jobs\ProcessTransaction;
use App\Jobs\ScheduleTransaction;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Account $account, TransactionFilters $filters)
    {
        $transactions = Transaction::whereHas('account', function ($query) use ($account) {
            $query->where('user_id', auth()->user()->id)
                ->where('id', $account->id);
        })
            ->filter($filters)
            ->latest()
            ->paginate(
                perPage: request()->query('perPage', config('mojo.perPage')),
                columns: ['*'],
                pageName: 'page',
                page: request()->query('page', 1)
            );

        return TransactionResource::collection($transactions);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\TransactionResource|\Illuminate\Http\JsonResponse
     */
    public function store(StoreTransactionRequest $request, Account $account)
    {
        if (! $request->hasEnoughBalance()) {
            abort(Response::HTTP_FORBIDDEN, 'You do not have sufficient balance to perform this transaction');
        }

        $scheduled = $request->isTransactionScheduled();

        ScheduleTransaction::dispatchIf(
            $scheduled,
            $account, $request->creditAccount(), Auth::user(), $request->amount, $request->period
        );

        ProcessTransaction::dispatchIf(
            ! $scheduled,
            $account, $request->creditAccount(), Auth::user(), $request->amount
        );

        if ($scheduled) {
            return $this->success('', 'Transaction scheduled successfully', Response::HTTP_CREATED);
        }

        return $this->success('', 'Transaction processing initiated successfully', Response::HTTP_CREATED);
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
}
