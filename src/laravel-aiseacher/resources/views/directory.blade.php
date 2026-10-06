<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f5f5f0">
    <title>AI FINDER | AIツール検索</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Noto+Sans+JP:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="page-shell">
        <header class="topbar">
            <a class="brand" href="/" aria-label="AI Finder ホーム">
                <span class="brand-mark" aria-hidden="true"><span></span><span></span><span></span></span>
                <span>AI<span class="brand-light">FINDER</span></span>
            </a>
            <nav class="top-nav" aria-label="メインナビゲーション">
                <a class="nav-active" href="#discover">ツールを探す</a>
                <a href="#popular">人気のツール</a>
                <a href="mailto:hello@aifinder.jp?subject=掲載リクエスト">掲載リクエスト <span aria-hidden="true">↗</span></a>
            </nav>
            <a class="submit-link" href="mailto:hello@aifinder.jp?subject=掲載リクエスト">ツールを掲載する <span aria-hidden="true">↗</span></a>
        </header>

        <main>
            <section class="hero" id="discover">
                <div class="hero-copy">
                    <p class="eyebrow"><span class="live-dot"></span> CURATED AI DIRECTORY <span class="eyebrow-jp">｜AIツール図鑑</span></p>
                    <h1>あなたにぴったりの<br><span>AIツール</span>を見つけよう。</h1>
                    <p class="hero-description">仕事も、創作も、毎日の小さなことも。<br class="desktop-break">目的から探せるAIツールのディレクトリ。</p>
                    <div class="hero-meta"><span><strong>240+</strong> 厳選ツール</span><i></i><span>毎週アップデート</span><i></i><span>日本語で探せる</span></div>
                </div>
                <div class="hero-art" aria-hidden="true">
                    <div class="art-orbit orbit-one"></div><div class="art-orbit orbit-two"></div>
                    <div class="art-spark spark-one">✳</div><div class="art-spark spark-two">✦</div>
                    <div class="art-center"><span>AI</span><small>FIND YOUR<br>FLOW</small></div>
                    <div class="art-label label-top">アイデアを、かたちに。</div>
                    <div class="art-label label-bottom">いい道具は、いい仕事をつくる。</div>
                </div>
            </section>

            <section class="search-section" aria-label="AIツール検索">
                <form class="search-box" id="search-form" role="search">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="7"></circle><path d="m20 20-4-4"></path></svg>
                    <input id="search-input" type="search" placeholder="ツール名、やりたいことから検索..." autocomplete="off" aria-label="キーワードで検索">
                    <kbd>/</kbd>
                    <button type="submit">検索する <span aria-hidden="true">→</span></button>
                </form>
                <div class="suggestions"><span>POPULAR：</span><button type="button" data-query="画像生成">画像生成</button><button type="button" data-query="文章">文章作成</button><button type="button" data-query="議事録">議事録</button><button type="button" data-query="動画">動画編集</button></div>
            </section>

            <section class="directory" id="popular">
                <aside class="filters" aria-label="検索フィルター">
                    <div class="filter-heading"><h2>絞り込み</h2><button type="button" id="clear-filters" class="clear-button">クリア</button></div>
                    <div class="filter-group">
                        <h3>カテゴリ</h3>
                        <div class="category-options" id="category-options">
                            <button type="button" class="category-option is-selected" data-category="すべて"><span class="category-icon icon-all">✳</span><span>すべて</span><span class="category-count">24</span></button>
                            <button type="button" class="category-option" data-category="文章・ライティング"><span class="category-icon icon-writing">Aa</span><span>文章・ライティング</span><span class="category-count">6</span></button>
                            <button type="button" class="category-option" data-category="画像・デザイン"><span class="category-icon icon-image">◈</span><span>画像・デザイン</span><span class="category-count">5</span></button>
                            <button type="button" class="category-option" data-category="動画・音声"><span class="category-icon icon-video">▷</span><span>動画・音声</span><span class="category-count">4</span></button>
                            <button type="button" class="category-option" data-category="仕事効率化"><span class="category-icon icon-work">⌘</span><span>仕事効率化</span><span class="category-count">5</span></button>
                            <button type="button" class="category-option" data-category="開発・コード"><span class="category-icon icon-code">&lt;/&gt;</span><span>開発・コード</span><span class="category-count">4</span></button>
                        </div>
                    </div>
                    <div class="filter-group pricing-group">
                        <h3>料金プラン</h3>
                        <label class="radio-row"><input type="radio" name="pricing" value="all" checked><span class="custom-radio"></span>すべて</label>
                        <label class="radio-row"><input type="radio" name="pricing" value="free"><span class="custom-radio"></span>無料プランあり</label>
                        <label class="radio-row"><input type="radio" name="pricing" value="paid"><span class="custom-radio"></span>有料プランのみ</label>
                    </div>
                    <div class="filter-note"><span aria-hidden="true">✦</span><p>新しいツールとの出会いを。<br>毎週、編集部が追加しています。</p></div>
                </aside>

                <div class="results-area">
                    <div class="results-heading">
                        <div><p class="section-kicker">THE DIRECTORY</p><h2>おすすめのAIツール</h2></div>
                        <div class="results-controls"><span id="result-count" aria-live="polite">24件</span><label for="sort-select" class="sr-only">並べ替え</label><select id="sort-select"><option value="recommended">おすすめ順</option><option value="rating">評価が高い順</option><option value="newest">新着順</option></select></div>
                    </div>
                    <div class="active-filters" id="active-filters" aria-live="polite"></div>
                    <div class="tool-grid" id="tool-grid"></div>
                    <div class="empty-state" id="empty-state" hidden><span>⌕</span><h3>見つかりませんでした</h3><p>キーワードや絞り込み条件を変えてお試しください。</p><button type="button" id="empty-clear">条件をリセット</button></div>
                    <div class="load-more-wrap"><button type="button" class="load-more" id="load-more">もっと見る <span aria-hidden="true">↓</span></button></div>
                </div>
            </section>
        </main>

        <footer class="footer"><a class="brand footer-brand" href="/"><span class="brand-mark" aria-hidden="true"><span></span><span></span><span></span></span><span>AI<span class="brand-light">FINDER</span></span></a><span>いい道具は、いい仕事をつくる。</span><span>© 2025 AI FINDER</span></footer>
    </div>
</body>
</html>
