<x-layout>
    <div class="bg-blue-900 h-24 px-4 mb-4 flex justify-center items-center rounded">
        <x-search />
        @if (request()->has('keyword') || request()->has('location'))
            <a href="{{ route('jobs.index') }}"
                class="bg-grey-700 hover:bg-grey-600 text-white px-4 py-3 focus:outline-none"><i class="fas fa-arrow-left"></i> Back</a>
        @endif
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        @foreach ($jobs as $job)
            <x-job-listing :job="$job" />
        @endforeach
    </div>
    {{ $jobs->links() }}
</x-layout>
