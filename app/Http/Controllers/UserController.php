<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Http\Response;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        try {
            $pageSize = $request->get('page_size', 10); // Default to 10 if no page size is provided
            $query = User::where('role', '!=', 'admin');
            if ($request->has('search')) {
                $search = $request->get('search');
                $query->where(function($query) use ($search) {
                    $query->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%");
                });
            }
            $user = $query->paginate($pageSize);
            if ($request->ajax()) {
                $tableHtml = view('masters.user._table', compact('user'))->render();
                $paginationHtml = $user->links('pagination::bootstrap-5')->render();

                return response()->json([
                    'user' => $tableHtml,
                    'paginationHtml' => $paginationHtml
                ]);
            }
            return view('masters.user.index', compact('user'));
        } catch (Exception $e) {
            return response()->json([
                'error' => 'An error occurred while fetching the user list.',
                'message' => $e->getMessage(),
            ], 500); // Return 500 status code for server errors
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $show = false;
        return view('masters.user.form',compact('show'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed', // 'confirmed' checks password_confirmation
            'designation' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        try {
            // Create and store the user
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password); // Store hashed password
            $user->designation = $request->designation;
            $user->save();
    
            // Return success response
            return response()->json([
                'message' => 'User created successfully!',
            ]);
        } catch (\Exception $e) {
            // Handle errors
            return response()->json(['error' => 'An error occurred, please try again later.'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $show = true;
        $user = User::findOrFail($id);
        return view('masters.user.form',compact('user','show'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $user = User::findOrFail($id);
            $show = false;
            return view('masters.user.form',compact('user','show'));
        } catch (ModelNotFoundException $e) {
            return back()->withErrors(['error' => 'User not found.']);
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'An error occurred while fetching the User for editing: ' . $e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id, // Ignore the email uniqueness for the current user
            'password' => 'nullable|string|min:8', // Make password optional (only required when changing)
            'designation' => 'required|string|max:255',
        ]);
    
        try {
            // Find the user by ID or fail if not found
            $user = User::findOrFail($id);
            
            // Update the user attributes
            $user->name = $request->name;
            $user->email = $request->email;
            
            // Update password if it's provided
            if ($request->password) {
                $user->password = Hash::make($request->password); // Hash the new password before saving
            }
            
            $user->designation = $request->designation;
    
            // Save the updated user to the database
            $user->save();
    
            // Return a success response with updated user data
            return response()->json([
                'message' => 'User updated successfully!',
                'user' => $user,
            ]);
        } catch (\Exception $e) {
            // Catch any errors and return an error response
            return response()->json(['error' => 'An error occurred, please try again later.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try { 
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully!',
        ]);
        } catch (\Exception $e) {
            // Catch any errors and return an error response
            return response()->json(['error' => 'An error occurred, please try again later.'], 500);
        }
    }

    public function import(Request $request)
    {
        // Validate the incoming file (xlsx, xls, or csv)
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        // Get the uploaded file
        $file = $request->file('file');

        // Load the spreadsheet
        try {
            $spreadsheet = IOFactory::load($file->getRealPath());
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to load the file.');
        }

        $sheet = $spreadsheet->getActiveSheet();

        // Initialize a counter for rows
        $importedCount = 0;

        // Iterate through all the rows
        foreach ($sheet->getRowIterator() as $row) {
            // Skip the first row (header)
            if ($row->getRowIndex() == 1) {
                continue;
            }

            $rowData = [];
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false); 

            // Collect row data from the cells
            foreach ($cellIterator as $cell) {
                $rowData[] = $cell->getValue();
            }

            // Make sure there is enough data in the row
            if (count($rowData) >= 4) {
                // Assigning values to variables for readability
                $EmployeeName = $rowData[0]; 
                $Designation = $rowData[1];
                $Email = $rowData[2];
                $Password = Hash::make($rowData[3]);

                // Creating or updating the User record
                User::firstOrCreate([
                    'name' => $EmployeeName,
                    'designation' => $Designation,
                    'email' => $Email,
                    'password' => $Password,
                ]);

                // Increment the imported count
                $importedCount++;
            }
        }

        // Return appropriate message based on import success
        if ($importedCount > 0) {
            return back()->with('success', "$importedCount users have been imported successfully.");
        }

        return back()->with('error', 'No valid data found to import.');
    }
    
    public function export(Request $request)
    {
        // Create a new spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set column headers for Excel file
        $sheet->setCellValue('A1', 'Employee Name');
        $sheet->setCellValue('B1', 'Designation');
        $sheet->setCellValue('C1', 'Email');
        
        // Set header row to bold
        $sheet->getStyle('A1:C1')->getFont()->setBold(true);
        
        // Set column width to auto size (or specify a reasonable width)
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);

        // Set text wrapping for all cells
        $sheet->getStyle('A1:C' . $sheet->getHighestRow())->getAlignment()->setWrapText(true);

        // Retrieve all users from the database
        $selectedIds = $request->get('selected_ids');
        $selectedPage = $request->get('selected_page');
        // Create a new spreadsheet object
        if (is_string($selectedIds)) {
            $selectedIds = explode(',', $selectedIds); // Convert string to array
        }
        if ($selectedIds && $selectedPage) {
            $users = User::whereIn('id', $selectedIds)->get();
        } elseif ($selectedIds) {
            $users = User::whereIn('id', $selectedIds)->get();
        } elseif ($selectedPage) {
            $users = User::paginate($selectedPage); // Adjust the number 10 based on page size
        } else {
            $users = User::all();
        }

        // Add user data to the spreadsheet starting from row 2
        $row = 2;
        foreach ($users as $user) {
            $sheet->setCellValue('A' . $row, $user->name);
            $sheet->setCellValue('B' . $row, $user->designation);
            $sheet->setCellValue('C' . $row, $user->email);
            
            // Adjust row height based on content
            $sheet->getRowDimension($row)->setRowHeight(-1); // Auto-adjust row height
            
            $row++;
        }

        // Set file name for export (you can add a dynamic date here)
        $fileName = 'user_data_' . date('Y-m-d_H-i-s') . '.xlsx';

        // Create a new Xlsx writer
        $writer = new Xlsx($spreadsheet);

        // Stream the generated Excel file to the user
        return response()->stream(
            function () use ($writer) {
                $writer->save('php://output');
            },
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment;filename="' . $fileName . '"',
                'Cache-Control' => 'max-age=0',
            ]
        );
    }

    public function updateStatus(Request $request)
    {
        $user = User::find($request->user_id);

        if ($user) {
            // Toggle the user's status
            $user->status = $request->status;
            $user->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 400);
    }

}
