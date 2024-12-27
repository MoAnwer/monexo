<?php

namespace App\Http\Controllers;

use PDOException;
use Exception;
use Illuminate\Http\Request;
use App\Models\Goal;

class GoalController extends Controller
{
    protected string $notFound = "goal not found";

    public function index()
    {
        try {
            return response(['status' => 200, 'message' => 'success', 'data' => Goal::where('user_id', auth()->id())->paginate(15)], 200);
        } catch(PDOException $e) {
            return response(['status' => 404, 'message' => 'failed', 'error' => $e->getMessage()]);
        } catch(Exception $e) {
            return response(['status' => 404, 'message' => 'failed', 'error' => $e->getMessage()]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
        
            $validateData = request()->validate([
                'name' => 'string|required',
                'target_amount' => 'integer|required',
                'current_amount' => 'integer',
                'due_date' => 'date'
            ]);            
            $validateData['user_id'] = auth()->id();
            $goal = Goal::create($validateData);

            return response(['status' => 200, 'message' => 'success', 'data' => $goal], 200);

        } catch(Exception $e) {
            return response(['status' => 404, 'message' => 'failed', 'error' => $e->getMessage()], 404);
        }
    }

    public function show(int $id)
    {
        try {
            $goal = Goal::find($id);
            if($goal && $goal->user_id == auth()->id()) {
                $remain = $goal->calcRemainingAmount($goal->target_amount, $goal->current_amount);
                $progress = $goal->calcProgress($goal->target_amount, $goal->current_amount);
                $goal->stats = ['remain_amount' => $remain, 'progress' => $progress];
                return response(['status' => 200, 'message' => 'success', 'data' => $goal], 200);
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
                'name' => 'string',
                'target_amount' => 'integer',
                'current_amount' => 'integer',
                'due_date' => 'date'
            ]);
            
            $goal = Goal::find($id);

            if($goal && $goal->user_id == auth()->id()) {
                $goal->update($validateData);
                return response(['status' => 200, 'message' => 'success', 'data' => $goal], 200);
            } else {
                return response(['status' => 404, 'message' => 'success', 'error' => $this->notFound], 404);
            }

        } catch(Exception $e) {

            return response(['status' => 404, 'message' => 'failed', 'error' => $e->getMessage()], 404);
        }
    }

    public function delete($id)
    {
        try {
            $goal = Goal::find($id);
            if($goal && $goal->user_id == auth()->id()) {
                $goal->delete();
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

    public function search(String $search) 
    {
        try {
            $searchGoal = Goal::where('name', e($search))->get();
            return response(['status' => 200, 'message' => 'success', 'data' => $searchGoal], 200);
        } catch (PDOException $e) {
            return response(['status' => 404, 'message' => 'failed', 'error' => $e->getMessage()], 404);
        } catch (Exception $e) {
            return response(['status' => 404, 'message' => 'failed', 'error' => $e->getMessage()], 404);
        }
    }
}
