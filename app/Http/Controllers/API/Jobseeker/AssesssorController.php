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
    public function quizDetailsByTrainerId($trainerId){
        $selectedQuiz = TrainerAssessment::select('id','trainer_id','assessment_title', 'assessment_description','assessment_level')->where('trainer_id', $trainerId)->first();
        return $this->successResponse($selectedQuiz, 'Quiz detail fetched successfully.');
    }

    public function quizFaqList(Request $request)
    {
        $request->validate([
            'training_id'    => 'required|integer',
            'trainer_id'     => 'required|integer',
            'jobseeker_id'   => 'required|integer',
            'assessment_id'  => 'required|integer'
        ]);
        $jobseeker_id = $request->jobseeker_id ;
        $training_id = $request->training_id ;
        $trainer_id = $request->trainer_id ;
        $assessment_id = $request->assessment_id ;
        // POST API selected answers by user for this assessment
        $selectedAnswers = AssessmentJobseekerData::where('assessment_id', $assessment_id)
            ->where('trainer_id', $trainer_id)
            ->where('training_id', $training_id)
            ->where('jobseeker_id', $jobseeker_id)
            ->pluck('selected_answer', 'question_id'); // [question_id => answer_id]

        // Fetch all questions with options
        $questions = AssessmentQuestion::select('id', 'assessment_id', 'questions_title')->with(['options' => function ($query) use ($selectedAnswers) {
            $query->select('id', 'question_id', 'options'); // select needed columns only
        }])
        ->where('assessment_id', $assessment_id)
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


    public function quizNavigatorList(Request $request)
    {
        $request->validate([
            'training_id'    => 'required|integer',
            'trainer_id'     => 'required|integer',
            'jobseeker_id'   => 'required|integer',
            'assessment_id'  => 'required|integer'
        ]);
        $jobseeker_id = $request->jobseeker_id ;
        $training_id = $request->training_id ;
        $trainer_id = $request->trainer_id ;
        $assessment_id = $request->assessment_id ;

        // Get selected answers by user for this assessment
        $selectedAnswers = AssessmentJobseekerData::where('assessment_id', $assessment_id)
            ->where('trainer_id', $trainer_id)
            ->where('training_id', $training_id)
            ->where('jobseeker_id', $jobseeker_id)
            ->pluck('selected_answer', 'question_id'); // [question_id => answer_id]

        // Fetch all questions with options
        $questions = AssessmentQuestion::select('id', 'assessment_id', 'questions_title')->with(['options' => function ($query) use ($selectedAnswers) {
            $query->select('id', 'question_id', 'options'); // select needed columns only
        }])
        ->where('assessment_id', $assessment_id)
        ->get();

        // Append 'is_selected' flag to each option
        $questions->transform(function ($question) use ($selectedAnswers) {
            $question->is_selected = isset($selectedAnswers[$question->id]);
            unset($question->options); // remove options field from output
            return $question;
        });

        return $this->successResponse($questions, 'Assessor quiz list fetched successfully.');
    }

   public function quizScorecardResult(Request $request)
    {
        $request->validate([
            'training_id'    => 'required|integer',
            'trainer_id'     => 'required|integer',
            'jobseeker_id'   => 'required|integer',
            'assessment_id'  => 'required|integer'
        ]);
        $jobseeker_id = $request->jobseeker_id ;
        $training_id = $request->training_id ;
        $trainer_id = $request->trainer_id ;
        $assessment_id = $request->assessment_id ;

        // Get selected and correct answers by user for this assessment
        $selectedAnswersRaw = AssessmentJobseekerData::where('assessment_id', $assessment_id)
            ->where('trainer_id', $trainer_id)
            ->where('training_id', $training_id)
            ->where('jobseeker_id', $jobseeker_id)
            ->select('question_id', 'selected_answer', 'correct_answer')
            ->get();

        // Count correct answers
        $correctAnswerCount = $selectedAnswersRaw->where('selected_answer', '=', 'correct_answer')->count(); // This compares keys, not values

        // Better way using custom comparison
        $correctAnswerCount = $selectedAnswersRaw->filter(function ($item) {
            return $item->selected_answer == $item->correct_answer;
        })->count();

        // Get passing question count from the trainer_assessments table
        $assessmentDetails = TrainerAssessment::where('id', $assessment_id)
            ->select('passing_questions')
            ->first();

        $passingQuestions = $assessmentDetails->passing_questions ?? 0;

        // Check if passed
        $is_pass = $correctAnswerCount >= $passingQuestions;

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
            ->where('assessment_id', $assessment_id)
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

        return $this->successWithCmsResponse($questions, ['is_pass' => $is_pass], 'Quiz data fetched successfully.');        
    }
}
