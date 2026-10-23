import './bootstrap';

const grid = document.querySelector('#tool-grid');
const input = document.querySelector('#search-input');
const count = document.querySelector('#result-count');
const emptyState = document.querySelector('#empty-state');
const moreButton = document.querySelector('#load-more');
const categoryOptions = document.querySelector('#category-options');
const apiError = document.querySelector('#api-error');
const pageSize = 9;
let selectedCategory = '';
let selectedCategoryName = '';
let query = '';
let pricing = 'all';
let sortOrder = 'recommended';
let currentPage = 1;
let lastPage = 1;
let requestController;
let searchTimer;
const apiErrors = {};
const tools = [];
const pendingFavorites = new Set();
const legacyFavorites = new Set(JSON.parse(localStorage.getItem('ai-finder-favorites') || '[]'));
const browserIdKey = 'ai-finder-browser-id';
let browserId = localStorage.getItem(browserIdKey);
if (!browserId) {
	browserId = crypto.randomUUID();
	localStorage.setItem(browserIdKey, browserId);
}
const favorites = new Set();

function escapeHtml(value) {
	return String(value ?? '').replace(/[&<>"']/g, (character) => ({
		'&': '&amp;',
		'<': '&lt;',
		'>': '&gt;',
		'"': '&quot;',
		"'": '&#39;',
	})[character]);
}

function safeUrl(value) {
	try {
		const url = new URL(value, window.location.origin);
		return ['http:', 'https:'].includes(url.protocol) ? escapeHtml(url.href) : '#';
	} catch {
		return '#';
	}
}

function pricingLabel(value) {
	if (value === 'Free') return '無料';
	if (value === 'Paid') return '有料';
	return '無料プランあり';
}

function renderCategories(categories) {
	const icons = ['icon-writing', 'icon-image', 'icon-video', 'icon-work', 'icon-code'];
	const symbols = ['Aa', '◈', '▷', '⌘', '</>'];
	const totalCount = categories.reduce((total, category) => total + category.tools_count, 0);
	categoryOptions.innerHTML = `
		<button type="button" class="category-option${selectedCategory === '' ? ' is-selected' : ''}" data-category="">
			<span class="category-icon icon-all">✳</span><span>すべて</span><span class="category-count">${totalCount}</span>
		</button>
		${categories.map((category, index) => `
			<button type="button" class="category-option${selectedCategory === category.slug ? ' is-selected' : ''}" data-category="${escapeHtml(category.slug)}" data-category-name="${escapeHtml(category.name)}">
				<span class="category-icon ${icons[index % icons.length]}">${symbols[index % symbols.length]}</span>
				<span>${escapeHtml(category.name)}</span><span class="category-count">${category.tools_count}</span>
			</button>
		`).join('')}`;
}

function render() {
	grid.innerHTML = tools.map((tool, index) => {
		const favorite = favorites.has(tool.id) || legacyFavorites.has(tool.name);
		const image = tool.thumbnail
			? `<img src="${safeUrl(tool.thumbnail)}" alt="${escapeHtml(tool.name)}のイメージ" loading="lazy">`
			: '';
		const tags = [...(tool.tags || []), ...(tool.functions || [])];

		return `
			<article class="tool-card" style="animation-delay:${Math.min(index * 35, 245)}ms">
				<div class="card-cover">
					${image}<span class="cover-wash"></span><span class="cover-category">${escapeHtml(tool.category?.name)}</span>
					<button class="favorite-button${favorite ? ' is-favorite' : ''}" type="button" data-favorite="${tool.id}" aria-label="${favorite ? 'お気に入りから削除' : 'お気に入りに追加'}" aria-pressed="${favorite}"${pendingFavorites.has(tool.id) ? ' disabled' : ''}>${favorite ? '♥' : '♡'}</button>
				</div>
				<div class="card-body">
					<div class="tool-title-row"><span class="tool-logo">${escapeHtml([...tool.name][0] || '?')}</span><h3 class="tool-title">${escapeHtml(tool.name)}${tool.is_verified ? '<span class="verified" aria-label="確認済み">✓</span>' : ''}</h3></div>
					<p class="tool-description">${escapeHtml(tool.description)}</p>
					<div class="tag-list">${tags.map((tag) => `<span class="tool-tag">${escapeHtml(tag.name)}</span>`).join('')}</div>
					<div class="card-footer"><span class="price-label${tool.pricing_system !== 'Paid' ? ' is-free' : ''}">${pricingLabel(tool.pricing_system)}</span><a class="tool-link" href="${safeUrl(tool.url)}" target="_blank" rel="noopener noreferrer">サイトを見る <span aria-hidden="true">↗</span></a></div>
				</div>
			</article>`;
	}).join('');

	document.querySelector('#active-filters').innerHTML = [
		selectedCategory ? `<button class="filter-chip" type="button" data-remove="category">${escapeHtml(selectedCategoryName)}<span>×</span></button>` : '',
		pricing !== 'all' ? `<button class="filter-chip" type="button" data-remove="pricing">${pricing === 'free' ? '無料プランあり' : '有料プランのみ'}<span>×</span></button>` : '',
		query ? `<button class="filter-chip" type="button" data-remove="query">「${escapeHtml(query)}」<span>×</span></button>` : '',
	].join('');
}

