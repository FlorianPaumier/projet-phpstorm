<?php
namespace Framework\Twig;

class TextExtension extends \Twig_Extension
{

    public function getFilters() : array
    {
        return  [
            new \Twig_SimpleFilter('excerpt', [$this, 'excerpt'])
        ];
    }

    /**
     * @param string $content
     * @param int $maxlength
     * @return string
     */
    public function excerpt(string $content, int $maxlength = 100): string
    {

        if (mb_strlen($content) > $maxlength) {
            $excerpt = mb_substr($content, 0, $maxlength);
            $lastSpace = mb_strrpos($excerpt, ' ');
            return mb_substr($excerpt, 0, $lastSpace). '...';
        }
        return $content;
    }
}
