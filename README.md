# GitHubからソースコードをダウンロード
git clone https://github.com/Kagana1212/Docker.git

# フォルダの中に入る
cd Docker

# Docker用の設定ファイルを作成
cp .env.example .env

# Laravelアプリ用の設定ファイルを作成（appディレクトリ内）
cp app/.env.example app/.env

# コンテナを作成してバックグラウンドで起動
docker compose up -d

# アプリケーションキーの生成（500エラー対策）
docker compose exec app php artisan key:generate

# データベースのテーブル作成（SQLエラー対策）
docker compose exec app php artisan migrate
