-- データベースの存在を確認して削除後、新規に作成
DROP DATABASE IF EXISTS posse;
CREATE DATABASE posse;
USE posse;

-- todosテーブルを作成
DROP TABLE IF EXISTS todos;
CREATE TABLE todos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  text VARCHAR(255) NOT NULL,
  completed BOOLEAN DEFAULT FALSE
) CHARSET=utf8;

INSERT INTO todos (user_id, text) VALUES (1, "todo1");
INSERT INTO todos (user_id, text) VALUES (1, "todo2");
INSERT INTO todos (user_id, text) VALUES (2, "todo3");

-- usersテーブル
DROP TABLE IF EXISTS users;
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(255) UNIQUE,
  password VARCHAR(255)
) CHARSET=utf8;

-- サンプルデータを挿入
INSERT INTO users (email, password) VALUES ("user1@gmail.com", "$2y$10$csAFREneXMq1sdnuvOrFWe.ZW0kDM3Qigy1S0bhFJ3hhc6fgpMEIy");
INSERT INTO users (email, password) VALUES ("user2@gmail.com", "$2y$10$csAFREneXMq1sdnuvOrFWe.ZW0kDM3Qigy1S0bhFJ3hhc6fgpMEIy");