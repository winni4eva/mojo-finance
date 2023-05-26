<?php

namespace App\Http\Controllers;

use App\Filters\TransactionFilters;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Jobs\ProcessTransaction;
use App\Jobs\ScheduleTransaction;
use App\Models\Account;
use App\Models\Transaction;
use App\Traits\HttpResponseTrait;
use Facades\App\Service\TransactionService;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{
    use HttpResponseTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Account $account, TransactionFilters $filters)
    {
        $perPage = request()->query('perPage', config('mojo.perPage'));
        $columns = ['*'];
        $pageName = 'page';
        $page = request()->query('page', 1);

        $transactions = Transaction::whereHas('account', function ($query) use ($account) {
            $query->where('user_id', auth()->user()->id)
                ->where('id', $account->id);
        })
            ->filter($filters)
            ->latest()
            ->paginate($perPage, $columns, $pageName, $page);

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
        $scheduled = $request->has('schedule') && $request->schedule ? true : false;

        ScheduleTransaction::dispatchIf(
            $scheduled, 
            $account, $request->creditAccount(), Auth::user(), $request->amount, $request->period
        );

        ProcessTransaction::dispatchIf(
            !$scheduled, 
            $request->creditAccount(), Auth::user(), $request->amount
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
