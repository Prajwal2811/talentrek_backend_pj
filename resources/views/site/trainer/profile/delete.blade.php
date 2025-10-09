<!-- Delete Account Section -->
<div x-show="activeSection === 'delete'" x-transition
    class="bg-white p-6 rounded-lg border border-red-200 shadow-md mt-4">
    <h3 class="text-xl font-semibold mb-3 text-red-600">Delete Account</h3>

    <p class="text-gray-700 leading-relaxed mb-4">
        This action is <span class="font-semibold text-red-700">irreversible</span>.
        Are you sure you want to permanently delete your account?
    </p>

    <form id="deleteAccountForm" action="{{ route('trainer.destroy') }}" method="POST">
        @csrf
        @method('DELETE')

        <button type="button" id="deleteAccountBtn"
            class="bg-red-600 text-white px-5 py-2.5 rounded hover:bg-red-700 transition-colors duration-200">
            Delete Account
        </button>
    </form>
</div>



<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.getElementById('deleteAccountBtn').addEventListener('click', function (e) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This action will permanently delete your account!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteAccountForm').submit();
            }
        });
    });
</script>