<?php

namespace App\Http\Controllers;

use Exception;
use PDOException;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{

    protected string $notFound = "transaction not found";
    protected $channel;

    public function __construct()
    {
        $this->channel = Log::build(['driver' => 'single', 'path' => storage_path('logs/notifications.log')]);
    }

    public function index()
    {
        try {
            return response(['status' => 200, 'message' => 'success', 'data' => Transaction::where('user_id', auth()->id())->paginate(15)], 200);
        } catch(PDOException $e) {
            return response(['status' => 404, 'message' => 'failed', 'error' => $e->getMessage()]);
        } catch(Exception $e) {
            return response(['status' => 404, 'message' => 'failed', 'error' => $e->getMessage()]);
        }
    }

    public function create()
    {
        try {
            
            $validateData = request()->validate([
                'title' => 'string|required',
                'amount' => 'integer',
                'category' => 'string',
                'type' => 'in:income,expense',
            ]);
            
            $validateData['user_id'] = auth()->id();

            $transaction = Transaction::create($validateData);

            Log::stack(['stack' => $this->channel])->info(
                "You have a new '". $validateData['type'] 
                ."' transaction with category '"
                . $validateData['category'] 
                . "' and the amount is : " . $validateData['amount'], 
                ['user' => auth()->id() ]
            );

            return response(['status' => 200, 'message' => 'success', 'data' => $transaction], 200);

        } catch(Exception $e) {
            return response(['status' => 404, 'message' => 'failed', 'error' => $e->getMessage()], 404);
        }
    }

    public function show(int $id)
    {
        try {
            $transaction = Transaction::find($id);
            
            if($transaction && $transaction->user_id == auth()->id()) {
                $transaction->category->name;
                $transaction->user = $transaction->user->name;
                return response(['status' => 200, 'message' => 'success', 'data' =>$transaction], 200);
            } else {
                return response(['status' => 404, 'message' => 'failed', 'error' => $this->notFound], 404);
            }
        } catch (PDOException $e) {
            return response(['status' => 404, 'message' => 'failed', 'error' => $e->getMessage()], 404);
        } catch (Exception $e) {
            return response(['status' => 404, 'message' => 'failed', 'error' => $e->getMessage()], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {

            $validateData = request()->validate([
                'title' => 'string',
                'amount' => 'integer',
                'category' => 'string',
                'type' => 'in:income,expense',
            ]);
            
            $validateData['user_id'] = auth()->id();

            $transaction = Transaction::find($id);

            if($transaction && $transaction->user_id == auth()->id()) {
                $transaction->update($validateData);
                return response(['status' => 200, 'message' => 'success', 'data' => $transaction], 200);
            } else {
                return response(['status' => 404, 'message' => 'success', 'error' => $this->notFound], 404);
            }
        } catch (PDOException $e) {
            return response(['status' => 404, 'message' => 'failed', 'error' => $e->getMessage()], 404);
        } catch (Exception $e) {
            return response(['status' => 404, 'message' => 'failed', 'error' => $e->getMessage()], 404);
        }
    }

    public function delete($id)
    {
        try {

            $transaction = Transaction::find($id);
            
            if($transaction && $transaction->user_id == auth()->id()) {
                $transaction->delete();
                return response(['status' => 200, 'message' => 'success'], 200);
            } else {
                return response(['status' => 404, 'message' => 'success', 'error' => $this->notFound], 404);
            }
        } catch (PDOException $e) {
            return response(['status' => 404, 'message' => 'failed', 'error' => $e->getMessage()], 404);
        } catch (Exception $e) {
            return response(['status' => 404, 'message' => 'failed', 'error' => $e->getMessage()], 404);
        }
    }

    public function transactionsByType(String $type) {

        try {
            if(!in_array($type, ['expense', 'income']) ) {
                return response(['status' => 404, 'message' => 'failed', 'error' => 'invalid transaction type'], 404);
            } else {
                $transactions = Transaction::where('type', trim($type))->where('user_id', auth()->id())->paginate(15);
                return response(['status' => 200, 'message' => 'success', 'data' => $transactions], 200);
            }
        } catch (Exception $e) {
            return response(['status' => 404, 'message' => 'failed', 'error' => $e->getMessage()], 404);
        }

    }

    public function transactionsByDate(String $date) 
    {

        try {
            if(!in_array($date, ['today', 'week', 'month', 'year']) ) {
                return response(['status' => 404, 'message' => 'failed', 'error' => 'invalid transaction date'], 404);
            } else {
                if($date == 'today') {
                    $transactions = Transaction::whereBetween('created_at', [now()->startOfDay(), now()->endOfDay()])->where('user_id', auth()->id())->paginate(15);
                } else if($date == 'week') {
                    $transactions = Transaction::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->where('user_id', auth()->id())->paginate(15);
                } else if($date == 'month') {
                    $transactions = Transaction::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->where('user_id', auth()->id())->paginate(15);
                } else if($date == 'year') {
                    $transactions = Transaction::whereBetween('created_at', [now()->startOfYear(), now()->endOfYear()])->where('user_id', auth()->id())->paginate(15);
                }
                return response(['status' => 200, 'message' => 'success', 'data' => $transactions], 200);
            }
        } catch (Exception $e) {
            return response(['status' => 404, 'message' => 'failed', 'error' => $e->getMessage()], 404);
        }

    }

    public function findTransactionByDate(String $date) 
    {
        try {
            // $date = e($date);
            $transaction = Transaction::whereDate('created_at', request('date'))->where('user_id', auth()->id())->paginate(15); 
            if($transaction) {
                return response(['status' => 200, 'message' => 'success', 'data' => $transaction], 200);   
            } else {
                return response(['status' => 404, 'message' => 'success', 'error' => $this->notFound], 404);
            }
        } catch (Exception $e) {
            return response(['status' => 404, 'message' => 'failed', 'error' => $e->getMessage()], 404);
        }
    }

}
