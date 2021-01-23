<?php
class EditorController extends Controller
{
  public function execute($parameters){
    //To add later: Allow user to edit or remove only his articles (author_id == user_id), unless he's (user_permissions>=2)
    $this->verifyUser(1);
    $this->header['title'] = 'Editor článků';
    $articlesManager = new ArticlesManager();
    $article = array(
      'article_id' => '',
      'article_title' => '',
      'article_content' => '',
      'article_url' => '',
      'article_description' => '',
      'article_keywords' => ''
    );
    if(isset($_POST['article_submit'])){
      $keys = array('article_title', 'article_content', 'article_url', 'article_description', 'article_keywords');
      $article = array_intersect_key($_POST, array_flip($keys));
      $articlesManager->saveArticle($_POST['article_id'], $article);
      $this->addMessage('Článek byl úspěšně uložen', true);
      $this->redirect('clanek/' . $article['article_url']);
    }
    else if(!empty($parameters[0])){
      $loadArticle = $articlesManager->getArticle($parameters[0]);
      if($loadArticle)
        $article = $loadArticle;
      else
        $this->addMessage('Článek nebyl nalezen', false);
    }
    $this->data['article'] = $article;
    $this->view = 'editor';
  }
}
