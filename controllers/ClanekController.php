<?php
class ClanekController extends Controller
{
  public function execute($parameters){
    $articlesManager = new ArticlesManager();
    $userManager = new UserManager();
    $user = $userManager->getUser();
    $this->data['user_permissions'] = $user['user_permissions'];

    if(!empty($parameters[1]) && $parameters[1] == 'odstranit'){
      // TO ADD LATER: check if the user_id equals article_author_id OR if user_permissions is equal or greater than 2
      $this->verifyUser(1);
      $articlesManager->removeArticle($parameters[0]);
      $this->addMessage('Článek byl úspěšně odstraněn', true);
      $this->redirect('clanek');
    }
    else if(!empty($parameters[0]))
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
        $this->header = array(
          'title' => 'Články',
          'keywords' => '',
          'description' => ''
        );
        $articles = $articlesManager->getArticles();
        $this->data['articles'] = $articles;
        $this->view = 'articles';
    }

  }
}
