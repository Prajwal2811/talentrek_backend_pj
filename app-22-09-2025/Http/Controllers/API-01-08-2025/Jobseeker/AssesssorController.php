<?php

namespace App\Http\Controllers\API\Jobseeker;

use App\Models\Api\AssessmentQuestion;
use App\Models\Api\Assessors;
use App\Models\Api\AssessmentOption;
use App\Models\Api\AssessmentJobseekerData;
use App\Models\Api\TrainerAssessment;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use DB;

class AssesssorController extends Controller
{
    use ApiResponse;
    
    public function index()
    {
        return view('home');
    }
    public function quizDetailsByTrainerId($trainerId)
    {
        try {
            $selectedQuiz = TrainerAssessment::select('id', 'trainer_id', 'assessment_title', 'assessment_description', 'assessment_level')
                ->where('trainer_id', $trainerId)
                ->first();

            if (!$selectedQuiz) {
                return $this->successResponse(null, 'No quiz found for the given trainer ID.');
            }

            return $this->successResponse($selectedQuiz, 'Quiz detail fetched successfully.');
            
        } catch (\Exception $e) {
            return $this->errorResponse('Something went wrong while fetching quiz details.', 500, [
                'error' => $e->getMessage()
            ]);
        }
    }


    public function submitQuizAnswer(Request $request)
    {
        $request->validate([
            'training_id'    => 'required|integer',
            'trainer_id'     => 'required|integer',
            'jobseeker_id'   => 'required|integer',
            'assessment_id'  => 'required|integer',
            'question_id'    => 'required|integer',
            'selected_answer'=> 'required|integer',
        ]);

        // Optional: You may fetch correct_answer from the options table
        $correctAnswerId = AssessmentOption::where('question_id', $request->question_id)
            ->where('correct_option', 1) // Assuming a boolean column
            ->value('id');
       
        $data = [
            'training_id'      => $request->training_id,
            'trainer_id'       => $request->trainer_id,
            'jobseeker_id'     => $request->jobseeker_id,
            'assessment_id'    => $request->assessment_id,
            'question_id'      => $request->question_id,
            'selected_answer'  => $request->selected_answer,
            'correct_answer'   => $correctAnswerId ?? null,
        ];

        // Update or create based on composite keys
        AssessmentJobseekerData::updateOrCreate(
            [
                'assessment_id' => $request->assessment_id,
                'jobseeker_id'  => $request->jobseeker_id,
                'question_id'   => $request->question_id,
            ],
            $data
        );

        return $this->successResponse(null, 'Answer submitted successfully.');
    }
    
    public function quizFaqList(Request $request)
    {
        try {
            $request->validate([
                'training_id'    => 'required|integer',
                'trainer_id'     => 'required|integer',
                'jobseeker_id'   => 'required|integer',
                'assessment_id'  => 'required|integer'
            ]);

            $jobseeker_id   = $request->jobseeker_id;
            $training_id    = $request->training_id;
            $trainer_id     = $request->trainer_id;
            $assessment_id  = $request->assessment_id;

            // Fetch user's selected answers
            $selectedAnswers = AssessmentJobseekerData::where('assessment_id', $assessment_id)
                ->where('trainer_id', $trainer_id)
                ->where('training_id', $training_id)
                ->where('jobseeker_id', $jobseeker_id)
                ->pluck('selected_answer', 'question_id');

            // Fetch all assessment questions with their options
            $questions = AssessmentQuestion::select('id', 'assessment_id', 'questions_title')
                ->with(['options' => function ($query) {
                    $query->select('id', 'question_id', 'options');
                }])
                ->where('assessment_id', $assessment_id)
                ->get();

            // Mark selected answers
            $questions->transform(function ($question) use ($selectedAnswers) {
                foreach ($question->options as $option) {
                    $option->is_selected = isset($selectedAnswers[$question->id]) && $selectedAnswers[$question->id] == $option->id;
                }
                return $question;
            });

            return $this->successResponse($questions, 'Assessor quiz list fetched successfully.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse('Validation failed.', 422, $e->errors());
        } catch (\Exception $e) {
            return $this->errorResponse('Something went wrong while fetching quiz questions.', 500, [
                'error' => $e->getMessage()
            ]);
        }
    }
 

    public function quizNavigatorList(Request $request)
    {
        try {
            $request->validate([
                'training_id'    => 'required|integer',
                'trainer_id'     => 'required|integer',
                'jobseeker_id'   => 'required|integer',
                'assessment_id'  => 'required|integer',
            ]);

            $jobseekerId   = $request->jobseeker_id;
            $trainingId    = $request->training_id;
            $trainerId     = $request->trainer_id;
            $assessmentId  = $request->assessment_id;

            // Get user's selected answers
            $selectedAnswers = AssessmentJobseekerData::where('assessment_id', $assessmentId)
                ->where('trainer_id', $trainerId)
                ->where('training_id', $trainingId)
                ->where('jobseeker_id', $jobseekerId)
                ->pluck('selected_answer', 'question_id');

            // Fetch questions (without options in response)
            $questions = AssessmentQuestion::select('id', 'assessment_id', 'questions_title')
                ->with(['options' => function ($query) {
                    $query->select('id', 'question_id'); // Only used to check existence, not returned
                }])
                ->where('assessment_id', $assessmentId)
                ->get();

            // Mark which questions have a selected answer
            $questions->transform(function ($question) use ($selectedAnswers) {
                $question->is_selected = isset($selectedAnswers[$question->id]);
                unset($question->options); // Optional: Remove if not needed in response
                return $question;
            });

            return $this->successResponse($questions, 'Quiz navigator data fetched successfully.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse('Validation failed.', 422, $e->errors());
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to fetch quiz navigator data.', 500, [
                'error' => $e->getMessage()
            ]);
        }
    }


