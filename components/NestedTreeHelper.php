<?php

namespace thefx\blocks\components;

/**
 * Class NestedTreeHelper
 */
class NestedTreeHelper
{
    public static function createCategoriesTree($items, $left = 0, $right = null)
    {
        $tree = [];
        foreach ($items as $cat => $item) {
            if ((int)$item['lft'] === $left + 1 && ($right === null || $item['rgt'] < $right)) {
                $tree[] = (object)array_filter([
                    'text' => $item['title'],
                    'alias' => $item['path'],
                    'id' => $item['id'],
                    'depth' => $item['depth'],
                    'public' => $item['public'],
                    'sort' => $item['sort'],
                    'icon' => $item['icon'],
                    'photo_preview' => $item['photo_preview'],
//                    'state' => (object)['opened' => false],
                    'children' => static::createCategoriesTree($items, $item['lft'], $item['rgt'])
                ]);
                $left = $item['rgt'];
            }
        }
        usort($tree, [self::class, 'usort']);
        return $tree;
    }

    public static function usort($a, $b)
    {
        if ($a->sort === $b->sort) {
            return $a->id - $b->id;
        }
        return $a->sort - $b->sort;
    }
}