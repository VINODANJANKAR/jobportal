<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\CompanyLocations;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Http\Response;
use Exception;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        try {
            $pageSize = $request->get('page_size', 10); // Default to 10 if no page size is provided
            $query = Company::query();

            if ($request->has('search')) {
                $search = $request->get('search');
                $query->where('name', 'LIKE', "%{$search}%");
            }

            $companies = $query->withCount('locations')->paginate($pageSize);

            if ($request->ajax()) {
                $html = view('masters.company.partials.company-table', compact('companies'))->render();
                $pagination = $companies->links('pagination::bootstrap-5')->render();

                return response()->json([
                    'companies' => $html,
                    'paginationHtml' => $pagination
                ]);
            }

            return view('masters.company.index', compact('companies'));
        } catch (Exception $e) {
            return response()->json([
                'error' => 'An error occurred while fetching the company list.',
                'message' => $e->getMessage(),
            ], 500); // Return 500 status code for server errors
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
            ]);

          

            $company = Company::create([
                'name' => $validated['name'],
            ]);

            return response()->json(['message' => 'Company created successfully!', 'company' => $company]);

        } catch (Exception $e) {
            return response()->json([
                'error' => 'An error occurred while creating the company.',
                'message' => $e->getMessage(),
            ], 500); // Return 500 status code for server errors
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $company = Company::findOrFail($id);
            return response()->json(['company' => $company]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Company not found.',
                'message' => $e->getMessage(),
            ], 404); // Return 404 status code if the company is not found
        } catch (Exception $e) {
            return response()->json([
                'error' => 'An error occurred while fetching the company for editing.',
                'message' => $e->getMessage(),
            ], 500); // Return 500 status code for server errors
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
            ]);

          

            $company = Company::findOrFail($id);
            $company->update([
                'name' => $validated['name'],
            ]);

            return response()->json(['message' => 'Company updated successfully!', 'company' => $company]);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Company not found.',
                'message' => $e->getMessage(),
            ], 404); // Return 404 status code if the company is not found
        } catch (Exception $e) {
            return response()->json([
                'error' => 'An error occurred while updating the company.',
                'message' => $e->getMessage(),
            ], 500); // Return 500 status code for server errors
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $company = Company::findOrFail($id);
            $company->delete();
            return response()->json(['message' => 'Company deleted successfully!']);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Company not found.',
                'message' => $e->getMessage(),
            ], 404); // Return 404 status code if the company is not found
        } catch (Exception $e) {
            return response()->json([
                'error' => 'An error occurred while deleting the company.',
                'message' => $e->getMessage(),
            ], 500); // Return 500 status code for server errors
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
        $spreadsheet = IOFactory::load($file->getRealPath());

        // Get the first sheet (or select another sheet by index)
        $sheet = $spreadsheet->getActiveSheet();
        
        $companyData = [];
        $locationData = [];

        // Skip the header row and start from the second row
        $rowIndex = 2; // Start from the second row (assuming first row is the header)
        foreach ($sheet->getRowIterator($rowIndex) as $row) {
            $rowData = [];
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false); // Allow iteration over empty cells

            // Collect data from each cell in the row
            foreach ($cellIterator as $cell) {
                $rowData[] = $cell->getValue();
            }

            // Ensure the row has enough data (at least 4 columns: name, location, city, address)
            if (count($rowData) >= 4) {
                // Check if the company already exists, if not, create a new company record
                $companyName = $rowData[0]; // Company name is in the first column
                $company = Company::firstOrCreate(['name' => $companyName]);

                // Collect the location, city, and address
                $locationData[] = [
                    'company_id' => $company->id,
                    'location'   => $rowData[1], // Location is in the second column
                    'city'       => $rowData[2], // City is in the third column
                    'address'    => $rowData[3], // Address is in the fourth column
                ];
            }
        }

        // Insert all company locations in bulk (this assumes the data array is populated)
        if (!empty($locationData)) {
            CompanyLocations::insert($locationData);
            return back()->with('success', 'Companies and their locations have been imported successfully.');
        }

        return back()->with('error', 'No data found to import.');
    }

    public function export(Request $request)
    {
        // Create a new spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
    
        // Set column headers for Excel file
        $sheet->setCellValue('A1', 'Company Name');
        $sheet->setCellValue('B1', 'Location');
        $sheet->setCellValue('C1', 'Location Map');
        $sheet->setCellValue('D1', 'City');
        $sheet->setCellValue('E1', 'Address');
        
        // Set header row to bold
        $sheet->getStyle('A1:E1')->getFont()->setBold(true);
        
        // Set column width to auto size (or specify a reasonable width)
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
    
        // Set text wrapping for all cells
        $sheet->getStyle('A1:E' . $sheet->getHighestRow())->getAlignment()->setWrapText(true);
    
        // Retrieve companies with their locations from the database
        $selectedIds = $request->get('selected_ids');
        $selectedPage = $request->get('selected_page');
        // Create a new spreadsheet object
        if (is_string($selectedIds)) {
            $selectedIds = explode(',', $selectedIds); // Convert string to array
        }
        if ($selectedIds && $selectedPage) {
            $companies = Company::whereIn('id', $selectedIds)->get();
        } elseif ($selectedIds) {
            $companies = Company::whereIn('id', $selectedIds)->get();
        } elseif ($selectedPage) {
            $companies = Company::paginate($selectedPage); // Adjust the number 10 based on page size
        } else {
            $companies = Company::all();
        }

        // Add company and location data to the spreadsheet starting from row 2
        $row = 2;
        foreach ($companies as $company) {
            foreach ($company->locations as $location) {
                $sheet->setCellValue('A' . $row, $company->name); // Company name
                $sheet->setCellValue('B' . $row, $location->location); // Location
                $sheet->setCellValue('C' . $row, $location->location_map); // Location Map
                $sheet->setCellValue('D' . $row, $location->city); // City
                $sheet->setCellValue('E' . $row, $location->address); // Address
                
                // Adjust row height based on content
                $sheet->getRowDimension($row)->setRowHeight(-1); // Auto-adjust row height
                
                $row++;
            }
        }
    
        // Set file name for export (you can add a dynamic date here)
        $fileName = 'company_location_data_' . date('Y-m-d_H-i-s') . '.xlsx';
    
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
    

    public function getLocations(Company $company)
    {
        return response()->json($company->locations);
    }

}
