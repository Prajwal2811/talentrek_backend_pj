@include('admin.componants.header')

<body data-theme="light">
    <div id="body" class="theme-cyan">
        <div class="themesetting">
        </div>
        <div class="overlay"></div>
        <div id="wrapper">
            @include('admin.componants.navbar')
            @include('admin.componants.sidebar')
            <div id="main-content">
                <div class="container-fluid py-4">
                    <div class="block-header">
                        <div class="row clearfix">
                            <div class="col-xl-5 col-md-5 col-sm-12">
                                <h1>Hi, {{  Auth()->user()->name }}!</h1>
                                <span>JustSee Training Assessment Detail,</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row clearfix">
                        <div class="col-md-12">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="fw-bold">Assessment Details</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Assessment Title</label>
                                            <input type="text" class="form-control" value="{{ $assessment->assessment_title }}" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Assessment Level</label>
                                            <input type="text" class="form-control" value="{{ $assessment->assessment_level }}" readonly>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Total Questions</label>
                                            <input type="text" class="form-control" value="{{ $assessment->total_questions }}" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Passing Questions</label>
                                            <input type="text" class="form-control" value="{{ $assessment->passing_questions }}" readonly>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Passing Percentage</label>
                                            <input type="text" class="form-control" value="{{ $assessment->passing_percentage }}%" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Assinged Course</label>
                                            <input type="text" class="form-control" value="{{ $assessment->course->title ?? 'N/A' }}" readonly>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <label class="form-label">Assessment Description</label>
                                            <textarea class="form-control" rows="3" readonly>{{ $assessment->assessment_description }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Questions and Options --}}
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="fw-bold">Assessment Questions & Answers</h5>
                                </div>
                                <div class="card-body" id="assessment-container">
                                    @foreach($assessment->questions as $index => $question)
                                        <div class="mb-4 border-bottom pb-3 question-block" style="{{ $loop->iteration > 2 ? 'display: none;' : '' }}">
                                            <label class="form-label fw-bold">
                                                <strong>Q{{ $loop->iteration }}. {{ $question->questions_title }}</strong>
                                            </label>

                                            <div class="row">
                                                @foreach($question->options as $optIndex => $option)
                                                    <div class="col-md-6 mb-2">
                                                        <label class="form-label">Option {{ $optIndex + 1 }}</label>

                                                        @if($option->correct_option)
                                                            <input type="text" class="form-control border-success" value="{{ $option->options }}" readonly>
                                                            <small class="text-success">
                                                                <strong>✔ Correct Answer</strong>
                                                            </small>
                                                        @else
                                                            <input type="text" class="form-control" value="{{ $option->options }}" readonly>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach

                                    @if(count($assessment->questions) > 2)
                                        <div class="text-center mt-4">
                                            <button id="loadMoreBtn" class="btn btn-primary">Load More</button>
                                        </div>
                                    @endif
                                </div>
                                <script>
                                    document.addEventListener('DOMContentLoaded', function () {
                                        const loadMoreBtn = document.getElementById('loadMoreBtn');
                                        const questionBlocks = document.querySelectorAll('.question-block');
                                        let visibleCount = 2;

                                        loadMoreBtn?.addEventListener('click', function () {
                                            let hiddenBlocks = Array.from(questionBlocks).slice(visibleCount, visibleCount + 2); // Load 2 at a time
                                            hiddenBlocks.forEach(block => block.style.display = 'block');

                                            visibleCount += 2;

                                            if (visibleCount >= questionBlocks.length) {
                                                loadMoreBtn.style.display = 'none'; // Hide button if all loaded
                                            }
                                        });
                                    });
                                </script>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @include('admin.componants.footer')