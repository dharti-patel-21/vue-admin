<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Enums\AppointmentStatus;

class AppointmentController extends Controller
{
    public function index(){
        return Appointment::query()->with('client:id,first_name,last_name')
        ->when(request('status'), function ($query) {
            return $query->where('status',AppointmentStatus::from(request('status')));
        })->latest()->paginate()
        ->through(fn ($appointment) => [
            'id' => $appointment->id,
            'start_time' => $appointment->start_time->format('Y-m-d h:i A'),
            'end_time' => $appointment->end_time->format('Y-m-d h:i A'),
            'client' => $appointment->client,
            'status' => [
                'name' => $appointment->status->name,
                'color' => $appointment->status->color()
            ]
        ]);
    }

    public function getAppointmentStatus(){
        $cases = AppointmentStatus::cases();
        return collect($cases)->map(function ($status) {
            return [
                'name' => $status->name,
                'value' => $status->value,
                'count' => Appointment::where('status',$status->value)->count(),
                'color' => AppointmentStatus::from($status->value)->color()
            ];
        });
    }

    public function store(){

        request()->validate([
            'client_id' => 'required',
            'title' => 'required',
            'description' => 'required',
            'start_time' => 'required',
            'end_time' => 'required'
        ], [
            'client_id.required' => 'The client name field is required'
        ]);

        Appointment::create([
            'title' => request('title'),
            'client_id' => 1,
            'start_time' => now(),
            'end_time' => now(),
            'description' => request('description'),
            'status' => AppointmentStatus::SCHEDULED
        ]);

        return response()->json(['message'=>'success']);
    }

    public function edit(Appointment $appointment){
        return $appointment;
    }

    public function update(Appointment $appointment){

        $validated = request()->validate([
            'client_id' => 'required',
            'title' => 'required',
            'description' => 'required',
            'start_time' => 'required',
            'end_time' => 'required'
        ], [
            'client_id.required' => 'The client name field is required'
        ]);

        $appointment->update($validated);

        return response()->json(['message'=>'success']);
    }

    public function destroy(Appointment $appointment){
        $appointment->delete();
        return response()->json(['success' => true],200);
    }
}
