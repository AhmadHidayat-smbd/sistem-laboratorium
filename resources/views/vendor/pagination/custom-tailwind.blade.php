@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
        
        <!-- Mobile View (Simple Previous/Next) -->
        <div class="flex justify-between flex-1 sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center px-4 py-2 text-sm font-bold text-gray-400 bg-white border border-gray-200 cursor-not-allowed rounded-[1rem]">
                    {!! __('pagination.previous') !!}
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-bold text-blue-600 bg-white border border-blue-200 rounded-[1rem] hover:bg-blue-50 hover:border-blue-300 transition-all focus:z-10 focus:ring-4 focus:ring-blue-100">
                    {!! __('pagination.previous') !!}
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-bold text-blue-600 bg-white border border-blue-200 rounded-[1rem] hover:bg-blue-50 hover:border-blue-300 transition-all focus:z-10 focus:ring-4 focus:ring-blue-100">
                    {!! __('pagination.next') !!}
                </a>
            @else
                <span class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-bold text-gray-400 bg-white border border-gray-200 cursor-not-allowed rounded-[1rem]">
                    {!! __('pagination.next') !!}
                </span>
            @endif
        </div>

        <!-- Desktop View -->
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div class="text-sm text-gray-400 font-medium">
                Menampilkan 
                <span class="font-black text-gray-600">{{ $paginator->firstItem() }}</span>
                -
                <span class="font-black text-gray-600">{{ $paginator->lastItem() }}</span>
                dari total
                <span class="font-black text-gray-600">{{ $paginator->total() }}</span>
                mahasiswa
            </div>

            <div>
                <span class="relative z-0 inline-flex shadow-sm rounded-[1rem] p-1 border border-gray-100 bg-gray-50">
                    
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                            <span class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-300 bg-transparent cursor-not-allowed" aria-hidden="true">
                                <i class="ph-bold ph-caret-left"></i>
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 bg-transparent hover:text-blue-600 hover:bg-white rounded-[0.75rem] transition-all focus:z-10 focus:outline-none focus:ring-2 focus:ring-blue-500/20" aria-label="{{ __('pagination.previous') }}">
                            <i class="ph-bold ph-caret-left"></i>
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span class="relative inline-flex items-center px-4 py-2 text-sm font-bold text-gray-400 bg-transparent">{{ $element }}</span>
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="relative inline-flex items-center px-4 py-2 text-sm font-black text-white bg-blue-600 rounded-[0.75rem] shadow-sm shadow-blue-200 cursor-default">{{ $page }}</span>
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2 text-sm font-bold text-gray-500 bg-transparent hover:text-blue-600 hover:bg-white rounded-[0.75rem] transition-all focus:z-10 focus:outline-none focus:ring-2 focus:ring-blue-500/20" aria-label="{{ __('Go to page '.$page) }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 bg-transparent hover:text-blue-600 hover:bg-white rounded-[0.75rem] transition-all focus:z-10 focus:outline-none focus:ring-2 focus:ring-blue-500/20" aria-label="{{ __('pagination.next') }}">
                            <i class="ph-bold ph-caret-right"></i>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                            <span class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-300 bg-transparent cursor-not-allowed" aria-hidden="true">
                                <i class="ph-bold ph-caret-right"></i>
                            </span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
