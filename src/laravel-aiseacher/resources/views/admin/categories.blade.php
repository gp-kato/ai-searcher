<x-app-layout>
    <x-slot name="title">カテゴリ追加 | AI FINDER</x-slot>

    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            カテゴリ管理
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-4xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if(session('status'))
                        <div class="mb-4 rounded border border-green-300 bg-green-50 px-4 py-3 text-sm text-green-700 dark:border-green-700 dark:bg-green-950 dark:text-green-300">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.categories.store') }}" class="grid gap-5 sm:grid-cols-2">
                        @csrf

                        <div>
                            <x-input-label for="name" value="カテゴリ名" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required maxlength="255" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="slug" value="slug（英小文字・数字・ハイフン）" />
                            <x-text-input id="slug" name="slug" type="text" class="mt-1 block w-full" :value="old('slug')" required maxlength="255" pattern="[a-z0-9]+(-[a-z0-9]+)*" />
                            <x-input-error :messages="$errors->get('slug')" class="mt-2" />
                        </div>

                        <div class="sm:col-span-2 flex items-center justify-between">
                            <a class="text-sm text-indigo-600 underline hover:text-indigo-900 dark:text-indigo-400" href="{{ route('admin.tools') }}">ツール追加へ戻る</a>
                            <x-primary-button>カテゴリを追加</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="mb-4 text-lg font-medium">登録済みカテゴリ</h3>
                    @if($categories->isEmpty())
                        <p class="text-sm text-gray-600 dark:text-gray-400">カテゴリはまだありません。</p>
                    @else
                        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($categories as $category)
                                <li class="flex items-center justify-between py-3">
                                    <span>{{ $category->name }} <span class="text-sm text-gray-500">({{ $category->slug }})</span></span>
                                    <span class="text-sm text-gray-500">{{ $category->tools_count }}件</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
