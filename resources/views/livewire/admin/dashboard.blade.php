<div class="py-8">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            <!-- Posts Card -->
            <div class="bg-gradient-to-br from-blue-50 to-white border border-blue-100 rounded-2xl p-6 shadow hover:shadow-md transition-all duration-300">
                <div class="flex items-center space-x-4">
                    <div class="bg-blue-200 text-blue-800 p-3 rounded-full shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-md font-semibold text-gray-600">Total Posts</h4>
                        <p class="text-3xl font-bold text-blue-800 mt-1">{{ $postCount }}</p>
                    </div>
                </div>
            </div>

            <!-- Pages Card -->
            <div class="bg-gradient-to-br from-green-50 to-white border border-green-100 rounded-2xl p-6 shadow hover:shadow-md transition-all duration-300">
                <div class="flex items-center space-x-4">
                    <div class="bg-green-200 text-green-800 p-3 rounded-full shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-md font-semibold text-gray-600">Total Pages</h4>
                        <p class="text-3xl font-bold text-green-800 mt-1">{{ $pageCount }}</p>
                    </div>
                </div>
            </div>

            <!-- Categories Card -->
            <div class="bg-gradient-to-br from-yellow-50 to-white border border-yellow-100 rounded-2xl p-6 shadow hover:shadow-md transition-all duration-300">
                <div class="flex items-center space-x-4">
                    <div class="bg-yellow-200 text-yellow-800 p-3 rounded-full shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M20 13V7a2 2 0 00-2-2h-4l-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2h8" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-md font-semibold text-gray-600">Total Categories</h4>
                        <p class="text-3xl font-bold text-yellow-800 mt-1">{{ $categoryCount }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