   public function quizScorecardResult(Request $request)
    {
        try {
            $request->validate([
                'training_id'    => 'required|integer',
                'trainer_id'     => 'required|integer',
                'jobseeker_id'   => 'required|integer',
                'assessment_id'  => 'required|integer',
            ]);

            $jobseekerId  = $request->jobseeker_id;
            $trainingId   = $request->training_id;
            $trainerId    = $request->trainer_id;
            $assessmentId = $request->assessment_id;

            // Get user's submitted answers and correct answers
            $submittedAnswers = AssessmentJobseekerData::where('assessment_id', $assessmentId)
                ->where('trainer_id', $trainerId)
                ->where('training_id', $trainingId)
                ->where('jobseeker_id', $jobseekerId)
                ->select('question_id', 'selected_answer', 'correct_answer')
                ->get();

            // Count how many selected answers are correct
            $correctAnswerCount = $submittedAnswers->filter(function ($item) {
                return $item->selected_answer == $item->correct_answer;
            })->count();

            // Fetch passing threshold from assessment settings
            $passingQuestions = TrainerAssessment::where('id', $assessmentId)
                ->value('passing_questions') ?? 0;

            // Determine pass/fail
            $isPass = $correctAnswerCount >= $passingQuestions;

            // Prepare answer lookup array: [question_id => ['selected' => ..., 'correct' => ...]]
            $answerMap = $submittedAnswers->keyBy('question_id')->map(function ($item) {
                return [
                    'selected' => $item->selected_answer,
                    'correct'  => $item->correct_answer,
                ];
            });

            // Fetch all questions and append result info
            $questions = AssessmentQuestion::select('id', 'assessment_id', 'questions_title')
                ->with(['options' => function ($query) {
                    $query->select('id', 'question_id', 'options');
                }])
                ->where('assessment_id', $assessmentId)
                ->get()
                ->transform(function ($question) use ($answerMap) {
                    $question->is_selected = isset($answerMap[$question->id]);
                    $question->is_correct_answer = false;

                    if ($question->is_selected) {
                        $selected  = $answerMap[$question->id]['selected'];
                        $correct   = $answerMap[$question->id]['correct'];
                        $question->is_correct_answer = $selected == $correct;
                    }

                    unset($question->options); // Optional: Remove if not needed
                    return $question;
                });

            // Final response
            return $this->successWithCmsResponse(
                $questions,
                ['is_pass' => $isPass],
                'Quiz data fetched successfully.'
            );

        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse('Validation failed.', 422, $e->errors());
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to fetch quiz result.', 500, [
                'error' => $e->getMessage()
            ]);
        }
    }

}
