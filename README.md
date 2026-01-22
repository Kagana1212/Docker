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
```
NGINX_HOST_PORT=8080
MYSQL_DATABASE=laravel
MYSQL_USER=phper
MYSQL_PASSWORD=secret
MYSQL_ROOT_PASSWORD=root
```
### 3.Laravelアプリ用の設定ファイルを作成（appディレクトリ内）
```
cp app/.env.example app/.env
```
```
APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=phper
DB_PASSWORD=secret
```
### 4.コンテナを作成してバックグラウンドで起動
```
docker compose up -d --build
```
### 5.ライブラリのインストール
```
docker compose exec app composer install
```
### 6.アプリの初期化
```
docker compose exec app composer install
docker compose exec app npm install
docker compose exec app npm run build
```
### 6.アプリケーションキーの生成（500エラー対策）
```
docker compose exec app php artisan key:generate
```
### 7.データベースの構築と初期データの投入
```
docker compose exec app php artisan migrate:fresh --seed
```
### 8.書き込み権限の付与（エラー防止）
```
docker compose exec app chmod -R 777 storage bootstrap/cache
```
### URL
```
http://localhost:8080
```
