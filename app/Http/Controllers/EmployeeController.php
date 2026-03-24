<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeRole;
use App\Models\User;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with(['roles', 'user'])->orderByDesc('id')->paginate(15);
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        $roles = EmployeeRole::orderBy('name')->get();
        return view('employees.create', compact('users', 'roles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
            'employee_code' => ['nullable', 'string', 'max:50', 'unique:employees,employee_code'],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'designation' => ['nullable', 'string', 'max:255'],
            'joining_date' => ['nullable', 'date'],
            'is_active' => ['nullable', 'boolean'],
            'role_ids' => ['nullable', 'array'],
            'role_ids.*' => ['exists:employee_roles,id'],
        ]);

        $employee = Employee::create([
            'user_id' => $data['user_id'] ?? null,
            'employee_code' => $data['employee_code'] ?? null,
            'name' => $data['name'],
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
            'designation' => $data['designation'] ?? null,
            'joining_date' => $data['joining_date'] ?? null,
            'is_active' => (bool)($data['is_active'] ?? true),
        ]);

        $employee->roles()->sync($data['role_ids'] ?? []);

        return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
    }

    public function edit(Employee $employee)
    {
        $employee->load('roles');
        $users = User::orderBy('name')->get();
        $roles = EmployeeRole::orderBy('name')->get();
        return view('employees.edit', compact('employee', 'users', 'roles'));
    }

    public function update(Request $request, Employee $employee)
    {
        $data = $request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
            'employee_code' => ['nullable', 'string', 'max:50', 'unique:employees,employee_code,' . $employee->id],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'designation' => ['nullable', 'string', 'max:255'],
            'joining_date' => ['nullable', 'date'],
            'is_active' => ['nullable', 'boolean'],
            'role_ids' => ['nullable', 'array'],
            'role_ids.*' => ['exists:employee_roles,id'],
        ]);

        $employee->update([
            'user_id' => $data['user_id'] ?? null,
            'employee_code' => $data['employee_code'] ?? null,
            'name' => $data['name'],
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
            'designation' => $data['designation'] ?? null,
            'joining_date' => $data['joining_date'] ?? null,
            'is_active' => (bool)($data['is_active'] ?? false),
        ]);

        $employee->roles()->sync($data['role_ids'] ?? []);

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }
}
