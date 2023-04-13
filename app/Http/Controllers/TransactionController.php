<?php

namespace App\Http\Controllers;

use App\Filters\TransactionFilters;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Jobs\ProcessTransaction;
use App\Models\Account;
use App\Models\Transaction;
use App\Service\TransactionService;
use App\Traits\HttpResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{
    use HttpResponseTrait;

    public function __construct(protected TransactionService $transactionService)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(TransactionFilters $filters)
    {
        $transactions = Transaction::where('user_id', Auth::user()->id)
            ->filter($filters)
            ->latest()
            ->paginate(request()->query('perPage', 10), columns: ['*'], pageName: 'page', page: request()->query('page', 1));

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
        $message = '';
        if ($request->has('schedule') && $request->schedule) {
            $this->transactionService->createScheduledTransaction();
            $message = 'Transaction scheduled successfully';
        } else {
            $message = 'Transaction processing initiated successfully';
            ProcessTransaction::dispatch($account, $request->creditAccount(), Auth::user()->id, $request->amount);
        }

        return $this->success('', $message, Response::HTTP_CREATED);
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
