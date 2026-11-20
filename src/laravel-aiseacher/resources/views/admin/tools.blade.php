<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            AIツール管理
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if(session('status'))
                        <div class="mb-4 rounded border border-green-300 bg-green-50 px-4 py-3 text-sm text-green-700 dark:border-green-700 dark:bg-green-950 dark:text-green-300">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.tools.store') }}" class="grid gap-5 sm:grid-cols-2">
                        @csrf

                        <div>
                            <x-input-label for="name" value="ツール名" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required maxlength="255" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="url" value="URL" />
                            <x-text-input id="url" name="url" type="url" class="mt-1 block w-full" :value="old('url')" placeholder="https://example.com" required maxlength="255" />
                            <x-input-error :messages="$errors->get('url')" class="mt-2" />
                        </div>

                        <div class="sm:col-span-2">
                            <x-input-label for="description" value="説明" />
                            <textarea id="description" name="description" rows="4" maxlength="5000" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="category_id" value="カテゴリ" />
                            <select id="category_id" name="category_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                <option value="">選択してください</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" @selected((string) old('category_id') === (string) $category->id)>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="pricing_system" value="料金区分" />
                            <select id="pricing_system" name="pricing_system" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                <option value="">選択してください</option>
                                @foreach(['Free' => '無料', 'Freemium' => '無料プランあり', 'Paid' => '有料'] as $value => $label)
                                    <option value="{{ $value }}" @selected(old('pricing_system') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('pricing_system')" class="mt-2" />
                        </div>

                        <fieldset class="sm:col-span-2">
                            <legend class="text-sm font-medium text-gray-700 dark:text-gray-300">タグ</legend>
                            <div class="mt-2 flex flex-wrap gap-3">
                                @foreach($tags as $tag)
                                    <label class="inline-flex items-center gap-2">
                                        <input type="checkbox" name="tag_ids[]" value="{{ $tag->id }}" @checked(in_array($tag->id, old('tag_ids', []))) class="rounded border-gray-300 text-indigo-600 shadow-sm">
                                        {{ $tag->name }}
                                    </label>
                                @endforeach
                            </div>
                            <x-input-error :messages="$errors->get('tag_ids')" class="mt-2" />
                        </fieldset>

                        <fieldset class="sm:col-span-2">
                            <legend class="text-sm font-medium text-gray-700 dark:text-gray-300">対応機能</legend>
                            <div class="mt-2 flex flex-wrap gap-3">
                                @foreach($functions as $function)
                                    <label class="inline-flex items-center gap-2">
                                        <input type="checkbox" name="function_ids[]" value="{{ $function->id }}" @checked(in_array($function->id, old('function_ids', []))) class="rounded border-gray-300 text-indigo-600 shadow-sm">
                                        {{ $function->name }}
                                    </label>
                                @endforeach
                            </div>
                            <x-input-error :messages="$errors->get('function_ids')" class="mt-2" />
                        </fieldset>

                        <div class="sm:col-span-2 flex justify-end">
                            <x-primary-button>ツールを追加</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
