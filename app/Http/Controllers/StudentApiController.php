<?php
namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::get();

        return response()->json([
            "status" => "success",
            "data"   => $students,

        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'   => 'required|min:4',
            'email'  => 'required|unique:students,email',
            'gender' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'fail',
                'message' => $validator->errors(),
            ]);
        }

        $data = $request->all();
        Student::create($data);

        return response()->json([
            'status'  => 'success',
            'message' => 'Student created successfully',
        ], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $student = Student::find($id);

        if ($student) {
            return response()->json([
                'status' => 'success',
                'data'   => $student,
            ], 200);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'No user found',
        ], 404);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name'   => 'required|min:4',
            'email'  => 'required|email|unique:students,email,' . $id, // ✅ fixed
            'gender' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'fail',
                'message' => $validator->errors(),
            ], 400);
        }

        $student = Student::find($id);

        if (! $student) {
            return response()->json([
                'status'  => 'fail',
                'message' => 'No student found',
            ], 404);
        }

        $student->name   = $request->name;
        $student->email  = $request->email;
        $student->gender = $request->gender;
        $student->save();

        return response()->json([
            'status'  => 'success',
            'message' => 'Student updated successfully',
            'data'    => $student,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $student = Student::find($id);

        if (! $student) {
            return response()->json([
                'status'  => 'fail',
                'message' => 'No student found',
            ], 404);
        }

        $student->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Student deleted successfully',
        ], 200);
    }
}
