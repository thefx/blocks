<?php

namespace thefx\blocks\components;

/**
 * Class TreeHelperNested
 */
class TreeHelperNested
{
    public static function createTreeList(array $elements, $except_section_id = null, $section_id = 0, $lvl = 0)
    {
        $branch = [];

        foreach ($elements as $element) {
            if ($element['section_id'] === $section_id && $element['id'] !== $except_section_id) {
                $branch[] = [
                    'id' => $element['id'],
                    'title' => $element['title'],
                    'lvl' => $lvl,
                ];
                $children = static::createTreeList($elements, $except_section_id, $element['id'], $lvl + 1);
                if ($children) {
                    foreach ($children as $child) {
                        $branch[] = [
                            'id' => $child['id'],
                            'lvl' => $lvl,
                            'title' => str_repeat('↳', $child['lvl']) . ' ' . $child['title'],
                        ];
                    }
                }
            }
        }

        return $branch;
    }
}