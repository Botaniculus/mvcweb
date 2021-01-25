<?php
class EditorController extends Controller
{
  public function execute($parameters){
    $this->verifyUser(1);



    $userManager = new UserManager();
    $user = $userManager->getUser();
    $user_id = $user['user_id'];
    $user_permissions = $user['user_permissions'];

    $this->header['title'] = 'Editor článků';
    $articlesManager = new ArticlesManager();
    $article = array(
      'article_id' => '',
      'article_title' => '',
      'article_content' => '',
      'article_url' => '',
      'article_description' => '',
      'article_keywords' => '',
      'article_author_id' => ''
    );


    if(isset($_POST['article_submit'])){
      $keys = array('article_title', 'article_content', 'article_description', 'article_keywords');
      $article = array_intersect_key($_POST, array_flip($keys));
      $article['article_author_id'] = $user_id;
      $article['article_url'] = $articlesManager->titleToUrl($article['article_title']);
      try{
        if($article['article_author_id'] == $user_id || $user_permissions >= 2){
          $articlesManager->saveArticle($_POST['article_id'], $article);
          $this->addMessage('Článek byl úspěšně uložen', true);
          $this->redirect('clanek/' . $article['article_url']);
        }
      }
      catch(UserException $e){
        $this->addMessage(($e->getMessage()), false);

      }


    }

    else if(!empty($parameters[0])){
      $loadArticle = $articlesManager->getArticle($parameters[0]);
      if($loadArticle){
        if(($loadArticle['article_author_id'] == $user_id) || $user_permissions >= 2){
          $article = $loadArticle;
        }
        else{
          $this->addMessage('Nemáte oprávnění editovat tento článek.', false);
          $this->redirect('clanek/' . $loadArticle['article_url']);
        }
      }
      else
        $this->addMessage('Článek nebyl nalezen', false);
    }


    $this->data['article'] = $article;
    $this->view = 'editor';
  }
}
