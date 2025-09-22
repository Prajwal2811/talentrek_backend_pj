

<div class="row">
    <div class="col-12 ml-auto mr-auto text-center" style="margin: auto">
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" id="successAlert">
                <strong>Success!</strong> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                </button>
            </div>
        @endif
    </div>
    <div class="col-12 ml-auto mr-auto text-center" style="margin: auto">
        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" id="errorAlert">
                <strong>Oops!</strong> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                </button>
            </div>
        @endif
    </div>
    <div class="col-12 ml-auto mr-auto text-center" style="margin: auto">
        @if ($errors->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" id="errorAlert">
                <strong>Oops!</strong> {{ $errors->first('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                </button>
            </div>
        @endif
    </div>
    
    <!-- Success message alert -->
    <div id="success-message" class="col-12 ml-auto mr-auto text-center alert alert-success alert-dismissible fade show" style="display: none;">
        <strong>Success!</strong> <span class="message-text"></span>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <!-- Error message alert -->
    <div id="error-message" class="col-12 ml-auto mr-auto text-center alert alert-danger alert-dismissible fade show" style="display: none;">
        <strong>Oops!</strong> <span class="message-text"></span>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</div>

<script>
    // Automatically hide alerts after 3 seconds
    setTimeout(() => {
        const successAlert = document.getElementById('successAlert');
        const errorAlert = document.getElementById('errorAlert');

        if (successAlert) {
            successAlert.classList.add('fade');
            setTimeout(() => successAlert.remove(), 500); // Remove from DOM after fade
        }
        if (errorAlert) {
            errorAlert.classList.add('fade');
            setTimeout(() => errorAlert.remove(), 500); // Remove from DOM after fade
        }
    }, 3000);
</script>