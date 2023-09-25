-- データベースの存在を確認して削除後、新規に作成
DROP DATABASE IF EXISTS posse;
CREATE DATABASE posse;
USE posse;

-- todosテーブルを作成
DROP TABLE IF EXISTS todos;
CREATE TABLE todos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  text VARCHAR(255) NOT NULL,
  completed BOOLEAN DEFAULT FALSE
) CHARSET=utf8;

INSERT INTO todos (text) VALUES ("テスト1");

-- usersテーブル
DROP TABLE IF EXISTS users;
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255),
  email VARCHAR(255),
  password VARCHAR(255)
) CHARSET=utf8;

-- サンプルデータを挿入
INSERT INTO users (name, email, password) VALUES ("管理者1", "admin@example.com", "$2y$10$csAFREneXMq1sdnuvOrFWe.ZW0kDM3Qigy1S0bhFJ3hhc6fgpMEIy");