<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeeWorkedHour;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{

    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'job_id' => 'required|exists:jobs,id',
        ]);

        if ($validator->fails()) {
            return json_encode([
                                'status' => '503',
                                'message' => $validator->errors()
                               ]);
        }

        $employees = Employee::where('job_id', $request->job_id)->get();

        return json_encode([ 'employees' => $employees]);
    }

    public function store(Request $request)
    {
        $dt = new Carbon();
        $before = $dt->subYears(18)->format('Y-m-d');

        $validator = Validator::make($request->all(), [
            'gender_id' => 'required|exists:genders,id',
            'job_id' => 'required|exists:jobs,id',
            'name' => 'required',
            'last_name' => 'required',
            'birthdate' => 'required|date|before:' . $before,
        ]);

        if ($validator->fails()) {
            return json_encode([
                                'status' => '503',
                                'message' => $validator->errors()
                               ]);
        }

        $exist = Employee::where('name', $request->name)->where('last_name', $request->last_name)->first();

        if ($exist) {
            return json_encode([
                'status' => '503',
                'message' => 'The employee already exists',
               ]);
        }

        $employ = new Employee();
        $employ->job_id = $request->job_id;
        $employ->gender_id = $request->gender_id;
        $employ->name = $request->name;
        $employ->last_name = $request->last_name;
        $employ->birthdate = $request->birthdate;
        $employ->save();

        return json_encode([
            'id' => $employ->id,
            'success' => true,
        ]);

    }

    public function workedHours(Request $request)
    {
        $dt = new Carbon();
        $today = $dt->addDay(1)->format('Y-m-d');

        $validator = Validator::make($request->all(), [
            "employee_id" =>  'required|exists:employees,id',
            "worked_hours" =>  'required|numeric|max:20',
            "worked_date" => 'required|date|before:' . $today,
        ]);

        if ($validator->fails()) {
            return json_encode([
                                'status' => '503',
                                'message' => $validator->errors()
                               ]);
        }

        $worked = new EmployeeWorkedHour();
        $worked->employee_id = $request->employee_id;
        $worked->worked_hours = $request->worked_hours;
        $worked->worked_date = $request->worked_date;
        $worked->save();

        return json_encode([
            "id" => $worked->id,
            "success" => true,
        ]);

    }

    public function workedEmployee(Request $request)
    {

        $validator = Validator::make($request->all(), [
            "employee_id" => 'required|exists:employees,id',
            "start_date" => 'required|date',
            "end_date" => 'required|date|after_or_equal:' . $request->start_date
        ]);

        if ($validator->fails()) {
            return json_encode([
                                'status' => '503',
                                'message' => $validator->errors()
                               ]);
        }


        $hoursModel = EmployeeWorkedHour::where('employee_id', $request->employee_id)->whereBetween('worked_date', [$request->start_date, $request->end_date])->get();
        $totalHours = 0;

        foreach ($hoursModel as $hours) {
            $totalHours = $totalHours + $hours->worked_hours;
        }

        return json_encode([
            "total_worked_hours" => $totalHours,
            "success" =>  true,
        ]);

    }

    public function paymentEmployee(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "employee_id" => 'required|exists:employees,id',
            "start_date" => 'required|date',
            "end_date" => 'required|date|after_or_equal:' . $request->start_date
        ]);

        if ($validator->fails()) {
            return json_encode([
                                'status' => '503',
                                'message' => $validator->errors()
                               ]);
        }


        $hoursModel = EmployeeWorkedHour::where('employee_id', $request->employee_id)->whereBetween('worked_date', [$request->start_date, $request->end_date])->get();
        $totalHours = 0;

        foreach ($hoursModel as $hours) {
            $totalHours = $totalHours + $hours->worked_hours;
        }

        $employee = Employee::find($request->employee_id);
        
        return json_encode([
            "payment" => $totalHours * $employee->job->salary,
            "success" =>  true,
        ]);
    }

}
