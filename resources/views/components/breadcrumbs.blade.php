                                    <nav class="text-sm breadcrumb">
    @hasSection('breadcrumbs')
        @yield('breadcrumbs')
    @else
        @php
            $homeUrl = Route::has('admin.dashboard') ? route('admin.dashboard') : url('/');
            $segments = request()->segments();
            // Remove leading 'admin' to keep breadcrumbs concise
            if (!empty($segments) && $segments[0] === 'admin') {
                array_shift($segments);
            }

            // Action label map (english/alias => Indonesian verb)
            $actionLabels = [
                'edit' => 'Edit',
                'update' => 'Edit',
                'create' => 'Tambah',
                'new' => 'Tambah',
                'show' => 'Lihat',
                'detail' => 'Lihat'
            ];
        @endphp

        <a href="{{ $homeUrl }}" class="mr-2 muted">Home</a>

        @if(empty($segments))
            <i class="fas fa-chevron-right text-xs mr-2"></i>
            <span class="font-semibold">@yield('page-title', trim($__env->yieldContent('title')) ?: 'Dashboard')</span>
        @else
            @php
                $acc = '';
                $rendered = false;
            @endphp

            @for($i = 0; $i < count($segments); $i++)
                @php
                    $segment = $segments[$i];
                    $isLast = ($i === count($segments) - 1);
                    // accumulate path for links
                    $acc .= '/' . $segment;
                @endphp

                @if(is_numeric($segment))
                    {{-- Skip numeric IDs from breadcrumb display --}}
                    @continue
                @endif

                {{-- If last segment is an action like edit/create and previous segment exists (resource) --}}
                @if($isLast && array_key_exists(strtolower($segment), $actionLabels))
                    @php
                        // find previous non-numeric segment to use as resource (skip IDs)
                        $resource = null;
                        for ($k = $i - 1; $k >= 0; $k--) {
                            if (!is_numeric($segments[$k])) { $resource = $segments[$k]; break; }
                        }
                        if ($resource) {
                            $label = $actionLabels[strtolower($segment)] . ' ' . ucwords(str_replace(['-','_'], ' ', $resource));
                        } else {
                            $label = $actionLabels[strtolower($segment)];
                        }
                        $rendered = true;
                    @endphp
                    <i class="fas fa-chevron-right text-xs mr-2"></i>
                    <span class="font-semibold">{{ $label }}</span>
                    @break
                @endif

                {{-- Normal rendering: if last, plain text; otherwise link --}}
                <i class="fas fa-chevron-right text-xs mr-2"></i>
                @if($isLast)
                    <span class="font-semibold">{{ ucwords(str_replace(['-','_'], ' ', $segment)) }}</span>
                @else
                    <a href="{{ url($acc) }}" class="mr-2 muted">{{ ucwords(str_replace(['-','_'], ' ', $segment)) }}</a>
                @endif
            @endfor

            {{-- Edge case: if loop only skipped numeric id and we still have last segment numeric (e.g., /dosen/1) show resource name --}}
            @if(! $rendered)
                @php
                    // Find the last non-numeric segment to display if nothing rendered
                    $lastLabel = null;
                    for($j = count($segments)-1; $j >= 0; $j--) {
                        if(!is_numeric($segments[$j])) { $lastLabel = $segments[$j]; break; }
                    }
                @endphp
                @if($lastLabel)
                    {{-- If the last non-numeric is the same as home (empty), use title fallback --}}
                    @if(strtolower($lastLabel) === 'home')
                        {{-- nothing --}}
                    @else
                        {{-- already rendered as part of loop if needed; this is a safeguard --}}
                    @endif
                @endif
            @endif
        @endif
    @endif
</nav>
