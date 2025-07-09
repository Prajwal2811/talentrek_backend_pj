<?php

namespace App\Http\Controllers\API\Jobseeker;

use App\Models\Api\AssessmentQuestion;
use App\Models\Api\Assessors;
use App\Models\Api\AssessmentOption;
use App\Models\Api\AssessmentJobseekerData;

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

    public function quizFaqList($assessorId , $userId)
    {
        // Get selected answers by user for this assessment
        $selectedAnswers = AssessmentJobseekerData::where('assessment_id', $assessorId)
            ->where('jobseeker_id', $userId)
            ->pluck('selected_answer', 'question_id'); // [question_id => answer_id]

        // Fetch all questions with options
        $questions = AssessmentQuestion::select('id', 'assessment_id', 'questions_title')->with(['options' => function ($query) use ($selectedAnswers) {
            $query->select('id', 'question_id', 'options'); // select needed columns only
        }])
        ->where('assessment_id', $assessorId)
        ->get();

        // Append 'is_selected' flag to each option
        $questions->transform(function ($question) use ($selectedAnswers) {
            foreach ($question->options as $option) {
                $option->is_selected = isset($selectedAnswers[$question->id]) && $selectedAnswers[$question->id] == $option->id;
            }
            return $question;
        });

        return $this->successResponse($questions, 'Assessor quiz list fetched successfully.');
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


    public function quizNavigatorList($assessorId , $userId)
    {
        // Get selected answers by user for this assessment
        $selectedAnswers = AssessmentJobseekerData::where('assessment_id', $assessorId)
            ->where('jobseeker_id', $userId)
            ->pluck('selected_answer', 'question_id'); // [question_id => answer_id]

        // Fetch all questions with options
        $questions = AssessmentQuestion::select('id', 'assessment_id', 'questions_title')->with(['options' => function ($query) use ($selectedAnswers) {
            $query->select('id', 'question_id', 'options'); // select needed columns only
        }])
        ->where('assessment_id', $assessorId)
        ->get();

        // Append 'is_selected' flag to each option
        $questions->transform(function ($question) use ($selectedAnswers) {
            $question->is_selected = isset($selectedAnswers[$question->id]);
            unset($question->options); // remove options field from output
            return $question;
        });

        return $this->successResponse($questions, 'Assessor quiz list fetched successfully.');
    }

   public function quizScorecardResult($assessorId , $userId)
    {
        // Get selected and correct answers by user for this assessment
        $selectedAnswersRaw = AssessmentJobseekerData::where('assessment_id', $assessorId)
            ->where('jobseeker_id', $userId)
            ->select('question_id', 'selected_answer', 'correct_answer')
            ->get();

        // Transform into array: [question_id => ['selected' => ..., 'correct' => ...]]
        $selectedAnswers = $selectedAnswersRaw->keyBy('question_id')->map(function ($item) {
            return [
                'selected' => $item->selected_answer,
                'correct' => $item->correct_answer,
            ];
        });

        // Fetch questions with options (if needed)
        $questions = AssessmentQuestion::select('id', 'assessment_id', 'questions_title')
            ->with(['options' => function ($query) {
                $query->select('id', 'question_id', 'options');
            }])
            ->where('assessment_id', $assessorId)
            ->get();

        // Append flags: is_selected and is_correct_answer
        $questions->transform(function ($question) use ($selectedAnswers) {
            $question->is_selected = isset($selectedAnswers[$question->id]);
            $question->is_correct_answer = false;

            if ($question->is_selected) {
                $question->is_correct_answer = $selectedAnswers[$question->id]['selected'] == $selectedAnswers[$question->id]['correct'];
            }

            unset($question->options); // remove options if not needed
            return $question;
        });

        return $this->successResponse($questions, 'Quiz data fetched successfully.');        
    }
}
