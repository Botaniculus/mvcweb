<?php
class ClanekController extends Controller
{
  public function execute($parameters){
    $articlesManager = new ArticlesManager();

    if(!empty($parameters[0]))
    {
      $article = $articlesManager->getArticle($parameters[0]);

      if(!$article)
        $this->redirect('error');

      $this->header = array(
        'title' => $article['article_title'],
        'keywords' => $article['article_keywords'],
        'description' => $article['article_description']
      );

      $this->data['title'] = $article['article_title'];
      $this->data['content'] = $article['article_content'];

      $this->view = 'article';
    }

    else
    {
        $articles = $articlesManager->getArticles();
        $this->data['articles'] = $articles;
        $this->view = 'articles';
    }

  }
}