function setError(key, message = '') {
	if (message) apiErrors[key] = message;
	else delete apiErrors[key];
	apiError.textContent = Object.values(apiErrors)[0] || '';
	apiError.hidden = apiError.textContent === '';
}

function saveLegacyFavorites() {
	if (legacyFavorites.size) localStorage.setItem('ai-finder-favorites', JSON.stringify([...legacyFavorites]));
	else localStorage.removeItem('ai-finder-favorites');
}

async function migrateLegacyFavorites(loadedTools) {
	const matches = loadedTools.filter((tool) => legacyFavorites.has(tool.name));
	if (!matches.length) return;

	matches.forEach((tool) => {
		legacyFavorites.delete(tool.name);
		favorites.add(tool.id);
		pendingFavorites.add(tool.id);
	});
	saveLegacyFavorites();
	render();

	const results = await Promise.allSettled(matches.map(async (tool) => {
		const response = await fetch(`/api/favorites/${tool.id}`, {
			method: 'PUT',
			headers: { 'X-Browser-Id': browserId, Accept: 'application/json' },
		});
		if (!response.ok) throw new Error(`API request failed with status ${response.status}`);
	}));
	results.forEach((result, index) => {
		if (result.status === 'rejected') {
			console.error(result.reason);
			favorites.delete(matches[index].id);
			legacyFavorites.add(matches[index].name);
		}
		pendingFavorites.delete(matches[index].id);
	});
	saveLegacyFavorites();
	if (results.some((result) => result.status === 'rejected')) {
		setError('favorites', '一部のお気に入りを移行できませんでした。時間をおいて再度お試しください。');
	}
	render();
}

async function loadTools({ append = false } = {}) {
	requestController?.abort();
	requestController = new AbortController();
	moreButton.disabled = append;
	const params = new URLSearchParams({
		page: String(currentPage),
		per_page: String(pageSize),
		sort: sortOrder,
		pricing,
	});
	if (query.trim()) params.set('q', query.trim());
	if (selectedCategory) params.set('category', selectedCategory);

	try {
		const response = await fetch(`/api/tools?${params}`, { signal: requestController.signal });
		if (!response.ok) throw new Error(`API request failed with status ${response.status}`);
		const result = await response.json();
		if (append) tools.push(...result.data);
		else tools.splice(0, tools.length, ...result.data);
		await migrateLegacyFavorites(result.data);
		count.textContent = `${result.meta.total}件`;
		lastPage = result.meta.last_page;
		moreButton.hidden = currentPage >= lastPage;
		emptyState.hidden = tools.length !== 0;
		setError('tools');
		render();
	} catch (error) {
		if (error.name === 'AbortError') return;
		console.error(error);
		if (!append) {
			tools.length = 0;
			render();
		} else {
			currentPage -= 1;
		}
		setError('tools', 'AIツール情報を取得できませんでした。時間をおいて再度お試しください。');
	} finally {
		moreButton.disabled = false;
	}
}

function refresh() {
	currentPage = 1;
	void loadTools();
}

function resetFilters() {
	selectedCategory = '';
	selectedCategoryName = '';
	pricing = 'all';
	query = '';
	input.value = '';
	document.querySelector('input[name="pricing"][value="all"]').checked = true;
	categoryOptions.querySelectorAll('.category-option').forEach((button) => button.classList.toggle('is-selected', button.dataset.category === ''));
	refresh();
}

document.querySelector('#search-form').addEventListener('submit', (event) => {
	event.preventDefault();
	query = input.value;
	window.clearTimeout(searchTimer);
	refresh();
});

input.addEventListener('input', () => {
	query = input.value;
	window.clearTimeout(searchTimer);
	searchTimer = window.setTimeout(refresh, 250);
});

