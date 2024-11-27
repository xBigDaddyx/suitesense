<header class="absolute top-0 left-0 right-0 flex items-center justify-between p-4">
    <!-- Logo on the Left -->
    <div class="flex items-center ml-8">
        <!-- Your SVG Logo -->

        <svg class="h-8 text-white" xmlns="http://www.w3.org/2000/svg" xml:space="preserve" version="1.1"
            shape-rendering="geometricPrecision" text-rendering="geometricPrecision" image-rendering="optimizeQuality"
            fill-rule="evenodd" clip-rule="evenodd" viewBox="0 0 51900 16154" xmlns:xlink="http://www.w3.org/1999/xlink"
            xmlns:xodm="http://www.corel.com/coreldraw/odm/2003">
            <defs>
                <linearGradient id="id0" gradientUnits="userSpaceOnUse" x1="23383" y1="17938"
                    x2="28517" y2="-1784">
                    <stop offset="0" stop-opacity="1" stop-color="#7A5DC7" />
                    <stop offset="1" stop-opacity="1" stop-color="#7A5DC7" />
                </linearGradient>
            </defs>
            <g id="Layer_x0020_1">
                <metadata id="CorelCorpID_0Corel-Layer" />
                <path fill="url(#id0)" fill-rule="nonzero"
                    d="M44705 16154l2307 0 581 -1460 4307 -10835 -2440 0 -2175 5428 -2138 -5428 -2440 0 3424 8701 -1426 3594zm-7523 -3594l2365 0 0 -6904 1967 0 0 -1797 -1967 0 0 -643c0,-738 397,-1116 1135,-1116l832 0 0 -1835 -1570 0c-1835,0 -2762,984 -2762,2818l0 776 -1134 0 0 1797 1134 0 0 6904zm-10118 -12295l2364 0 0 3594 1854 0 0 1797 -1854 0 0 3953c0,738 398,1116 1135,1116l833 0 0 1835 -1570 0c-1835,0 -2762,-984 -2762,-2819l0 -4085 -1135 0 0 -1797 1135 0 0 -3594zm5408 12295l2368 0 19 -8701 -2387 0 0 8701zm1194 -9723c624,0 1305,-491 1305,-1324 0,-813 -681,-1248 -1305,-1248 -643,0 -1306,435 -1306,1248 0,833 663,1324 1306,1324zm-11311 9723l2367 0 19 -8701 -2386 0 0 8701zm1193 -9723c624,0 1305,-491 1305,-1324 0,-813 -681,-1248 -1305,-1248 -643,0 -1305,435 -1305,1248 0,833 662,1324 1305,1324zm-7170 9874c851,0 1759,-246 2421,-946l0 795 2365 0 0 -8701 -2365 0 0 4956c0,1532 -1021,1891 -1702,1891 -681,0 -1759,-359 -1759,-1891l0 -4956 -2365 0 0 5334c0,2667 1835,3518 3405,3518zm-4594 -9884l-2317 -2317 -2316 2317 2316 2316 2317 -2316zm-11784 0l2827 -2827 5196 0 -2826 2827 871 871 5716 5716 -3146 3146 -4765 0 2059 -2059 871 -871 -871 -871 -5932 -5932zm4633 6803l-2317 -2317 -2316 2317 2316 2316 2317 -2316z" />
            </g>
        </svg>


    </div>

    <!-- User Info on the Right -->
    @auth
        <div class="flex items-center gap-2 text-primary-500 mr-8">
            <span class="font-medium">Halo, {{ auth()->user()->name }}</span>
            <img src="{{ auth()->user()->avatar_url ?? 'https://ui-avatars.com/api/?name=' . auth()->user()->name . '&background=ffffff&color=947CD6' }}"
                alt="Avatar" class="h-8 w-8 rounded-full" />
        </div>
    @endauth
</header>
