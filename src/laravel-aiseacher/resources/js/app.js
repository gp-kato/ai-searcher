import './bootstrap';

const tools = [
	{ name: 'Notion AI', category: '仕事効率化', description: 'メモも議事録も、AIと一緒に整理。考えをすばやく形にするワークスペース。', tags: ['議事録', '文章作成', '整理'], price: '無料プランあり', free: true, rating: 4.8, initial: 'N', color: '#242424', bg: '#ededeb', image: 'photo-1455390582262-044cdead277a', url: 'https://www.notion.so/product/ai', verified: true },
	{ name: 'Midjourney', category: '画像・デザイン', description: '言葉から、想像を超えるビジュアルを。世界中のクリエイターが愛用。', tags: ['画像生成', 'アート', 'デザイン'], price: '有料', free: false, rating: 4.9, initial: 'M', color: '#fff', bg: '#202126', image: 'photo-1549490349-8643362247b5', url: 'https://www.midjourney.com/', verified: true },
	{ name: 'Claude', category: '文章・ライティング', description: '自然な対話で、リサーチから執筆まで。長文の読み込みも得意なAI。', tags: ['文章作成', 'リサーチ', 'チャット'], price: '無料プランあり', free: true, rating: 4.8, initial: '✳', color: '#a46e4f', bg: '#f2e9df', image: 'photo-1455390582262-044cdead277a', url: 'https://claude.ai/', verified: true },
	{ name: 'Runway', category: '動画・音声', description: 'テキストや画像から映像を生成。アイデアをそのまま動画に。', tags: ['動画生成', '映像編集', '創作'], price: '無料プランあり', free: true, rating: 4.7, initial: 'R', color: '#fff', bg: '#20242b', image: 'photo-1485846234645-a62644f84728', url: 'https://runwayml.com/', verified: true },
	{ name: 'GitHub Copilot', category: '開発・コード', description: 'コードを書く、その先へ。いつものエディタでAIとペアプログラミング。', tags: ['コード生成', '開発', '補完'], price: '無料プランあり', free: true, rating: 4.7, initial: '⌘', color: '#252525', bg: '#edeee9', image: 'photo-1461749280684-dccba630e2f6', url: 'https://github.com/features/copilot', verified: true },
	{ name: 'Canva Magic Studio', category: '画像・デザイン', description: 'デザインの悩みを、AIでショートカット。作成から編集までひとつに。', tags: ['画像生成', 'デザイン', '資料'], price: '無料プランあり', free: true, rating: 4.6, initial: 'C', color: '#fff', bg: '#536df0', image: 'photo-1545235617-9465d2a55698', url: 'https://www.canva.com/magic/', verified: true },
	{ name: 'Fathom', category: '仕事効率化', description: '会議に集中して、記録はAIに。要約とアクションを自動でお届け。', tags: ['議事録', '文字起こし', '会議'], price: '無料プランあり', free: true, rating: 4.8, initial: 'f', color: '#fff', bg: '#5367dd', image: 'photo-1497366754035-f200968a6e72', url: 'https://fathom.video/', verified: false },
	{ name: 'Jasper', category: '文章・ライティング', description: 'ブランドらしい言葉を、もっと速く。マーケティング文章の頼れる相棒。', tags: ['文章作成', 'マーケティング', 'SEO'], price: '有料', free: false, rating: 4.5, initial: 'J', color: '#fff', bg: '#8058cf', image: 'photo-1455390582262-044cdead277a', url: 'https://www.jasper.ai/', verified: false },
	{ name: 'ElevenLabs', category: '動画・音声', description: '人の声のような自然な音声を生成。多言語ナレーションにも対応。', tags: ['音声生成', 'ナレーション', '翻訳'], price: '無料プランあり', free: true, rating: 4.8, initial: '11', color: '#fff', bg: '#222', image: 'photo-1478737270239-2f02b77fc618', url: 'https://elevenlabs.io/', verified: true },
	{ name: 'Perplexity', category: '文章・ライティング', description: '出典付きで答えが見つかる。調べもののための新しい検索体験。', tags: ['リサーチ', '検索', '要約'], price: '無料プランあり', free: true, rating: 4.7, initial: 'P', color: '#fff', bg: '#267f80', image: 'photo-1456324504439-367cee3b3c32', url: 'https://www.perplexity.ai/', verified: true },
	{ name: 'Descript', category: '動画・音声', description: '動画も音声も、テキストを編集する感覚で。収録から仕上げまで。', tags: ['動画編集', '文字起こし', '音声'], price: '無料プランあり', free: true, rating: 4.6, initial: 'D', color: '#fff', bg: '#3568f5', image: 'photo-1492619375914-88005aa9e8fb', url: 'https://www.descript.com/', verified: false },
	{ name: 'Cursor', category: '開発・コード', description: 'コードベースを理解するAIエディタ。自然な言葉で開発を加速。', tags: ['コード生成', '開発', 'エディタ'], price: '無料プランあり', free: true, rating: 4.8, initial: '↗', color: '#252525', bg: '#efefe9', image: 'photo-1518770660439-4636190af475', url: 'https://www.cursor.com/', verified: true },
	{ name: 'Adobe Firefly', category: '画像・デザイン', description: '商用利用にも配慮した生成AI。Adobe製品との連携もスムーズ。', tags: ['画像生成', 'デザイン', '画像編集'], price: '無料プランあり', free: true, rating: 4.5, initial: '✦', color: '#e74642', bg: '#fff0ed', image: 'photo-1531058020387-3be344556be6', url: 'https://www.adobe.com/products/firefly.html', verified: true },
	{ name: 'DeepL Write', category: '文章・ライティング', description: '文章のトーンや表現を自然にブラッシュアップ。日本語にも対応。', tags: ['文章校正', '翻訳', 'ライティング'], price: '無料プランあり', free: true, rating: 4.6, initial: 'D', color: '#fff', bg: '#2864dc', image: 'photo-1455390582262-044cdead277a', url: 'https://www.deepl.com/write', verified: false },
	{ name: 'Suno', category: '動画・音声', description: 'ジャンルと気分を伝えるだけ。オリジナルの楽曲をAIでつくろう。', tags: ['音楽生成', '作曲', '音声'], price: '無料プランあり', free: true, rating: 4.7, initial: '♪', color: '#fff', bg: '#ed8054', image: 'photo-1516280440614-37939bbacd81', url: 'https://suno.com/', verified: true },
	{ name: 'Zapier AI', category: '仕事効率化', description: '普段使っているアプリをつないで、繰り返し作業を自動化。', tags: ['自動化', '連携', 'ワークフロー'], price: '無料プランあり', free: true, rating: 4.6, initial: 'Z', color: '#fff', bg: '#e98b3c', image: 'photo-1497366811353-6870744d04b2', url: 'https://zapier.com/ai', verified: false },
	{ name: 'Framer AI', category: '開発・コード', description: 'プロンプトからWebサイトをデザイン。公開までをひとつの場所で。', tags: ['Web制作', 'デザイン', 'ノーコード'], price: '無料プランあり', free: true, rating: 4.5, initial: 'F', color: '#fff', bg: '#7756db', image: 'photo-1498050108023-c5249f4df085', url: 'https://www.framer.com/ai/', verified: false },
	{ name: 'ChatGPT', category: '仕事効率化', description: 'アイデア出しから画像生成、データ分析まで。毎日に寄り添うAI。', tags: ['チャット', '画像生成', '文章作成'], price: '無料プランあり', free: true, rating: 4.8, initial: '◉', color: '#fff', bg: '#169b74', image: 'photo-1486312338219-ce68d2c6f44d', url: 'https://chatgpt.com/', verified: true },
	{ name: 'Leonardo AI', category: '画像・デザイン', description: '細かなスタイル調整で、イメージ通りの画像をスピーディに生成。', tags: ['画像生成', 'イラスト', 'ゲーム'], price: '無料プランあり', free: true, rating: 4.6, initial: 'L', color: '#fff', bg: '#7764d7', image: 'photo-1541961017774-22349e4a1262', url: 'https://leonardo.ai/', verified: false },
	{ name: 'Grammarly', category: '文章・ライティング', description: '伝えたいことを、より明確に。英文の校正と書き換えをサポート。', tags: ['文章校正', '英語', 'ライティング'], price: '無料プランあり', free: true, rating: 4.5, initial: 'G', color: '#fff', bg: '#23815d', image: 'photo-1455390582262-044cdead277a', url: 'https://www.grammarly.com/', verified: false },
	{ name: 'Airtable AI', category: '仕事効率化', description: 'データ整理や要約をAIで自動化。チームの情報を使える資産に。', tags: ['データ整理', '自動化', 'チーム'], price: '無料プランあり', free: true, rating: 4.4, initial: 'A', color: '#fff', bg: '#eb7866', image: 'photo-1497366754035-f200968a6e72', url: 'https://www.airtable.com/ai', verified: false },
	{ name: 'Lovable', category: '開発・コード', description: '作りたいものを会話で伝えて、動くWebアプリをすばやく形に。', tags: ['アプリ開発', 'コード生成', 'ノーコード'], price: '無料プランあり', free: true, rating: 4.7, initial: '♥', color: '#fff', bg: '#e77963', image: 'photo-1498050108023-c5249f4df085', url: 'https://lovable.dev/', verified: true },
	{ name: 'Ideogram', category: '画像・デザイン', description: '画像の中に読みやすい文字を。ポスターやロゴづくりにもぴったり。', tags: ['画像生成', 'ロゴ', 'デザイン'], price: '無料プランあり', free: true, rating: 4.5, initial: 'I', color: '#fff', bg: '#e26b52', image: 'photo-1549490349-8643362247b5', url: 'https://ideogram.ai/', verified: false },
	{ name: 'Otter.ai', category: '仕事効率化', description: '会話をリアルタイムで文字起こし。重要なポイントも自動で整理。', tags: ['議事録', '文字起こし', '会議'], price: '無料プランあり', free: true, rating: 4.4, initial: 'O', color: '#fff', bg: '#4772c9', image: 'photo-1497366754035-f200968a6e72', url: 'https://otter.ai/', verified: false },
	{ name: 'Copy.ai', category: '文章・ライティング', description: 'メールやSNS投稿、商品紹介文をAIで作成。書き出しに迷わない。', tags: ['文章作成', 'SNS', 'マーケティング'], price: '無料プランあり', free: true, rating: 4.3, initial: 'C', color: '#fff', bg: '#252525', image: 'photo-1455390582262-044cdead277a', url: 'https://www.copy.ai/', verified: false },
];

