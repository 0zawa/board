# Board
下記の機能を持つCakePHPプロジェクトです。

* ユーザーの追加/取得/認証
* 24時間有効なアクセストークンでの認証
* スレッドの投稿/取得/削除/タグ指定での検索
* ポストの投稿/取得

# 設置方法
以下のAPIの説明は下記のように設置したものと仮定します。

## DocumentRoot
/var/www/html

## プロジェクト設置位置
/var/www/board 

## シンボリックリンクの作成
/var/www/html/api/v1 # v1は/var/www/board/app/webrootへのシンボリックリンク

# APIドキュメント

## ユーザー
### 作成

#### リクエスト
```bash
curl -X POST -H "Content-Type: application/json" https://example.com/api/v1/users -d '{"name":"ozawa","password":"qwerty","mail":"ozawa@example.com”}'
```

#### レスポンス
```
{"id":"8","name":"ozawa”}
```
### 取得
#### リクエスト

```bash
curl -X GET https://example.com/api/v1/users/7
```

#### レスポンス

```
{"id":"8","name":"ozawa”}
```

## ログイン
#### リクエスト

```bash
curl -X POST -H "Content-Type: application/json" http://example.com/api/v1/login -d '{"name":"ozawa","password":"qwerty”}'
```

#### レスポンス

```
{"token":"18beda5def56451a8cccdd7c62659bd1”}
```

## スレッド

スレッド操作系APIは全てにHTTPヘッダ「X-Token」を追加し、
値にログイン時の戻り値のアクセストークンを入れる.

### 作成
#### リクエスト

```bash
curl -X POST -H "Content-Type: application/json" -H "X-Token:fb5dbd45360746078228542187bb2475" http://example.com/api/v1/threads -d {"title":"animals","tags":["cat","dog"] }
```

#### レスポンス

```
{"id":"11","title":"animal","created_at":"2015-07-26 11:49:12","created_by":"8”}
```
### 取得
#### リクエスト

```bash
curl -X GET -H "Content-Type: application/json" -H "X-Token:fb5dbd45360746078228542187bb2475" http://example.com/api/v1/threads/2
```

#### レスポンス

```
{"id":"2","title":"animals","tags":”cat,dog","total_posts":3}
```

### 削除
#### リクエスト

```bash
curl -X DELETE -H "Content-Type: application/json" -H "X-Token:fb5dbd45360746078228542187bb2475" http://example.com/api/v1/threads/2
```

#### レスポンス

```
{"id":"2","created_at":"2015-07-26 11:49:12”}
```

### 検索
#### リクエスト

```bash
curl -X GET -H "Content-Type: application/json" -H "X-Token:18beda5def56451a8cccdd7c62659bd1" "http://example.com/api/v1/threads?tags=cat,dog”
```

#### レスポンス

```
{"threads":["8","9","10”]}
```

## ポスト

ポスト操作系APIは全てにHTTPヘッダ「X-Token」を追加し、
値にログイン時の戻り値のアクセストークンを入れる.

### 作成
#### リクエスト

```bash
curl -X POST -H "Content-Type: application/json" -H "X-Token:fb5dbd45360746078228542187bb2475" http://example.com/api/v1/threads/8/posts -d '{"content":"poodle”}'
```

#### レスポンス

```
{"id":"5","thread_id":"8","content":"poodle","created_at":"2015-07-26 13:54:35","created_by":"12”}
```

### 取得
#### リクエスト

```bash
curl -X GET -H "Content-Type: application/json" -H "X-Token:fb5dbd45360746078228542187bb2475" http://example.com/api/v1/threads/8/posts/2
```

#### レスポンス

```
{"id":"2","thread_id":"8","content":"shiba","created_at":"2015-07-25 13:57:25","created_by":"1”}
```

## エラー時レスポンス

HTTPステータスコードは全て500.
エラーレスポンスは全て{"message":エラー内容}の形式.

```
{"message":"invalid token”}
```

# todo
* テストの充実
* 例外発生時のメッセージの共通化
* エラー発生時のレスポンスの詳細内容表示対応.

