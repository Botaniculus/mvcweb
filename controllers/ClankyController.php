<?php
class ClankyController extends Controller
{
  public function execute($parameters){
    $onPage = 2;
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
      $articles = $articlesManager->getArticles($page, $onPage);
      $this->data['articles'] = $articles[1];
      $pageCount = ceil($articles[0] / $onPage);
      $pagination = new Pagination('clanky/', $pageCount);
    }
    else if($parameters[0] == "tag"){
        $tag = (isset($parameters[1])) ? urldecode($parameters[1]) : '';
        $page = (isset($parameters[2])) ? $parameters[2] : 1;

        $articles = $articlesManager->getArticles($page, $onPage, $tag);
        $pagesCount = ceil($articles[0] / $onPage);
        $this->data['articles'] = $articles[1];

        $pagination = new Pagination('clanky/tag/' . $tag . '/', $pagesCount);
    }


    $this->data['pagination'] = $pagination->pagination($page);
    $this->view = 'articles';
  }
}
