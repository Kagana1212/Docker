<?php
echo "<h1>日記サイトがDocker上で起動しました！</h1>";
echo "<h2>PHP Version: " . phpversion() . "</h2>";

// DB接続テストのヒント
try {
    // dbサービス名（ホスト名）と.envの情報を利用
    $dbh = new PDO('mysql:host=db;dbname=diary_db', 'diary_user', 'secret_password');
    echo "<p>データベース接続に成功しました。</p>";
} catch (PDOException $e) {
    echo "<p>データベース接続エラー: " . $e->getMessage() . "</p>";
}

?>