document.querySelectorAll('.suggestions button').forEach((button) => button.addEventListener('click', () => {
	input.value = button.dataset.query;
	query = button.dataset.query;
	refresh();
	input.focus();
}));

categoryOptions.addEventListener('click', (event) => {
	const button = event.target.closest('[data-category]');
	if (!button) return;
	selectedCategory = button.dataset.category;
	selectedCategoryName = button.dataset.categoryName || '';
	categoryOptions.querySelectorAll('.category-option').forEach((option) => option.classList.toggle('is-selected', option === button));
	refresh();
});

document.querySelectorAll('input[name="pricing"]').forEach((radio) => radio.addEventListener('change', () => {
	pricing = radio.value;
	refresh();
}));

document.querySelector('#sort-select').addEventListener('change', (event) => {
	sortOrder = event.target.value;
	refresh();
});

document.querySelector('#clear-filters').addEventListener('click', resetFilters);
document.querySelector('#empty-clear').addEventListener('click', resetFilters);

document.querySelector('#active-filters').addEventListener('click', (event) => {
	const remove = event.target.closest('[data-remove]')?.dataset.remove;
	if (remove === 'category') {
		selectedCategory = '';
		selectedCategoryName = '';
		categoryOptions.querySelectorAll('.category-option').forEach((button) => button.classList.toggle('is-selected', button.dataset.category === ''));
	}
	if (remove === 'pricing') {
		pricing = 'all';
		document.querySelector('input[name="pricing"][value="all"]').checked = true;
	}
	if (remove === 'query') {
		query = '';
		input.value = '';
	}
	if (remove) refresh();
});

grid.addEventListener('click', (event) => {
	const button = event.target.closest('[data-favorite]');
	if (!button) return;
	const toolId = Number(button.dataset.favorite);
	if (pendingFavorites.has(toolId)) return;
	const tool = tools.find((item) => item.id === toolId);
	const wasLegacyFavorite = legacyFavorites.has(tool?.name);
	const wasFavorite = favorites.has(toolId) || wasLegacyFavorite;
	pendingFavorites.add(toolId);
	if (wasFavorite) favorites.delete(toolId);
	else favorites.add(toolId);
	if (tool) legacyFavorites.delete(tool.name);
	saveLegacyFavorites();
	render();
	void fetch(`/api/favorites/${toolId}`, {
		method: wasFavorite ? 'DELETE' : 'PUT',
		headers: { 'X-Browser-Id': browserId, Accept: 'application/json' },
	}).then((response) => {
		if (!response.ok) throw new Error(`API request failed with status ${response.status}`);
		setError('favorites');
	}).catch((error) => {
		console.error(error);
		if (wasFavorite) favorites.add(toolId);
		else favorites.delete(toolId);
		if (wasLegacyFavorite && tool) legacyFavorites.add(tool.name);
		saveLegacyFavorites();
		setError('favorites', 'お気に入りを保存できませんでした。時間をおいて再度お試しください。');
	}).finally(() => {
		pendingFavorites.delete(toolId);
		render();
	});
});

moreButton.addEventListener('click', () => {
	if (currentPage >= lastPage) return;
	currentPage += 1;
	void loadTools({ append: true });
});

document.addEventListener('keydown', (event) => {
	if (event.key === '/' && !['INPUT', 'TEXTAREA'].includes(document.activeElement.tagName)) {
		event.preventDefault();
		input.focus();
	}
});

async function initialize() {
	const [categoriesResult, favoritesResult] = await Promise.allSettled([
		fetch('/api/categories'),
		fetch('/api/favorites', { headers: { 'X-Browser-Id': browserId, Accept: 'application/json' } }),
	]);
	try {
		if (categoriesResult.status === 'rejected') throw categoriesResult.reason;
		if (!categoriesResult.value.ok) throw new Error(`API request failed with status ${categoriesResult.value.status}`);
		const result = await categoriesResult.value.json();
		renderCategories(result.data);
	} catch (error) {
		console.error(error);
		setError('categories', 'カテゴリ情報を取得できませんでした。時間をおいて再度お試しください。');
	}
	try {
		if (favoritesResult.status === 'rejected') throw favoritesResult.reason;
		if (!favoritesResult.value.ok) throw new Error(`API request failed with status ${favoritesResult.value.status}`);
		const result = await favoritesResult.value.json();
		result.data.forEach((toolId) => favorites.add(Number(toolId)));
	} catch (error) {
		console.error(error);
		setError('favorites', 'お気に入りを取得できませんでした。時間をおいて再度お試しください。');
	}
	await loadTools();
}

void initialize();