const grid = document.querySelector('#tool-grid');
const input = document.querySelector('#search-input');
const count = document.querySelector('#result-count');
const emptyState = document.querySelector('#empty-state');
const moreButton = document.querySelector('#load-more');
const pageSize = 9;
let selectedCategory = 'すべて';
let visibleCount = pageSize;
let query = '';
let pricing = 'all';
let sortOrder = 'recommended';
const favorites = new Set(JSON.parse(localStorage.getItem('ai-finder-favorites') || '[]'));

function getMatches() {
	const normalizedQuery = query.trim().toLocaleLowerCase('ja');
	const matches = tools.filter((tool) => {
		const text = [tool.name, tool.category, tool.description, ...tool.tags].join(' ').toLocaleLowerCase('ja');
		return (!normalizedQuery || text.includes(normalizedQuery))
			&& (selectedCategory === 'すべて' || tool.category === selectedCategory)
			&& (pricing === 'all' || (pricing === 'free' ? tool.free : !tool.free));
	});

	if (sortOrder === 'rating') matches.sort((first, second) => second.rating - first.rating);
	if (sortOrder === 'newest') matches.reverse();
	return matches;
}

function render() {
	const matches = getMatches();
	const shown = matches.slice(0, visibleCount);
	count.textContent = `${matches.length}件`;
	emptyState.hidden = matches.length !== 0;
	moreButton.hidden = matches.length <= visibleCount;
	grid.innerHTML = shown.map((tool, index) => `
		<article class="tool-card" style="animation-delay:${Math.min(index * 35, 245)}ms">
			<div class="card-cover">
				<img src="https://images.unsplash.com/${tool.image}?auto=format&fit=crop&w=640&q=75" alt="${tool.name}のイメージ" loading="lazy">
				<span class="cover-wash"></span><span class="cover-category">${tool.category}</span>
				<button class="favorite-button${favorites.has(tool.name) ? ' is-favorite' : ''}" type="button" data-favorite="${tool.name}" aria-label="${favorites.has(tool.name) ? 'お気に入りから削除' : 'お気に入りに追加'}" aria-pressed="${favorites.has(tool.name)}">${favorites.has(tool.name) ? '♥' : '♡'}</button>
			</div>
			<div class="card-body">
				<div class="tool-title-row"><span class="tool-logo" style="--tool-color:${tool.color};--tool-bg:${tool.bg}">${tool.initial}</span><h3 class="tool-title">${tool.name}${tool.verified ? '<span class="verified" aria-label="確認済み">✓</span>' : ''}</h3><span class="tool-rating"><span>★</span>${tool.rating.toFixed(1)}</span></div>
				<p class="tool-description">${tool.description}</p>
				<div class="tag-list">${tool.tags.map((tag) => `<span class="tool-tag">${tag}</span>`).join('')}</div>
				<div class="card-footer"><span class="price-label${tool.free ? ' is-free' : ''}">${tool.price}</span><a class="tool-link" href="${tool.url}" target="_blank" rel="noopener noreferrer">サイトを見る <span aria-hidden="true">↗</span></a></div>
			</div>
		</article>`).join('');

	document.querySelector('#active-filters').innerHTML = [
		selectedCategory !== 'すべて' ? `<button class="filter-chip" type="button" data-remove="category">${selectedCategory}<span>×</span></button>` : '',
		pricing !== 'all' ? `<button class="filter-chip" type="button" data-remove="pricing">${pricing === 'free' ? '無料プランあり' : '有料プランのみ'}<span>×</span></button>` : '',
		query ? `<button class="filter-chip" type="button" data-remove="query">「${query}」<span>×</span></button>` : '',
	].join('');
}

