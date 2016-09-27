<?php

namespace smtech\SeeAllSubmissions;

use smtech\LTI\Configuration\Option;
use Battis\HierarchicalSimpleCache;

/**
 * See All Submissions toolbox
 *
 * Adds some common, useful methods to the St. Mark's-styled
 * ReflexiveCanvasLTI Toolbox
 *
 * @author  Seth Battis <SethBattis@stmarksschool.org>
 */
class Toolbox extends \smtech\StMarksReflexiveCanvasLTI\Toolbox
{

    /**
     * Configure course and account navigation placements
     *
     * @return Generator
     */
    public function getGenerator()
    {
        parent::getGenerator();

        $this->generator->setOptionProperty(
            Option::COURSE_NAVIGATION(),
            'visibility',
            'members'
        );

        return $this->generator;
    }
}
