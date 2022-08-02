<?php
namespace App;

use Twig_SimpleFilter;
use Twig_Extension;
use Twig_SimpleFunction;

class MonExtension extends Twig_Extension
{

    public function getFilters()
    {
        return [
            new Twig_SimpleFilter('markdown', [$this, 'markdownParse'], ['is_safe' => ['html']])
        ];
    }

    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('activeClass', [$this, 'activeClass'], ['needs_context' => true])
        ];
    }

    // public function getIdCategorie()
    // {
    //     return [
    //         new Twig_SimpleFunction('IdCategorie', [$this, 'activeClass'], ['needs_context' => true])
    //     ];
    // }

    public function markdownParse($value)
    {
        return \Michelf\MarkdownExtra::defaultTransform($value);
    }

    public function activeClass(array $context, $page) {
        if (isset($context['current_page']) && $context['current_page'] === $page) {
            return ' active ';
        }
    }
}