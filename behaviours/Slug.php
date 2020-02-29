<?php

namespace thefx\blocks\behaviours;

use dosamigos\transliterator\TransliteratorHelper;
use yii\base\Behavior;
use yii\db\ActiveRecord;

/**
 * @property ActiveRecord $owner
 */
class Slug extends Behavior
{
    public $in_attribute = 'title';
    public $out_attribute = 'path';
    public $translit = true;
    public $replacement = '-';

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'getSlug'
        ];
    }

    public function getSlug( $event )
    {
        if ( empty( $this->owner->{$this->out_attribute} ) ) {
            $this->owner->{$this->out_attribute} = $this->generateSlug( $this->owner->{$this->in_attribute} );
        } else {
            $this->owner->{$this->out_attribute} = $this->generateSlug( $this->owner->{$this->out_attribute} );
        }
    }

    private function generateSlug( $slug )
    {
        $slug = $this->slugify( $slug );
        if ( $this->checkUniqueSlug( $slug ) ) {
            return $slug;
        } else {
            for ( $suffix = 2; !$this->checkUniqueSlug( $new_slug = $slug . '_' . $suffix ); $suffix++ ) {}
            return $new_slug;
        }
    }

    private function slugify( $slug )
    {
        if ( $this->translit ) {
            $slug = TransliteratorHelper::process( $slug );
        }
        return $this->slug( $slug, $this->replacement, true );
    }

    private function slug( $string, $replacement = '-', $lowercase = true )
    {
        $string = preg_replace( '/[^\p{L}\p{Nd}]+/u', $replacement, $string );
        $string = trim( $string, $replacement );
        return $lowercase ? strtolower( $string ) : $string;
    }

    private function checkUniqueSlug( $slug )
    {
        $pk = $this->owner->primaryKey();
        $pk = $pk[0];

        $condition = $this->out_attribute . ' = :out_attribute';
        $params = [ ':out_attribute' => $slug ];
        if ( !$this->owner->isNewRecord ) {
            $condition .= ' and ' . $pk . ' != :pk';
            $params[':pk'] = $this->owner->{$pk};
        }

        return !$this->owner->find()
            ->where( $condition, $params )
            ->one();
    }
}