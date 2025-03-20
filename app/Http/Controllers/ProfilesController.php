<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profiles;
use App\Models\Skills;
use App\Models\Experiences;
use App\Models\Qualifications;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Exception;


class ProfilesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $pageSize = $request->get('page_size', 10);
            $profiles = Profiles::query();
            if ($request->has('search') && !empty($request->search)) {
                $search = $request->get('search');
                
                // Split the search term by '+' to handle multiple search terms
                $searchTerms = explode('+', $search);
            
                // If there are search terms, apply them to the query
                $profiles->where(function($query) use ($searchTerms) {
                    foreach ($searchTerms as $term) {
                        // Trim extra spaces for better matching
                        $term = trim($term);
            
                        // Search in fields like first_name, last_name, etc.
                        $query->where('first_name', 'LIKE', "%$term%")
                              ->orWhere('last_name', 'LIKE', "%$term%")
                              ->orWhere('city', 'LIKE', "%$term%")
                              ->orWhere('state', 'LIKE', "%$term%")
                              ->orWhere('address', 'LIKE', "%$term%");
            
                        // Search qualifications specifically for terms like "Diploma" or "PHP"
                        $query->orWhereHas('qualifications', function($q) use ($term) {
                            $q->where('qualification', 'LIKE', "%$term%");
                        });
            
                        // Search skills specifically for terms like "PHP"
                        $query->orWhereHas('skills', function($q) use ($term) {
                            $q->where('skill', 'LIKE', "%$term%");
                        });
            
                        // Search experience duration (e.g., "1 Year")
                        $query->orWhereHas('experiences', function($q) use ($term) {
                            $q->where('experience', 'LIKE', "%$term%"); // Assuming experience has a 'duration' field
                        });
                    }
                });
            }
            
            
            
            $profiles = $profiles->paginate($pageSize);
            if ($request->ajax()) {
                $html = view('profiles._table', compact('profiles'))->render();
                $pagination = $profiles->links('pagination::bootstrap-5')->render();
                return response()->json([
                    'profiles' => $html,
                    'paginationHtml' => $pagination
                ]);
            }
            return view('profiles.index', compact('profiles'));

        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Error fetching profiles: ' . $e->getMessage()]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $show = false;
        $skills = Skills::pluck('skill', 'id'); // Fetch skills as ['id' => 'name']
        $experiences = Experiences::pluck('experience', 'id'); // Fetch experiences
        $qualifications = Qualifications::pluck('qualification', 'id'); // Fetch qualifications
        return view('profiles.form',compact('show', 'skills', 'experiences', 'qualifications'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name'        => 'required|string|max:255',
            'last_name'         => 'required|string|max:255',
            'gender'            => 'required|in:male,female,other',
            'mobile_number'     => 'required|string|unique:profiles,mobile_number|max:15',
            'aadhar_card_no'    => 'nullable|string|unique:profiles,aadhar_card_no|max:12',
            'address'           => 'nullable|string|max:255',
            'city'              => 'nullable|string|max:255',
            'state'             => 'nullable|string|max:255',
            'pin_code'          => 'nullable|string|max:6',
            'skill_id'          => 'nullable|string|exists:skills,id',
            'qualification_id'  => 'nullable|string|exists:qualifications,id',
            'experience_id'     => 'nullable|string|exists:experiences,id',
            'current_salary'    => 'nullable|numeric|min:0',
            'photo'             => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'cv'                => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'password'          => 'required|string|min:8',
            'current_location'  => 'nullable|string|max:255',
            'passing_year'  => 'nullable|string|max:255',

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            // Handle file upload for photo (if available)
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $photoName = Str::random(10) . '.' . $photo->getClientOriginalExtension();
                $photo->storeAs('public/photos', $photoName);
                $photoPath = $photoName; // Store only filename
            }
        
            // Handle file upload for CV (if available)
            $cvPath = null;
            if ($request->hasFile('cv')) {
                $cv = $request->file('cv');
                $cvName = Str::random(10) . '.' . $cv->getClientOriginalExtension();
                $cv->storeAs('public/cv', $cvName);
                $cvPath = $cvName; // Store only filename
            }
        
            // Get all request data
            $data = $request->all();
            $data['photo'] = $photoPath;
            $data['cv'] = $cvPath;
            $data['password'] = Hash::make($request->password); // Hash password
            $profile = Profiles::create($data);
        
            return response()->json([
                'message' => 'Profile created successfully!',
                'profile' => $profile,
            ], 201);
        } catch (Exception $e) {
            \Log::error('Error creating profile: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while creating the profile.'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $show = true;
        $profile = Profiles::findOrFail($id);
        $skills = $profile->skills->pluck('skill', 'id'); // Fetch skills as ['id' => 'name']
        $experiences = $profile->experiences->pluck('experience', 'id'); 
        $qualifications = $profile->qualifications->pluck('qualification', 'id'); // Fetch qualifications
        return view('profiles.form',compact('profile','show', 'skills', 'experiences', 'qualifications'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $show = false;
            $profile = Profiles::findOrFail($id);
            $skills = Skills::pluck('skill', 'id'); // Fetch skills as ['id' => 'name']
            $experiences = Experiences::pluck('experience', 'id'); // Fetch experiences
            $qualifications = Qualifications::pluck('qualification', 'id'); // Fetch qualifications
            return view('profiles.form',compact('profile','show', 'skills', 'experiences', 'qualifications'));
        } catch (ModelNotFoundException $e) {
            return back()->withErrors(['error' => 'Profile not found.']);
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'An error occurred while fetching the Profile for editing: ' . $e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'first_name'        => 'required|string|max:255',
            'last_name'         => 'required|string|max:255',
            'gender'            => 'required|in:male,female,other',
            'mobile_number'     => 'required|string|unique:profiles,mobile_number,' . $id . '|max:15',
            'aadhar_card_no'    => 'nullable|string|unique:profiles,aadhar_card_no,' . $id . '|max:12',
            'address'           => 'nullable|string|max:255',
            'city'              => 'nullable|string|max:255',
            'state'             => 'nullable|string|max:255',
            'pin_code'          => 'nullable|string|max:6',
            'skill_id'          => 'nullable|string|exists:skills,id',
            'qualification_id'  => 'nullable|string|exists:qualifications,id',
            'experience_id'     => 'nullable|string|exists:experiences,id',
            'current_salary'    => 'nullable|numeric|min:0',
            'photo'             => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'cv'                => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'password'          => 'required|string|min:8',
            'current_location'  => 'nullable|string|max:255',
            'passing_year'  => 'nullable|string|max:255',

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            // Fetch profile
            $profile = Profiles::findOrFail($id);
    
            // Update profile fields except files
            $profile->update($request->except('photo', 'cv', 'password'));
    
            // Handle password update
            if ($request->filled('password')) {
                $profile->password = bcrypt($request->password);
            }
    
            // Handle photo upload
            if ($request->hasFile('photo')) {
                if ($profile->photo) {
                    $photoIsUsedElsewhere = Profiles::where('photo', $profile->photo)
                        ->where('id', '!=', $profile->id)
                        ->exists();
    
                    if (!$photoIsUsedElsewhere) {
                        Storage::delete('public/photos/' . $profile->photo);
                    }
                }
    
                $photo = $request->file('photo');
                $photoName = Str::random(10) . '.' . $photo->getClientOriginalExtension();
                $photo->storeAs('public/photos', $photoName);
                $profile->photo = $photoName;
            }
    
            // Handle CV upload
            if ($request->hasFile('cv')) {
                if ($profile->cv) {
                    $cvIsUsedElsewhere = Profiles::where('cv', $profile->cv)
                        ->where('id', '!=', $profile->id)
                        ->exists();
    
                    if (!$cvIsUsedElsewhere) {
                        Storage::delete('public/cv/' . $profile->cv);
                    }
                }
    
                $cv = $request->file('cv');
                $cvName = Str::random(10) . '.' . $cv->getClientOriginalExtension();
                $cv->storeAs('public/cv', $cvName);
                $profile->cv = $cvName;
            }
    
            // Save profile
            $profile->save();
    
            return response()->json([
                'success' => 'Profile updated successfully!',
                'profile' => $profile,
            ], 200);
    
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Profile not found.'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred while updating the profile.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Fetch the profile of the candidate by ID
            $profile = Profiles::findOrFail($id);

            // Delete photo if exists
            if ($profile->photo) {
                Storage::delete('public/photos/' . $profile->photo);
            }

            // Delete CV if exists
            if ($profile->cv) {
                Storage::delete('public/cv/' . $profile->cv);
            }

            // Delete the profile
            $profile->delete();

            // Return success response
            return response()->json([
                'message' => 'Profile deleted successfully!',
            ], 200);
        } catch (ModelNotFoundException $e) {
            // Handle case where the profile is not found
            return response()->json([
                'error' => 'Profile not found.',
                'message' => $e->getMessage(),
            ], 404); // Return 404 status code if the profile is not found
        } catch (Exception $e) {
            // Handle other server errors
            return response()->json([
                'error' => 'An error occurred while deleting the profile.',
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
            // dd($rowData);
            // exit;

            // Make sure there is enough data in the row
            if (count($rowData) >= 17) {
                // Assigning values to variables for readability
                $FirstName = $rowData[0]; 
                $LastName = $rowData[1];
                $Gender = $rowData[2];
                $MobNo = $rowData[3];
                $Aadarchad = $rowData[4];
                $Address = $rowData[5];
                $City = $rowData[6];
                $Status = $rowData[7];
                $PinCode = $rowData[8];
                $Education = $rowData[9];
                $Experience = $rowData[10];
                $Salary = $rowData[11];
                $Skill = $rowData[12];
                $Photo = $rowData[13];
                $Cv = $rowData[14];
                $Location = $rowData[15];
                $PassingYear = $rowData[16];

                

                // You may want to find the corresponding skill, qualification, and experience from your database
                $skill = Skills::where('skill', $Skill)->first(); // Assuming 'name' field exists in the skills table
                $qualification = Qualifications::where('qualification', $Education)->first(); // Assuming 'name' field exists in qualifications
                $experience = Experiences::where('experience', $Experience)->first(); // Assuming 'years' field exists in the experiences table

                Profiles::firstOrCreate([
                    'first_name'       => $FirstName,
                    'last_name'        => $LastName,
                    'gender'           => $Gender,
                    'mobile_number'    => $MobNo,
                    'aadhar_card_no'   => $Aadarchad,
                    'address'          => $Address,
                    'city'             => $City,
                    'state'            => $Status,
                    'pin_code'         => $PinCode,
                    'skill_id'         => $skill ? $skill->id : null, // If skill exists, link it
                    'qualification_id' => $qualification ? $qualification->id : null, // If qualification exists, link it
                    'experience_id'    => $experience ? $experience->id : null, // If experience exists, link it
                    'current_salary'   => $Salary,
                    'photo'            => $Photo,
                    'cv'               => $Cv,
                    'password'         => Hash::make('Test@123'), // Default password
                    'current_location' => $Location,
                    'passing_year'     => $PassingYear, // Default passing year
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
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set the header row
        $sheet->setCellValue('A1', 'First Name');
        $sheet->setCellValue('B1', 'Last Name');
        $sheet->setCellValue('C1', 'Gender');
        $sheet->setCellValue('D1', 'Mobile Number');
        $sheet->setCellValue('E1', 'Aadhar Card No');
        $sheet->setCellValue('F1', 'Address');
        $sheet->setCellValue('G1', 'City');
        $sheet->setCellValue('H1', 'State');
        $sheet->setCellValue('I1', 'Pin Code');
        $sheet->setCellValue('J1', 'Education');
        $sheet->setCellValue('K1', 'Work Experience');
        $sheet->setCellValue('L1', 'Current Salary');
        $sheet->setCellValue('M1', 'Skills');
        $sheet->setCellValue('N1', 'Photo');
        $sheet->setCellValue('O1', 'CV');
        $sheet->setCellValue('P1', 'Location');
        $sheet->setCellValue('Q1', 'Passing Year');
        
        // Set header row to bold
        $sheet->getStyle('A1:Q1')->getFont()->setBold(true);
        
        // Set column width to auto size (or specify a reasonable width)
        $columns = range('A', 'Q');
        foreach ($columns as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Set text wrapping for all cells
        $sheet->getStyle('A1:Q' . $sheet->getHighestRow())->getAlignment()->setWrapText(true);

        $selectedIds = $request->get('selected_ids');
        $selectedPage = $request->get('selected_page');

        // Create a new spreadsheet object
        if (is_string($selectedIds)) {
            $selectedIds = explode(',', $selectedIds); // Convert string to array
        }

        if ($selectedIds && $selectedPage) {
            $profiles = Profiles::whereIn('id', $selectedIds)->get();
        } elseif ($selectedIds) {
            $profiles = Profiles::whereIn('id', $selectedIds)->get();
        } elseif ($selectedPage) {
            $profiles = Profiles::paginate($selectedPage); // Adjust the number 10 based on page size
        } else {
            $profiles = Profiles::all();
        }

        $row = 2; // Start from row 2 for the actual data
        foreach ($profiles as $profile) {
            // Fetch related data (skills, qualification, experience)
            $skill = $profile->skills ? $profile->skills->skill : ''; // Assuming 'name' exists in the skills table
            $qualification = $profile->qualifications ? $profile->qualifications->qualification : ''; // Assuming 'name' exists in the qualifications table
            $experience = $profile->experiences ? $profile->experiences->experience : ''; // Assuming 'years' exists in the experiences table

            $sheet->setCellValue('A' . $row, $profile->first_name);
            $sheet->setCellValue('B' . $row, $profile->last_name);
            $sheet->setCellValue('C' . $row, $profile->gender);
            $sheet->setCellValue('D' . $row, $profile->mobile_number);
            $sheet->setCellValue('E' . $row, $profile->aadhar_card_no);
            $sheet->setCellValue('F' . $row, $profile->address);
            $sheet->setCellValue('G' . $row, $profile->city);
            $sheet->setCellValue('H' . $row, $profile->state);
            $sheet->setCellValue('I' . $row, $profile->pin_code);
            $sheet->setCellValue('J' . $row, $qualification); // Education
            $sheet->setCellValue('K' . $row, $experience); // Work Experience
            $sheet->setCellValue('L' . $row, $profile->current_salary);
            $sheet->setCellValue('M' . $row, $skill); // Skills
            $sheet->setCellValue('N' . $row, $profile->photo); // If you want to include the photo URL or path
            $sheet->setCellValue('O' . $row, $profile->cv); // If you want to include the CV URL or path
            $sheet->setCellValue('P' . $row, $profile->current_location);
            $sheet->setCellValue('Q' . $row, $profile->passing_year);

            // Adjust row height based on content
            $sheet->getRowDimension($row)->setRowHeight(-1); // Auto-adjust row height

            $row++;
        }

        // Set file name for export (you can add a dynamic date here)
        $fileName = 'profile_of_candidates_' . date('Y-m-d_H-i-s') . '.xlsx';

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


    const FORM_FIELDS = [
        'personal_information' => [
            ['type' => 'text', 'name' => 'first_name', 'label' => 'First Name'],
            ['type' => 'text', 'name' => 'last_name', 'label' => 'Last Name'],
            ['type' => 'select', 'name' => 'gender', 'label' => 'Gender', 'options' => ['male' => 'Male', 'female' => 'Female', 'other' => 'Other'], 'multiple' => false],
            ['type' => 'text', 'name' => 'mobile_number', 'label' => 'Mobile Number'],
            ['type' => 'text', 'name' => 'aadhar_card_no', 'label' => 'Aadhar Card No'],
            ['type' => 'text', 'name' => 'address', 'label' => 'Address'],
            ['type' => 'text', 'name' => 'city', 'label' => 'City'],
            ['type' => 'text', 'name' => 'state', 'label' => 'State'],
            ['type' => 'text', 'name' => 'pin_code', 'label' => 'Pin Code'],
            ['type' => 'text', 'name' => 'education', 'label' => 'Education'],
            ['type' => 'number', 'name' => 'current_salary', 'label' => 'Current Salary'],
            ['type' => 'password', 'name' => 'password', 'label' => 'Password'],
            ['type' => 'text', 'name' => 'current_location', 'label' => 'Current Location'],
            ['type' => 'select', 'name' => 'skill_id', 'label' => 'Skill' , 'options' => [], 'multiple' => true],
            ['type' => 'select', 'name' => 'qualification_id', 'label' => 'Qualification' , 'options' => [], 'multiple' => true],
            ['type' => 'select', 'name' => 'experience_id', 'label' => 'Experience' , 'options' => [] , 'multiple' => true],
            ['type' => 'file', 'name' => 'photo', 'label' => 'Profile Photo'],
            ['type' => 'file', 'name' => 'cv', 'label' => 'Upload CV'],
            ['type' => 'text', 'name' => 'passing_year', 'label' => 'Passing Year'],

        ],
    ];
        
}
