<?php

class ArticlesManager
{
  public function getArticle($url){
    return Db::querySingleRow('
      SELECT `article_id`, `article_title`, `article_content`, `article_url`, `article_description`, `article_keywords`
      FROM `articles`
      WHERE `article_url` = ?
    ', array($url));
  }

  public function getArticles(){
    return Db::queryAllRows('
      SELECT `article_id`, `article_title`, `article_url`, `article_description`
      FROM `articles`
      ORDER BY `article_id` DESC
    ');
  }

  public function saveArticle($id, $article){
    if(!$id)
      Db::insert('articles', $article);
    else
      Db::update('articles', $article, 'WHERE article_id = ?', array($id));
  }

  public function removeArticle($url){
    Db::query('DELETE FROM articles WHERE article_url = ?', array($url));
  }
}
