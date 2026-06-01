<x-layout>
    <section class="flex flex-col md:flex-row gap-6">
        <!-- Profile Info -->
        <div class="bg-white p-8 rounded-lg shadow-md w-full md:w-1/2">
            <h3 class="text-3xl text-center font-bold mb-4">
                Profile Info
            </h3>
            @if ($user->avatar)
                <img src="{{ '/storage/' . $user->avatar }}" alt="{{ $user->name }}"
                    class="rounded-full mb-4 m-auto w-32 h-32 object-fit-cover" />
            @endif
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <x-inputs.text id="name" name="name" value="{{ $user->name }}" label="Name" />
                <x-inputs.text id="email" name="email" type="email" value="{{ $user->email }}"
                    label="Email" />
                <x-inputs.file id="avatar" name="avatar" label="Upload Avatar" />
                <button type="submit"
                    class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 my-3 rounded focus:outline-none">
                    Save
                </button>
            </form>
        </div>

        <!-- My Job Listings -->
        <div class="bg-white p-8 rounded-lg shadow-md w-full">
            <h3 class="text-3xl text-center font-bold mb-4">
                My Job Listings
            </h3>
            <!-- Listing 1 -->
            @forelse ($jobs as $job)
                <div class="flex justify-between items-center border-b-2 border-gray-200 py-2">
                    <div>
                        <h3 class="text-xl font-semibold">
                            {{ $job->title }}
                        </h3>
                        <p class="text-gray-700">{{ $job->job_type }}</p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('jobs.edit', $job->id) }}"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm">Edit</a>
                        <form method="POST" action="{{ route('jobs.destroy', $job->id) }}?from=dashboard"
                            onsubmit="return confirm('Are you sure you want to delete this job?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
                @forelse($job->applicants as $applicant)
                    <div class="py-2">
                        <p class="text-gray-900"><strong>Full Name</strong>: {{ $applicant->full_name }}</p>
                        <p class="text-gray-900"><strong>Contact Phone</strong>: {{ $applicant->contact_phone }}</p>
                        <p class="text-gray-900"><strong>Contact Email</strong>: {{ $applicant->contact_email }}</p>
                        <p class="text-gray-900"><strong>Message</strong>: {{ $applicant->message }}</p>
                        <p class="text-gray-900"><strong>Location</strong>: {{ $applicant->location }}</p>
                        <p class="text-gray-900"><a href="{{ asset('storage/' . $applicant->resume_path) }}"
                                class="text-blue-500" download><i class="fas fa-download"></i> Download Resume</a></p>
                        <form method="POST" action="{{ route('applicant.destroy', $applicant->id) }}"
                            onsubmit="return confirm('Are you sure you want to delete this applicant?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="hover:text-red-600 text-red-500">Delete</button>
                        </form>
                    </div>
                @empty
                    <p class="text-gray-700">No applicants found for this job</p>
                @endforelse
            @empty
                <p class="text-gray-700">No jobs found</p>
            @endforelse
        </div>
    </section>
</x-layout>
