@extends('layouts.app')

@section('title', 'Kelas Saya')

@push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        .active-nav {
            background-color: var(--color-primary);
            color: white !important;
        }
    </style>
@endpush

@section('content')
    <div class="flex flex-col gap-8 max-w-[1200px] mx-auto w-full flex-1">

                    </div>
                </div>
            @endforeach
        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>

                if (window.innerWidth >= 768) {
                    const modal = document.getElementById('absensiModal');

                }
                return false;
            }

            function closeAbsensiModal() {
                const modal = document.getElementById('absensiModal');
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');

        </script>
    @endpush
@endsection