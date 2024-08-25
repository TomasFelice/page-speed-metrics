<!-- Header -->
<header class="bg-blue-600 text-white shadow p-4">
    <div class="container mx-auto flex items-center justify-between w-full">
        <div>
            <h1 class="text-2xl font-bold">{{ __('messages.app_name') }}</h1>
            <nav class="mt-2">
                <a href="{{ route('pagespeed.index') }}" class="text-white hover:underline">{{ __('messages.get_metrics') }}</a>
                <a href="{{ route('metrics.index') }}" class="ml-4 text-white hover:underline">{{ __('messages.metrics_history') }}</a>
            </nav>
        </div>

        <div class="flex items-center">
            <button id="change-language">
                @if (app()->getLocale() == 'en')
                    <img width="48" height="48" src="https://img.icons8.com/fluency/48/usa-circular.png" alt="change-language-english"/>
                @else
                    <img width="48" height="48" src="https://img.icons8.com/fluency/48/spain-circular.png" alt="change-language-spanish"/>
                @endif
            </button>
        </div>
    </div>
</header>