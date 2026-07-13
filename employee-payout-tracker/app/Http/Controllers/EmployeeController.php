<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Employee;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class EmployeeController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Employees/Index', [
            'employees' => Employee::query()
                ->withCount('payouts')
                ->orderBy('name')
                ->paginate(10),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Employees/Create');
    }

    public function store(StoreEmployeeRequest $request): RedirectResponse
    {
        Employee::create($request->validated());

        return redirect()->route('employees.index')->with('success', 'Employee added.');
    }

    public function edit(Employee $employee): Response
    {
        return Inertia::render('Employees/Edit', [
            'employee' => $employee,
        ]);
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee): RedirectResponse
    {
        $employee->update($request->validated());

        return redirect()->route('employees.index')->with('success', 'Employee updated.');
    }

    public function destroy(Employee $employee): RedirectResponse
    {
        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'Employee deleted.');
    }
}
