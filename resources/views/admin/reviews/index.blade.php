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
                <div class="container-fluid">
                    @include('admin.errors')
                    <div class="block-header">
                        <div class="row clearfix">
                            <div class="col-xl-5 col-md-5 col-sm-12">
                                <h1>Hi, {{  Auth()->user()->name }}!</h1>
                                <span>JustDo Review Management,</span>
                            </div>
                            <div class="col-xl-7 col-md-7 col-sm-12 text-md-right">

                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="header">
                                    <h2>Review Management</h2>
                                </div>
                                <div class="body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover dataTable js-exportable">
                                            <thead>
                                                <tr>
                                                    <th>Sr. No.</th>
                                                    <th>Reviewer</th>
                                                    <th>Reviewee Type</th>
                                                    <th>Reviewee</th>
                                                    <th>Rating</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th>Sr. No.</th>
                                                    <th>Reviewer</th>
                                                    <th>Reviewee Type</th>
                                                    <th>Reviewee</th>
                                                    <th>Action</th>
                                                </tr>
                                            </tfoot>
                                            <tbody>
                                                <?php
                                                foreach ($reviews as $review) {
                                                    switch ($review->user_type) {
                                                        case 'trainer':
                                                            $reviewee = App\Models\Trainers::find($review->user_id);
                                                            break;
                                                        case 'mentor':
                                                            $reviewee = App\Models\Mentors::find($review->user_id);
                                                            break;
                                                        case 'coach':
                                                            $reviewee = App\Models\Coach::find($review->user_id);
                                                            break;
                                                        case 'assessor':
                                                            $reviewee = App\Models\Assessors::find($review->user_id);
                                                            break;
                                                        default:
                                                            $reviewee = null;
                                                    }

                                                    $review->reviewee_name = $reviewee?->name ?? 'N/A';
                                                }
                                               ?>


                                                @foreach($reviews as $review)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $review->reviewer_name }}</td>
                                                        <td>{{ ucfirst($review->user_type) }}</td>
                                                        <td>{{ $review->reviewee_name }}</td>
                                                        <td>{{ $review->ratings }}/5</td>
                                                        <td>
                                                            <a href="{{ route('admin.reviews.view', $review->review_id) }}" class="btn btn-sm btn-primary">View Review</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('admin.componants.footer')