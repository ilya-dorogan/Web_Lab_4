<?php
	function getDbConnection(){
		$db = new SQLite3('database/articles.db');
		if (!$db) {
			http_response_code(500);
			exit('DB connection error');
		}
		return $db;
	}
 
	function initDB(){
		$db = getDbConnection();
		$CreateArticleTableQuery = 'CREATE TABLE IF NOT EXISTS Posts (
									ID INTEGER PRIMARY KEY AUTOINCREMENT,
									ImageName TEXT,
									Title TEXT,
									Content TEXT,
									Created DATE);

									CREATE TABLE IF NOT EXISTS Comments (
									id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
									Article_id INTEGER NOT NULL,
									Author_Name TEXT NOT NULL,
									Rate INTEGER NOT NULL,
									Comment TEXT NOT NULL,
									Created DATE NOT NULL);';
		$result = $db->exec($CreateArticleTableQuery);
		if (!$result) {
			http_response_code(500);
			exit('DB init error');
		}
	}


