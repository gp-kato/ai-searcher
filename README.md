# AI FINDER API

AIツール一覧はLaravel APIから取得します。APIはJSONを返します。

## エンドポイント

### `GET /api/tools`

AIツールをページネーション付きで取得します。指定できる検索条件は次のとおりです。

| パラメーター | 説明 |
| --- | --- |
| `q` | ツール名、説明、カテゴリ、タグ、機能名のキーワード検索 |
| `category` | カテゴリのslugまたは名前 |
| `pricing` | `all`（すべて）、`free`（無料または無料プランあり）、`paid`（有料のみ） |
| `sort` | `recommended`（名前順）、`newest`（登録日の新しい順） |
| `page` | ページ番号（1以上） |
| `per_page` | 1ページあたりの件数（1〜50、既定値9） |

例: `/api/tools?q=文章&category=writing&pricing=free&per_page=9`

レスポンスには `data`（AIツール一覧）と `meta`（件数、ページ情報）が含まれます。ツール項目にはカテゴリ、タグ、機能が含まれます。無効な検索条件は `422 Unprocessable Entity` とバリデーションエラーを返します。

### `GET /api/categories`

カテゴリ一覧を件数（`tools_count`）とともに返します。

お気に入りと確認済みフラグの仕様は[ツール表示項目・お気に入り仕様](./docs/tool-metadata-and-favorites.md)を参照してください。
