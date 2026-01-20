<!-- Sidebar -->
<aside
    class="w-64 bg-white dark:bg-[#1f1616] border-r border-[#e6dbdb] dark:border-slate-800 hidden lg:flex flex-col h-full">
    <div class="p-6 flex flex-col gap-8 h-full overflow-y-auto">
        <div class="flex items-center gap-3">
            <div
                class="size-10 rounded-xl bg-gradient-to-br from-primary to-red-900 flex items-center justify-center text-white shadow-lg shadow-primary/20">
                <span class="material-symbols-outlined">school</span>
            </div>
            <div class="flex flex-col">
                <h1 class="text-[#111218] dark:text-white text-base font-bold leading-tight">SIAKAD Uni</h1>
                <p class="text-[#616889] dark:text-slate-400 text-xs font-normal">Lecturer Portal</p>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex flex-col gap-1 grow">

                <p class="text-sm font-medium">Profil</p>
            </a>

            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf

                    <p class="text-sm font-medium">Logout</p>
                </button>
            </form>
        </nav>
    </div>
</aside>