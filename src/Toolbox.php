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

    /**
     * This is a quasi temporary "unborking" while a few support tickets are
     * working through the queue. See the fix-me notations below.
     *
     * @param string $previewUrl
     * @return string
     */
    public function unborkPreviewUrl($previewUrl)
    {
        if (!preg_match('%^https?://.*%', $previewUrl)) {
            /*
             * FIXME: per [case 01584858 ](https://cases.canvaslms.com/CommunityConsole?id=500A000000UPwauIAD)
             * attachments are not well-documented and the Crocodoc attachments
             * include an incomplete preview URL that works… but not in an IFRAME
             */
            return $_SESSION[CANVAS_INSTANCE_URL] . $previewUrl;
        } elseif (preg_match('%^(.*version=)(\d+)(.*)$%', $previewUrl, $match)) {
            /*
             * FIXME: per [case 01584819](https://cases.canvaslms.com/CommunityConsole?id=500A000000UPwSlIAL)
             * preview URLs are generated "off by one" and are really zero-indexed.
             */
            return $match[1] . ($match[2] - 1) . $match[3];
        } else {
            /* whatevs, it is what it is… */
            return $previewUrl;
        }
    }
}
