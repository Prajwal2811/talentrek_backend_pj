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
                    <div class="container py-4">
                        <h3 class="mb-4">Activity Log</h3>
                        <div class="mb-4">
                            <div class="input-group">
                                <input type="text" id="logSearch" class="form-control" placeholder="Search logs...">
                            </div>
                        </div>

                        @if(count($logs) > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover align-middle">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Timestamp</th>
                                            <th>Details</th>
                                        </tr>
                                    </thead>
                                    <tbody id="logTableBody">
                                        @foreach($logs as $log)
                                            <tr>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($log['timestamp'])->format('jS F Y') }} |
                                                    {{ \Carbon\Carbon::parse($log['timestamp'])->format('H:i:s') }}
                                                </td>
                                                <td>
                                                    @php $data = $log['data']; @endphp

                                                    @if($log['message'] === 'Admin login successful')
                                                        <strong>Email:</strong> {{ $data['email'] ?? '' }} <br>
                                                        <strong>Time:</strong> {{ $data['time'] ?? '' }}

                                                    @elseif($log['message'] === 'Jobseekers assigned to admin')
                                                        <strong>Assigned By:</strong> {{ $data['assigned_by']['name'] ?? '' }} ({{ $data['assigned_by']['email'] ?? '' }})<br>
                                                        <strong>Assigned To:</strong> {{ $data['assigned_to_admin']['name'] ?? '' }} ({{ $data['assigned_to_admin']['email'] ?? '' }})<br>
                                                        <strong>Total Assigned:</strong> {{ $data['total_assigned'] ?? '' }}<br>
                                                        <strong>Jobseekers:</strong>
                                                        <ul>
                                                            @foreach($data['jobseekers_assigned'] ?? [] as $js)
                                                                <li>{{ $js['name'] }} ({{ $js['email'] }})</li>
                                                            @endforeach
                                                        </ul>

                                                    @elseif($log['message'] === 'Jobseeker status updated')
                                                        <strong>Jobseeker:</strong> {{ $data['jobseeker']['name'] ?? '' }} ({{ $data['jobseeker']['email'] ?? '' }})<br>
                                                        <strong>Old Status:</strong> {{ $data['jobseeker']['old_status'] ?? '' }}<br>
                                                        <strong>New Status:</strong> {{ $data['jobseeker']['new_status'] ?? '' }}<br>
                                                        @if(!empty($data['jobseeker']['new_reason']))
                                                            <strong>Reason:</strong> {{ $data['jobseeker']['new_reason'] }}
                                                        @endif
                                                        <br>
                                                        <strong>Changed By:</strong> {{ $data['changed_by']['name'] ?? '' }} ({{ $data['changed_by']['role'] ?? '' }})

                                                    @elseif($log['message'] === 'Jobseeker admin status updated')
                                                        <strong>Jobseeker:</strong> {{ $data['jobseeker']['name'] ?? '' }} ({{ $data['jobseeker']['email'] ?? '' }})<br>
                                                        <strong>Old Admin Status:</strong> {{ $data['jobseeker']['previous_status'] ?? 'N/A' }}<br>
                                                        <strong>New Admin Status:</strong> {{ $data['jobseeker']['new_status'] ?? '' }}<br>
                                                        <strong>Updated By:</strong> {{ $data['updated_by']['name'] ?? '' }} ({{ $data['updated_by']['role'] ?? '' }})

                                                    @else
                                                        <pre>{{ json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-warning">No activity logs found.</div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('logSearch');
        const tableRows = document.querySelectorAll('#logTableBody tr');

        searchInput.addEventListener('input', function () {
            const query = this.value.toLowerCase();

            tableRows.forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(query) ? '' : 'none';
            });
        });
    });
</script>

    @include('admin.componants.footer')