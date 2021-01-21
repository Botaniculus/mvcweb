<?php

class ArticlesManager
{
  public function returnArticle($url){
    return Db::querySingleRow('
      SELECT `article_id`, `article_title`, `article_content`, `article_url`, `article_description`, `article_keywords`
      FROM `articles`
      WHERE `article_url` = ?
    ', array($url));
  }

  public function returnArticles(){
    return Db::queryAllRows('
      SELECT `article_id`, `article_title`, `article_url`, `article_description`
      FROM `articles`
      ORDER BY `article_id` DESC
    ');
  }
}
