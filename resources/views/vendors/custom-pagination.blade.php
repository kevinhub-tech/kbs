@if ($paginator->hasPages())
    <ul class="pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="disabled" aria-disabled="true">
                <span>«</span>
            </li>
        @else
            <li>
                <button type="button" wire:click="previousPage" wire:loading.attr="disabled" rel="prev">«</button>
            </li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="disabled" aria-disabled="true"><span>{{ $element }}</span></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="active" aria-current="page">
                            <span>{{ $page }}</span>
                        </li>
                    @else
                        <li>
                            <button type="button" wire:click="gotoPage({{ $page }})"
                                wire:loading.attr="disabled">{{ $page }}</button>
                        </li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li>
                <button type="button" wire:click="nextPage" wire:loading.attr="disabled" rel="next">»</button>
            </li>
        @else
            <li class="disabled" aria-disabled="true">
                <span>»</span>
            </li>
        @endif
    </ul>
@endif