function resetFilters() {
	selectedCategory = 'すべて';
	pricing = 'all';
	query = '';
	visibleCount = pageSize;
	input.value = '';
	document.querySelector('input[name="pricing"][value="all"]').checked = true;
	document.querySelectorAll('.category-option').forEach((button) => button.classList.toggle('is-selected', button.dataset.category === 'すべて'));
	render();
}

document.querySelector('#search-form').addEventListener('submit', (event) => event.preventDefault());
input.addEventListener('input', () => { query = input.value; visibleCount = pageSize; render(); });
document.querySelectorAll('.suggestions button').forEach((button) => button.addEventListener('click', () => {
	input.value = button.dataset.query;
	query = button.dataset.query;
	visibleCount = pageSize;
	render();
	input.focus();
}));
document.querySelector('#category-options').addEventListener('click', (event) => {
	const button = event.target.closest('[data-category]');
	if (!button) return;
	selectedCategory = button.dataset.category;
	visibleCount = pageSize;
	document.querySelectorAll('.category-option').forEach((option) => option.classList.toggle('is-selected', option === button));
	render();
});
document.querySelectorAll('input[name="pricing"]').forEach((radio) => radio.addEventListener('change', () => { pricing = radio.value; visibleCount = pageSize; render(); }));
document.querySelector('#sort-select').addEventListener('change', (event) => { sortOrder = event.target.value; render(); });
document.querySelector('#clear-filters').addEventListener('click', resetFilters);
document.querySelector('#empty-clear').addEventListener('click', resetFilters);
document.querySelector('#active-filters').addEventListener('click', (event) => {
	const remove = event.target.closest('[data-remove]')?.dataset.remove;
	if (remove === 'category') {
		selectedCategory = 'すべて';
		document.querySelectorAll('.category-option').forEach((button) => button.classList.toggle('is-selected', button.dataset.category === 'すべて'));
	}
	if (remove === 'pricing') {
		pricing = 'all';
		document.querySelector('input[name="pricing"][value="all"]').checked = true;
	}
	if (remove === 'query') { query = ''; input.value = ''; }
	visibleCount = pageSize;
	render();
});
grid.addEventListener('click', (event) => {
	const button = event.target.closest('[data-favorite]');
	if (!button) return;
	const name = button.dataset.favorite;
	if (favorites.has(name)) favorites.delete(name); else favorites.add(name);
	localStorage.setItem('ai-finder-favorites', JSON.stringify([...favorites]));
	render();
});
moreButton.addEventListener('click', () => { visibleCount += pageSize; render(); });
document.addEventListener('keydown', (event) => {
	if (event.key === '/' && !['INPUT', 'TEXTAREA'].includes(document.activeElement.tagName)) {
		event.preventDefault();
		input.focus();
	}
});

render();
