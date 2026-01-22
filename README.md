### 1.ソースコードの取得
GitHubからリポジトリをクローンし、ディレクトリに移動します。
```
git clone　https://github.com/Kagana1212/Docker.git
cd Docker
```
### 2.Docker用の設定ファイルを作成
```
cp .env.example .env
```
### 3.Laravelアプリ用の設定ファイルを作成（appディレクトリ内）
```
cp app/.env.example app/.env
```
### 4.コンテナを作成してバックグラウンドで起動
```
docker compose up -d
```
### 5.ライブラリのインストール
```
docker compose exec app composer install
```
### 6.アプリケーションキーの生成（500エラー対策）
```
docker compose exec app php artisan key:generate
```
### 7.データベースの構築と初期データの投入
```
docker compose exec app php artisan migrate:fresh --seed
```
### 8.データベースのテーブル作成（SQLエラー対策）
```
docker compose exec app php artisan migrate
```
