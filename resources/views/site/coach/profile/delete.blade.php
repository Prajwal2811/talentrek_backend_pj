<!-- Delete Account Section -->
                                <div x-show="activeSection === 'delete'" x-transition>
                                    <h3 class="text-xl font-semibold mb-4 text-red-600">Delete Account</h3>
                                    <p>This action is irreversible. Are you sure you want to delete your account?</p>
                                    <!-- <button class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Delete Account</button> -->
                                    <form id="
                                    " action="{{ route('coach.destroy') }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" id="deleteAccountBtn" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                                            Delete Account
                                        </button>
                                    </form>
                                </div>