<?php

namespace App\Http\Controllers\API\Training;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Api\Trainers;
use App\Models\Api\TrainingMaterial;
use App\Models\Api\TrainingBatch;
use App\Models\Api\TrainingMaterialsDocument;
use App\Models\Api\TrainerAssessment;
use App\Models\Api\AssessmentOption;
use App\Models\Api\AssessmentQuestion;
use App\Models\Api\AdditionalInfo;
use App\Services\ZoomService;
use Carbon\Carbon;


use Illuminate\Support\Facades\Mail;
class TrainingController extends Controller
{
    public function saveTrainingRecordedData(Request $request)
    {
        try {
                       
            // Validate request
            $validator = Validator::make($request->all(), [
                'trainerId' => 'required',
                'training_title' => 'required|string|max:255',
                'training_sub_title' => 'required|string|max:255',
                'training_descriptions' => 'nullable|string',
                'training_category' => 'required|string',
                'training_level' => 'required|string',
                'training_price' => 'required|numeric',
                'training_offer_price' => 'required|numeric',
                'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'content_sections' => 'array',
                'content_sections.*.title' => 'required|string|max:255',
                'content_sections.*.description' => 'required|string',
                'content_sections.*.file_duration' => 'required',
                'content_sections.*.file' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,mp4,mov,avi,mkv|max:51200',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first(), // ✅ Return only the first error
                ], 422);
            }
            $trainerId = $request->trainerId ;
            $trainer = Trainers::where('id', $trainerId)->first();
            DB::beginTransaction();

            // Handle thumbnail upload
            $thumbnailFilePath = null;
            $thumbnailFileName = null;

            if ($request->hasFile('thumbnail')) {
                $file = $request->file('thumbnail');
                $thumbnailFileName = 'thumbnail_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads'), $thumbnailFileName);
                $thumbnailFilePath = asset('uploads/' . $thumbnailFileName);
            }

            $training = TrainingMaterial::where('id', $request->courseId)->first();

            if ($training) {
                // Update existing training
                $training->trainer_id = $trainer->id;
                $training->training_type = 'recorded';
                $training->training_title = $request->training_title;
                $training->training_sub_title = $request->training_sub_title;
                $training->training_descriptions = $request->training_descriptions;
                $training->training_category = $request->training_category;
                $training->training_level = $request->training_level;
                $training->training_price = $request->training_price;
                $training->training_offer_price = $request->training_offer_price;
                $training->thumbnail_file_path = $thumbnailFilePath;
                $training->thumbnail_file_name = $thumbnailFileName;
                $training->admin_status = 'pending';
            } else {
                // Create new training
                $training = new TrainingMaterial();
                $training->trainer_id = $trainer->id;
                $training->training_type = 'recorded';
                $training->training_title = $request->training_title;
                $training->training_sub_title = $request->training_sub_title;
                $training->training_descriptions = $request->training_descriptions;
                $training->training_category = $request->training_category;
                $training->training_level = $request->training_level;
                $training->training_price = $request->training_price;
                $training->training_offer_price = $request->training_offer_price;
                $training->thumbnail_file_path = $thumbnailFilePath;
                $training->thumbnail_file_name = $thumbnailFileName;
                $training->training_objective = null;
                $training->session_type = null;
                $training->admin_status = 'pending';
                $training->rejection_reason = null;
            }

            $training->save();


            // Save training using Eloquent
            // $training = new TrainingMaterial();
            // $training->trainer_id = $trainer->id;
            // $training->training_type = 'recorded';
            // $training->training_title = $request->training_title;
            // $training->training_sub_title = $request->training_sub_title;
            // $training->training_descriptions = $request->training_descriptions;
            // $training->training_category = $request->training_category;
            // $training->training_level = $request->training_level;
            // $training->training_price = $request->training_price;
            // $training->training_offer_price = $request->training_offer_price;
            // $training->thumbnail_file_path = $thumbnailFilePath;
            // $training->thumbnail_file_name = $thumbnailFileName;
            // $training->training_objective = null;
            // $training->session_type = null;
            // $training->admin_status = 'pending';
            // $training->rejection_reason = null;
            // $training->save();

            // Handle content sections
            if ($request->has('content_sections')) {
                foreach ($request->content_sections as $index => $section) {
                    $filePath = null;
                    $fileName = null;

                    if (isset($section['file']) && $section['file'] instanceof \Illuminate\Http\UploadedFile) {
                        $uploadedFile = $section['file'];
                        $fileName = 'section_' . time() . '_' . $index . '.' . $uploadedFile->getClientOriginalExtension();
                        $uploadedFile->move(public_path('uploads'), $fileName);
                        $filePath = asset('uploads/' . $fileName);
                    }

                    // Check if content section has an ID (for update)
                    if (isset($section['id'])) {
                        $document = TrainingMaterialsDocument::where('id', $section['id'])
                                    ->where('training_material_id', $training->id)
                                    ->first();

                        if ($document) {
                            // Update existing record
                            $document->training_title = $section['title'];
                            $document->description = $section['description'];
                            if ($filePath) {
                                $document->file_path = $filePath;
                                $document->file_name = $fileName;
                                $document->file_duration = $section['file_duration'];
                            }
                            $document->save();
                            continue;
                        }
                    }

                    // Create new record
                    $document = new TrainingMaterialsDocument();
                    $document->trainer_id = $trainer->id;
                    $document->training_material_id = $training->id;
                    $document->training_title = $section['title'];
                    $document->description = $section['description'];
                    $document->file_path = $filePath;
                    $document->file_name = $fileName;
                    $document->file_duration = $section['file_duration'];
                    $document->save();
                }
            }

            // if ($request->has('content_sections')) {
            //     foreach ($request->content_sections as $index => $section) {
            //         $filePath = null;
            //         $fileName = null;

            //         if (isset($section['file']) && $section['file'] instanceof \Illuminate\Http\UploadedFile) {
            //             $uploadedFile = $section['file'];
            //             $fileName = 'section_' . time() . '_' . $index . '.' . $uploadedFile->getClientOriginalExtension();
            //             $uploadedFile->move(public_path('uploads'), $fileName);
            //             $filePath = asset('uploads/' . $fileName);
            //         }

            //         $document = new TrainingMaterialsDocument();
            //         $document->trainer_id = $trainer->id;
            //         $document->training_material_id = $training->id;
            //         $document->training_title = $section['title'];
            //         $document->description = $section['description'];
            //         $document->file_path = $filePath;
            //         $document->file_name = $fileName;
            //         $document->save();
            //     }
            // }

            DB::commit();

            if($request->courseId)
            {
                return response()->json([
                    'success' => true,
                    'message' => 'Recorded training course updated successfully.',
                    'training_id' => $training->id
                ]);
            }else{
                return response()->json([
                    'success' => true,
                    'message' => 'Recorded training course saved successfully.',
                    'training_id' => $training->id
                ]);
            }

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while saving training.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function saveTrainingOnlineData(Request $request)
    {
        try {           

             $validator = Validator::make($request->all(), [
                'trainerId' => 'required',
                'training_title'         => 'required|string|max:255',
                'training_sub_title'     => 'nullable|string|max:255',
                'training_objective'     => 'nullable|string',
                'training_descriptions'  => 'nullable|string',
                'training_category'      => 'required|string',
                'training_level'         => 'required|string',
                'training_price'         => 'required|numeric',
                'training_offer_price'   => 'required|numeric',
                'thumbnail'              => 'nullable|image|max:2048',
                'training_type'          => 'required|string',            
                
                'content_sections'               => 'required|array|min:1',
                'content_sections.*.batch_no'    => 'required|string|max:255',
                'content_sections.*.batch_date'  => 'required|date',
                'content_sections.*.start_time'  => 'required|string',
                'content_sections.*.end_time'    => 'required|string',
                'content_sections.*.duration'    => 'required|string',
                'content_sections.*.duration_type'    => 'required|string',
                'content_sections.*.strength'   => 'required|integer|min:1',
                'content_sections.*.days'       => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first(), // ✅ Return only the first error
                ], 422);
            }

            $trainerId = $request->trainerId ;
            $trainer = Trainers::where('id', $trainerId)->first();
            // Handle thumbnail
            $thumbnailFilePath = null;
            $thumbnailFileName = null;

            if ($request->hasFile('thumbnail')) {
                $file = $request->file('thumbnail');
                $thumbnailFileName = 'thumbnail_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads'), $thumbnailFileName);
                $thumbnailFilePath = asset('uploads/' . $thumbnailFileName);
            }

            
                
            $training = TrainingMaterial::where('id', $request->courseId)->first();
            // Save training using Eloquent
            if ($training) {
                // Update existing training
                $training->trainer_id = $trainer->id;
                $training->training_type = $request->training_type ;
                $training->training_title = $request->training_title;
                $training->training_sub_title = $request->training_sub_title;
                $training->training_descriptions = $request->training_descriptions;
                $training->training_category = $request->training_category;
                $training->training_level = $request->training_level;
                $training->training_price = $request->training_price;
                $training->training_offer_price = $request->training_offer_price;
                $training->thumbnail_file_path = $thumbnailFilePath;
                $training->thumbnail_file_name = $thumbnailFileName;
                //$training->strength = $request->strength;
            } else {
                // Create new training
                $training = new TrainingMaterial();
                $training->trainer_id = $trainer->id;
                $training->training_type = $request->training_type ;
                $training->training_title = $request->training_title;
                $training->training_sub_title = $request->training_sub_title;
                $training->training_descriptions = $request->training_descriptions;
                $training->training_category = $request->training_category;
                $training->training_level = $request->training_level;
                $training->training_price = $request->training_price;
                $training->training_offer_price = $request->training_offer_price;
                $training->thumbnail_file_path = $thumbnailFilePath;
                $training->thumbnail_file_name = $thumbnailFileName;
                $training->training_objective = null;
                $training->session_type = null;
                $training->admin_status = 'pending';
                //$training->strength = $request->strength;
            }
            $training->save();
            
            
            // $training = new TrainingMaterial();
            // $training->trainer_id           = $trainer->id;
            // $training->training_title       = $request->training_title;
            // $training->training_sub_title   = $request->training_sub_title;
            // $training->training_objective   = $request->training_objective;
            // $training->training_descriptions = $request->training_descriptions;
            // $training->training_level       = $request->training_level;
            // $training->training_price       = $request->training_price;
            // $training->training_offer_price = $request->training_offer_price;
            // $training->training_type        = $request->training_type;
            // $training->session_type         = $request->training_category;
            // $training->thumbnail_file_name  = $thumbnailFileName;
            // $training->thumbnail_file_path  = $thumbnailFilePath;
            // $training->admin_status         = 'pending';
            // $training->save();

            // Save training batches using Eloquent

            if ($request->has('content_sections')) {
                foreach ($request->content_sections as $index => $section) {
                    // Check if content section has an ID (for update)

                    $zoom = new ZoomService();
                    $date = Carbon::createFromFormat('d/m/Y', $section['batch_date'])->format('Y-m-d');
                    $startTime = $date . ' ' . $section['start_time'];

                    $zoomMeeting = $zoom->createMeeting("Batch #{$section['batch_no']}", $startTime);
 
                    // if (!$zoomMeeting || !isset($zoomMeeting['start_url'])) {
                    //     throw new \Exception("Zoom creation failed for batch {$section['batch_no']}");
                    // }
                    echo $durationType = $section['duration_type'] ;
                    $duration = $section['duration'] ;
                     $start = Carbon::parse($section['batch_date']) ;
                    switch ($durationType) {
                        case 'Day':                        
                           echo "===" .$end_date =  $start->copy()->addDays($duration);
                        case 'Month':
                           echo  "=-----==" .$end_date =  $start->copy()->addMonths($duration);
                        case 'Year':
                           echo "========" . $end_date =  $start->copy()->addYears($duration);
                        default:
                            throw new \Exception("Invalid duration type: $durationType");
                    }
                    
                    if (isset($section['id'])) {
                        $document = TrainingBatch::where('id', $section['id'])
                                    ->where('training_material_id', $training->id)
                                    ->first();

                        if ($document) {
                            // Update existing record
                            $document->trainer_id = $trainer->id;
                            $document->training_material_id = $training->id;
                            $document->batch_no     = $section['batch_no'];
                            $document->start_date   = date("Y-m-d", strtotime($section['batch_date'])) ;
                            $document->start_timing = date("H:i", strtotime($section['start_time']));
                            $document->end_timing   = date("H:i", strtotime($section['end_time']));
                            $document->duration     = $section['duration'].' '.$section['duration_type'];
                            $document->strength     = $section['strength'];
                            $document->days     =json_encode(json_decode($section['days'], true)); // convert from stringified JSON
                            $document->save();
                            continue;
                        }
                    }

                    // Create new record
                    $document = new TrainingBatch();
                    $document->trainer_id = $trainer->id;
                    $document->training_material_id = $training->id;
                    $document->batch_no     = $section['batch_no'];
                    $document->start_date   = date("Y-m-d", strtotime($section['batch_date'])) ;
                    $document->start_timing = date("H:i", strtotime($section['start_time']));
                    $document->end_timing   = date("H:i", strtotime($section['end_time']));
                    $document->end_date   = date("Y-m-d", strtotime($end_date)) ;

                    $document->duration     = $section['duration'];
                    $document->strength     = $section['strength'];
                    $document->days     =json_encode(json_decode($section['days'], true)); // convert from stringified JSON
                    $document->zoom_start_url      = '';//$zoomMeeting['start_url'];
                    $document->zoom_join_url        = '';//$zoomMeeting['join_url'];
                    $document->save();
                }
            }
            // foreach ($request->content_sections as $section) {
            //     TrainingBatch::create([
            //         'trainer_id'   => $trainer->id,
            //         'training_material_id'   => $training->id,
            //         'batch_no'     => $section['batch_no'],
            //         'start_date'   => $section['batch_date'],
            //         'start_timing' => date("H:i", strtotime($section['start_time'])),
            //         'end_timing'   => date("H:i", strtotime($section['end_time'])),
            //         'duration'     => $section['duration'],
            //     ]);
            // }
            if($request->courseId)
            {
                return response()->json([
                    'success' => true,
                    'message' => 'Training and batches updated successfully.',
                    'data'    => $training->load('batches') // Optional: return with batch data
                ], 201);
            }else{
                return response()->json([
                    'success' => true,
                    'message' => 'Training and batches saved successfully.',
                    'data'    => $training->load('batches') // Optional: return with batch data
                ], 201);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save training.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function assessmentStore(Request $request)
    {
        try {
            // Decode JSON if sent as a string (common in form-data)
            if (is_string($request->input('questions'))) {
                $request->merge([
                    'questions' => json_decode($request->input('questions'), true)
                ]);
            }

            $validator = Validator::make($request->all(), [
                'trainerId' => 'required',
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'level' => 'required|string|max:100',
                'total_questions' => 'required|integer|min:1',
                'passing_questions' => 'required|integer|min:1',
                'passing_percentage' => 'required|string',
                'questions' => 'required|array|min:1',
                'questions.*.text' => 'required|string',
                'questions.*.options' => 'required|array|min:2',
                'questions.*.options.*.text' => 'required|string',
                'questions.*.options.*.correct' => 'required|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first(), // ✅ Return only the first error
                ], 422);
            }
            $trainerId = $request->trainerId ;
            $trainer = Trainers::where('id', $trainerId)->first();
            $validated = $validator->validated();

            DB::beginTransaction();

            $assessment = TrainerAssessment::create([
                'assessment_title'      => $validated['title'],
                'assessment_description'=> $validated['description'],
                'assessment_level'      => $validated['level'],
                'total_questions'       => $validated['total_questions'],
                'passing_questions'     => $validated['passing_questions'],
                'passing_percentage'    => $validated['passing_percentage'],
                'trainer_id'            => $trainer->id,
                'material_id'           => null,
            ]);

            foreach ($validated['questions'] as $questionData) {
                $question = AssessmentQuestion::create([
                    'trainer_id'     => $trainer->id,
                    'assessment_id'  => $assessment->id,
                    'questions_title'=> $questionData['text'],
                ]);

                foreach ($questionData['options'] as $option) {
                    AssessmentOption::create([
                        'trainer_id'     => $trainer->id,
                        'assessment_id'  => $assessment->id,
                        'question_id'    => $question->id,
                        'options'        => $option['text'],
                        'correct_option' => $option['correct'],
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Assessment created successfully.',
                'data'    => $assessment->load('questions.options'), // Load nested if defined in model
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create assessment.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

   public function trainingAllList($trainer_id)
    {
        try {
            $recordedTrainings = TrainingMaterial::select(
                    'id',
                    'trainer_id',
                    'training_type',
                    'training_title',
                    'training_level',
                    'training_price',
                    'training_offer_price',
                    'thumbnail_file_path as image'
                )
                ->where('trainer_id', $trainer_id)
                ->where('training_type','!=', 'recorded')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Trainer training list fetched successfully.',
                'data'    => $recordedTrainings,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while fetching training list.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }


    public function recordedTrainingAllList($trainer_id)
    {
        try {
            $recordedTrainings = TrainingMaterial::select(
                    'id',
                    'trainer_id',
                    'training_type',
                    'training_title',
                    'training_level',
                    'training_price',
                    'training_offer_price',
                    'thumbnail_file_path as image'
                )
                ->where('trainer_id', $trainer_id)
                ->where('training_type', 'recorded')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Trainer training list fetched successfully.',
                'data'    => $recordedTrainings,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while fetching training list.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function assessmentLists($trainerId)
    {
        try {
            
            $assessments = TrainerAssessment::where('trainer_id', $trainerId)->get();
            $courses = TrainingMaterial::where('trainer_id', $trainerId)->get();

            return response()->json([
                'success' => true,
                'message' => 'Assessment and training material list fetched successfully.',
                'data' => [
                    'assessments' => $assessments,
                    'courses' => $courses
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch assessment list.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function assignTrainingMaterialToCourse(Request $request)
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'assessment_id' => 'required|exists:trainer_assessments,id',
                'course_id'     => 'required|exists:training_materials,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first(), // ✅ Returns only the first error
                ], 422);
            }

            // Find and update the assessment
            $assessment = TrainerAssessment::find($request->assessment_id);
            $assessment->material_id = $request->course_id;
            $assessment->save();

            return response()->json([
                'success' => true,
                'message' => 'Course assigned successfully.'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to assign course.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function trainingBatchList($trainingMaterialId)
    {
        try {
            $batches = TrainingBatch::where('trainer_id', $trainingMaterialId)
                ->with('trainingMaterial:id,id,session_type,training_title')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Batches fetched successfully.',
                'data' => $batches,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch batches.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function deleteAdditionalFile($type, $trainerId)
    {
        try {
            $userId = $trainerId;

            $file = AdditionalInfo::where('user_id', $userId)
                ->where('doc_type', $type)
                ->where('user_type', 'trainer')
                ->first();

            if ($file) {
                // Convert public asset URL to local file path
                $publicPath = str_replace(asset('') . '/', '', $file->document_path);
                $filePath = public_path($publicPath);

                if (file_exists($filePath)) {
                    unlink($filePath); // Delete the file from storage
                }

                $file->delete(); // Remove DB record

                return response()->json([
                    'status' => 'success',
                    'message' => ucfirst(str_replace('_', ' ', $type)) . ' deleted successfully.',
                    'type' => $type
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => ucfirst(str_replace('_', ' ', $type)) . ' not found.'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong while deleting the file.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function editTrainingRecordedData($trainerMaterialId)
    {
        try {
            $recordedTrainings = TrainingMaterial::select(
                    'id',
                    'trainer_id',
                    'training_type',
                    'training_title',
                    'training_level',
                    'training_title',
                    'training_sub_title',
                    'training_descriptions',
                    'training_category',
                    'training_objective',
                    'session_type',
                    'training_price',
                    'training_offer_price',
                    'thumbnail_file_path as image'
                )->with('trainingMaterialDocuments')
                ->where('id', $trainerMaterialId)
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Recorded training material list fetched successfully.',
                'data'    => $recordedTrainings,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while fetching training list.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function editTrainingOnlineData($trainerMaterialId)
    {
        try {
            $recordedTrainings = TrainingMaterial::select(
                    'id',
                    'trainer_id',
                    'training_type',
                    'training_title',
                    'training_sub_title',
                    'training_descriptions',
                    'training_category',
                    'training_objective',
                    'session_type',
                    'training_level',
                    'training_price',
                    'training_offer_price',
                    'thumbnail_file_path as image'
                )
                ->with('batches')
                ->where('id', $trainerMaterialId)
                ->where('training_type','!=', 'recorded')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Trainer training list fetched successfully.',
                'data'    => $recordedTrainings,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while fetching training list.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

}