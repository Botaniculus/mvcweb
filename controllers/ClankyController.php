<?php
class ClankyController extends Controller
{
  public function execute($parameters){
    $onPage = 10;
    $articlesManager = new ArticlesManager();
    $userManager = new UserManager();
    $user = $userManager->getUser();
    $this->data['user_permissions'] = $user['user_permissions'];
    $this->data['user_id'] = $user['user_id'];

    $this->header = array(
      'title' => 'Články',
      'keywords' => '',
      'description' => ''
    );

    if((isset($parameters[0]) && is_numeric($parameters[0])) || empty($parameters[0])) {
      $page = (isset($parameters[0])) ? $parameters[0] : 1;
      $pageCount = ceil($articlesManager->getArticlesCount() / $onPage);
      $this->data['articles'] = $articlesManager->getArticles($page, $onPage);
      $pagination = new Pagination('clanky/', $pageCount);
    }
    else if($parameters[0] == "archiv"){
        $page = (isset($parameters[1])) ? $parameters[1] : 1;
        $pageCount = ceil($articlesManager->getArticlesCount('article_archival') / $onPage);
        $this->data['articles'] = $articlesManager->getArticles($page, $onPage, 'article_archival');
        $pagination = new Pagination('clanky/archiv/', $pageCount);
    }


    $this->data['pagination'] = $pagination->pagination($page);

    $this->view = 'articles';
  }
}
