<?php

class ArticlesManager
{
  public function getArticle($url){
    return Db::querySingleRow('
      SELECT `article_id`, `article_title`, `article_content`, `article_url`, `article_description`, `article_keywords`, `article_author_id`, `article_author_name`, `article_submitted_date`, `article_archival`
      FROM `articles`
      WHERE `article_url` = ?
    ', array($url));
  }

  public function getArticles($page, $onPage, $must = '', $requiredValue = 1){
    $where = (!empty($must)) ? "WHERE `$must` = ?" : '';
    $array = (!empty($must)) ? array($requiredValue, (($page-1) * $onPage), $onPage) : array((($page-1) * $onPage), $onPage);

      return Db::queryAllRows("
        SELECT `article_id`, `article_title`, `article_url`, `article_description`, `article_author_id`, `article_author_name`, `article_submitted_date`, `article_archival`, `article_empty`
        FROM `articles`
        $where
        ORDER BY `article_submitted_date` DESC, `article_id` DESC
        LIMIT ?, ?
      ", $array);
  }
  public function getArticlesCount($must = '', $requiredValue = 1){
    $where = (!empty($must)) ? "WHERE `$must` = ?" : '';
    $array = (!empty($must)) ? array($requiredValue) : array();

    return Db::querySingleColumn("
        SELECT COUNT(*) FROM `articles` $where
      ", $array);
  }

  public function saveArticle($id, $article){
    if(!$id){
      try{
        Db::insert('articles', $article);
      }
      catch (PDOException $e){
        throw new UserException('Vyberte prosím jiný titulek, tento je již použit. (insert) ' . $article['article_submitted_date']);
      }
    }

    else{
      try{
        Db::update('articles', $article, 'WHERE article_id = ?', array($id));
      }
      catch (PDOException $e){
        throw new UserException('Vyberte prosím jiný titulek, tento je již použit. (update)');
      }
    }
  }

  public function removeArticle($url){
    Db::query('DELETE FROM articles WHERE article_url = ?', array($url));
  }

  public function titleToUrl($title){
    $prevodni_tabulka = Array(
      'ä'=>'a',
      'Ä'=>'A',
      'á'=>'a',
      'Á'=>'A',
      'à'=>'a',
      'À'=>'A',
      'ã'=>'a',
      'Ã'=>'A',
      'â'=>'a',
      'Â'=>'A',
      'č'=>'c',
      'Č'=>'C',
      'ć'=>'c',
      'Ć'=>'C',
      'ď'=>'d',
      'Ď'=>'D',
      'ě'=>'e',
      'Ě'=>'E',
      'é'=>'e',
      'É'=>'E',
      'ë'=>'e',
      'Ë'=>'E',
      'è'=>'e',
      'È'=>'E',
      'ê'=>'e',
      'Ê'=>'E',
      'í'=>'i',
      'Í'=>'I',
      'ï'=>'i',
      'Ï'=>'I',
      'ì'=>'i',
      'Ì'=>'I',
      'î'=>'i',
      'Î'=>'I',
      'ľ'=>'l',
      'Ľ'=>'L',
      'ĺ'=>'l',
      'Ĺ'=>'L',
      'ń'=>'n',
      'Ń'=>'N',
      'ň'=>'n',
      'Ň'=>'N',
      'ñ'=>'n',
      'Ñ'=>'N',
      'ó'=>'o',
      'Ó'=>'O',
      'ö'=>'o',
      'Ö'=>'O',
      'ô'=>'o',
      'Ô'=>'O',
      'ò'=>'o',
      'Ò'=>'O',
      'õ'=>'o',
      'Õ'=>'O',
      'ő'=>'o',
      'Ő'=>'O',
      'ř'=>'r',
      'Ř'=>'R',
      'ŕ'=>'r',
      'Ŕ'=>'R',
      'š'=>'s',
      'Š'=>'S',
      'ś'=>'s',
      'Ś'=>'S',
      'ť'=>'t',
      'Ť'=>'T',
      'ú'=>'u',
      'Ú'=>'U',
      'ů'=>'u',
      'Ů'=>'U',
      'ü'=>'u',
      'Ü'=>'U',
      'ù'=>'u',
      'Ù'=>'U',
      'ũ'=>'u',
      'Ũ'=>'U',
      'û'=>'u',
      'Û'=>'U',
      'ý'=>'y',
      'Ý'=>'Y',
      'ž'=>'z',
      'Ž'=>'Z',
      'ź'=>'z',
      'Ź'=>'Z'
    );

    $url = strtr($title, $prevodni_tabulka);
    $url = strtolower($url);
    $url = str_replace(' ', '-', $url);
    $url = preg_replace('/[^A-Za-z0-9\-]/', '', $url);

    return $url;
  }
}
