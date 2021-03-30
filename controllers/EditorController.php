<?php
class EditorController extends Controller
{
  public function execute($parameters){
    $this->verifyUser(1);



    $userManager = new UserManager();
    $user = $userManager->getUser();

    $this->header['title'] = 'Editor článků';
    $articlesManager = new ArticlesManager();
    $article = array(
      'article_id' => '',
      'article_title' => '',
      'article_content' => '',
      'article_url' => '',
      'article_description' => '',
      'article_keywords' => '',
      'article_author_id' => $user['user_id'],
      'article_author_name' => $user['user_name'],
      'article_submitted_date' => date("Y-m-d"),
      'article_empty' => 1
    );


    if(isset($_POST['article_submit'])){
      $keys = array('article_title', 'article_content', 'article_description', 'article_keywords', 'article_author_id', 'article_author_name', 'article_submitted_date', 'article_archival');
      $article = array_intersect_key($_POST, array_flip($keys));
      $article['article_keywords'] = strtolower($article['article_keywords']);
      $article['article_url'] = $articlesManager->titleToUrl($article['article_title']);
      $article['article_empty'] = (empty($article['article_content'])) ? 1 : 0;

      try{
        if(!empty($_POST['article_id'])){
          $author_id = $articlesManager->getArticleAuthorById($_POST['article_id']);
          if($user['user_id'] == $author_id || $user['user_permissions'] >= 2){
            $articlesManager->saveArticle($_POST['article_id'], $article);
            $this->addMessage('Článek byl úspěšně uložen', true);
            $this->redirect('clanek/' . $article['article_url']);
          }
          else{
            $this->addMessage('Nemáte oprávnění editovat tento článek, a neupravujte ty skryté vstupy ' . $author_id, false);
            $article['article_id'] = '';
          }
        } else{
          $articlesManager->saveArticle($_POST['article_id'], $article);
          $this->addMessage('Článek byl úspěšně uložen', true);
          $this->redirect('clanek/' . $article['article_url']);
        }


      }
      catch(UserException $e){
        $this->addMessage(($e->getMessage()), false);
        $article['article_id'] = $_POST['article_id'];
      }

    }

    else if(!empty($parameters[0])){
      $loadArticle = $articlesManager->getArticle($parameters[0]);
      if($loadArticle){
        if(($loadArticle['article_author_id'] == $user['user_id']) || $user['user_permissions'] >= 2){
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
