 <div x-show="activeSection === 'delete'" x-transition class="bg-white p-6">
                                    <h3 class="text-xl font-semibold mb-4 text-red-600">Delete Account</h3>
                                    <p>This action is irreversible. Are you sure you want to delete your account?</p>
                                    <!-- <button class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Delete Account</button> -->
                                    <form id="deleteAccountForm" action="{{ route('recruiter.destroy') }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" id="deleteAccountBtn"
                                            class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                                            Delete Account
                                        </button>
                                    </form>

                                    <!-- SweetAlert2 CDN -->
                                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                                    <script>
                                        document.getElementById('deleteAccountBtn').addEventListener('click', function () {
                                            Swal.fire({
                                                title: 'Are you sure?',
                                                text: "This action is irreversible. Delete your account?",
                                                icon: 'warning',
                                                showCancelButton: true,
                                                confirmButtonColor: '#d33',
                                                cancelButtonColor: '#3085d6',
                                                confirmButtonText: 'Yes, delete it!',
                                                cancelButtonText: 'No',
                                                width: '350px', // Make it smaller
                                                padding: '1em',
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    document.getElementById('deleteAccountForm').submit();
                                                }
                                            });
                                        });
                                    </script>
                                </div>