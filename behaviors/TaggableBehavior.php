<?php

namespace elitedivision\amos\core\behaviors;

use creocoder\taggable\TaggableBehavior as YiiTaggable;


class TaggableBehavior extends YiiTaggable
{
    /**
     * @var string|separator for tags
     */
    public $tagValuesSeparatorAttribute = ', ';
    public $tagValueNameAttribute = 'nome';

    /**
     * @var string[]
     */
    private $_tagValues;
    private $_tagValuesName;

    /**
     * Returns tags.
     * @param boolean|null $asArray
     * @return string|string[]
     */
    public function getTagNameValues($asArray = null)
    {
        if (!$this->owner->getIsNewRecord() && $this->_tagValuesName === null) {
            $this->_tagValuesName = [];

            /* @var ActiveRecord $tag */
            foreach ($this->owner->{$this->tagRelation} as $tag) {
                $this->_tagValuesName[] = $tag->getAttribute($this->tagValueNameAttribute);
            }
        }

        if ($asArray === null) {
            $asArray = $this->tagValuesAsArray;
        }

        if ($asArray) {
            return $this->_tagValuesName === null ? [] : $this->_tagValuesName;
        } else {
            return $this->_tagValuesName === null ? '' : implode($this->tagValuesSeparatorAttribute, $this->_tagValuesName);
        }
    }

    /**
     * Returns tags.
     * @param boolean|null $asArray
     * @return string|string[]
     */
    public function getTagValues($asArray = null)
    {
        if (!$this->owner->getIsNewRecord() && $this->_tagValues === null) {
            $this->_tagValues = [];

            /* @var ActiveRecord $tag */
            foreach ($this->owner->{$this->tagRelation} as $tag) {
                $this->_tagValues[] = $tag->getAttribute($this->tagValueAttribute);
            }
        }

        if ($asArray === null) {
            $asArray = $this->tagValuesAsArray;
        }

        if ($asArray) {
            return $this->_tagValues === null ? [] : $this->_tagValues;
        } else {
            return $this->_tagValues === null ? '' : implode($this->tagValuesSeparatorAttribute, $this->_tagValues);
        }
    }


    /**
     * Filters tags.
     * @param string|string[] $values
     * @return string[]
     */
    public function filterTagValues($values)
    {
        return array_unique(preg_split(
            '/\s*,\s*/u',
            preg_replace('/\s+/u', ' ', is_array($values) ? implode(trim($this->tagValuesSeparatorAttribute), $values) : $values),
            -1,
            PREG_SPLIT_NO_EMPTY
        ));
    }
}