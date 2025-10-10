<!-- Delete Account Section -->
                                <div x-show="activeSection === 'delete'" x-transition>
                                    <h3 class="text-xl font-semibold mb-4 text-red-600">Delete Account</h3>
                                    <p>This action is irreversible. Are you sure you want to delete your account?</p>
                                    <!-- <button class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Delete Account</button> -->
                                    <form  id="deleteAccountForm"  action="{{ route('mentor.destroy') }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" id="deleteAccountBtn" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                                            Delete Account
                                        </button>
                                    </form>
                                </div>

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