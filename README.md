# Board
下記の機能を持つCakePHPプロジェクトです。

* ユーザーの追加/参照/認証
* 24時間有効なアクセストークンでの認証
* スレッドの投稿/参照/削除/タグ指定での検索
* ポストの投稿/参照

# 設置方法
以下のAPIの説明は下記のように設置したものと仮定します。

DocumentRoot
/var/www/html

プロジェクト設置位置
/var/www/board 

シンボリックリンクの作成
/var/www/html/api/v1 # v1は/var/www/board/app/webrootへのシンボリックリンク

# APIドキュメンと

## ユーザー

### 作成

#### リクエスト
```bash
curl -X POST -H "Content-Type: application/json" http://url/api/v1/users -d '{"name":"ozawa","password":"qwerty","mail":"ozawa@example.com”}'
```

#### レスポンス
```
{"id":"8","name":"ozawa”}
```





