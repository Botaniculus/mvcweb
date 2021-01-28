<?php
class ClanekController extends Controller
{
  public function execute($parameters){
    $articlesManager = new ArticlesManager();
    $userManager = new UserManager();
    $user = $userManager->getUser();
    $this->data['user_permissions'] = $user['user_permissions'];
    $this->data['user_id'] = $user['user_id'];

    // ----- delete article ----- //
    if(!empty($parameters[1]) && $parameters[1] == 'odstranit'){
      $this->verifyUser(1);
      $article = $articlesManager->getArticle($parameters[0]);

      if(($user['user_id'] == $article['article_author_id']) || $user['user_permissions'] >='2'){
        $articlesManager->removeArticle($parameters[0]);
        $this->addMessage('Článek byl úspěšně odstraněn', true);
        $this->redirect('clanek');
      }
      else{
        $this->addMessage('Nemáte oprávnění odstranit tento článek', false);
        $this->redirect('clanek/' . $article['article_url']);
      }
    }


    else if(!empty($parameters[0]))
    {
      $article = $articlesManager->getArticle($parameters[0]);
      if(!$article)
        $this->redirect('error');

      $this->data['authorname'] = $userManager->getUsername($article['article_author_id']);
      
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
