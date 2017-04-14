<?php 

namespace App\Helpers;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Pagination\BootstrapThreePresenter;

class CustomPaginator extends BootstrapThreePresenter 
{
     /**
     * Convert the URL window into Zurb Foundation HTML.
     *
     * @return string
     */
    public function render()
    {
        if( ! $this->hasPages())
            return '';

        $this->paginator;

        return sprintf(
            '<ul class="pagination" aria-label="Pagination">%s %s %s %s %s</ul></div>',
            $this->getFirstPageButton('&laquo;&laquo;'),
            $this->getPreviousButton('&laquo;'),
            $this->getLinks(),
            $this->getNextButton('&raquo;'),
            $this->getLastPageButton('&raquo;&raquo;')
        );
    }

    private function getFirstPageButton($text)
    {
        if ($this->paginator->currentPage() <= 1)
        {
            return $this->getDisabledTextWrapper($text);
        }

        $url = $this->paginator->url(1);
        return $this->getPageLinkWrapper($url, $text);
    }

    private function getLastPageButton($text)
    {
        if (!$this->paginator->hasMorePages())
        {
            return $this->getDisabledTextWrapper($text);
        }

        $url = $this->paginator->url($this->paginator->lastPage());
        return $this->getPageLinkWrapper($url, $text);
    }

    /**
     * Get HTML wrapper for disabled text.
     *
     * @param  string  $text
     * @return string
     */
    protected function getDisabledTextWrapper($text)
    {
        return '<li class="disabled"><span>'.$text.'</span></li>';
    }

    /**
     * Get HTML wrapper for active text.
     *
     * @param  string  $text
     * @return string
     */
    protected function getActivePageWrapper($text)
    {
        return '<li class="active"><span>'.$text.'</span></li>';
    }

    /**
     * Get a pagination "dot" element.
     *
     * @return string
     */
    protected function getDots()
    {
        return $this->getDisabledTextWrapper('&hellip;');
    }
}