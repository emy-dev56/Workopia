<x-layout>
    <h2 class="text-center text-3xl mb-4 font-bold border border-gray-300 p-3">
        Saved Jobs
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        @forelse ($bookmarks as $bookmark)
         <x-job-listing :job="$bookmark" />
        @empty
         <p class="text-center text-sm color-gray-500">No bookmarks found</p>
        @endforelse
    </div>
</x-layout>
