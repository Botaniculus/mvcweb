<?php
class Pagination
{
  private $url;
  private $pageCount;

  public function __construct($url, $pageCount){
    $this->url = $url;
    $this->pageCount = $pageCount;
  }
  private function getPageUrl($page){
    return $this->url . $page;
  }

  public function pagination($page){
    $radius = 5;
    $html = '<nav class="center"><ul class="pagination">';
    // Left arrow
    $html .= ($page > 1) ? '<li><a href="' . $this->getPageUrl($page-1) . '">❮</a></li>' : '<li class="disabled"><a>❮</a></li>';

    $left = ($page - $radius >= 1) ? ($page - $radius) : 1;
    $right = ($page + $radius <= $this->pageCount) ? ($page + $radius) : $this->pageCount;

    // First page location
    $html .= ($left > 1) ? '<li><a href="' . $this->getPageUrl(1). '">1</a></li>' : '';

    // Left dots
    $html .= ($left > 2) ? '<li class="disabled"><a>&hellip;</a></li>' : '';

    for($i = $left; $i <= $right; $i++){
      if($i == $page)
        $html .= '<li class="active"><a>' . $i . '</a></li>';
      else
        $html .= '<li><a href="' . $this->getPageUrl($i) . '">' . $i . '</a></li>';
    }

    // Right dots
    $html .= ($right < $this->pageCount - 1) ? '<li class="disabled"><a>&hellip;</a></li>' : '';

    // Last page_location
    $html .= ($right < $this->pageCount) ? '<li><a href="' . $this->getPageUrl($this->pageCount). '">' . $this->pageCount . '</a></li>' : '';

    // Right arrow
    $html .= ($page < $this->pageCount) ? '<li><a href="' . $this->getPageUrl($page + 1) . '">❯</a></li>' : '<li class="disabled"><a>❯</a></li>';

    $html .= '</ul></nav>';

    return $html;
  }
}
