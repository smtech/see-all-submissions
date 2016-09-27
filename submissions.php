<?php

require_once 'common.inc.php';

use smtech\ReflexiveCanvasLTI\LTI\ToolProvider;

/**
 * This is a quasi temporary "unborking" while a few support tickets are
 * working through the queue. See the fix-me notations below.
 *
 * @param string $previewUrl
 * @return string
 */
function unborkPreviewUrl($previewUrl)
{
    if (!preg_match('%^https?://.*%', $previewUrl)) {
        /*
         * FIXME: per [case 01584858 ](https://cases.canvaslms.com/CommunityConsole?id=500A000000UPwauIAD) attachments are not well-documented and the Crocodoc attachments include an incomplete preview URL that works… but not in an IFRAME
         */
        return $_SESSION[CANVAS_INSTANCE_URL] . $previewUrl;
    } elseif (preg_match('%^(.*version=)(\d+)(.*)$%', $previewUrl, $match)) {
        /*
         * FIXME: per [case 01584819](https://cases.canvaslms.com/CommunityConsole?id=500A000000UPwSlIAL) preview URLs are generated "off by one" and are really zero-indexed.
         */
        return $match[1] . ($match[2] - 1) . $match[3];
    } else {
        /* whatevs, it is what it is… */
        return $previewUrl;
    }
}

$user = $toolbox->api_get('users/' . $_SESSION[ToolProvider::class]['canvas']['user_id']);

$submissions = $toolbox->api_get(
    'courses/' . $_SESSION[ToolProvider::class]['canvas']['course_id'] . '/students/submissions',
    [
        'student_ids' => [$user['id']],
        'include' => ['submission_history']
    ]
);

$assignments = [];
foreach ($submissions as $submission) {
    if (empty($assignments[$submission['assignment_id']])) {
        $assignment = $toolbox->api_get(
            'courses/' . $_SESSION[ToolProvider::class]['canvas']['course_id'] . "/assignments/{$submission['assignment_id']}"
        );
        if (!in_array('not_graded', $assignment['submission_types'])) {
            $assignments[$submission['assignment_id']]['assignment'] = $assignment;
            foreach($submission['submission_history'] as $version) {
                if (!empty($version['submitted_at'])) {
                    $versionData = [
                        'id' => $version['id'],
                        'attempt' => $version['attempt'],
                        'submitted_at' => $version['submitted_at']
                    ];
                    if ($version['submission_type'] == 'online_text_entry') {
                        $versionData['body'] = $version['body'];
                    } else {
                        /*
                        FIXME: per [case 01584858 ](https://cases.canvaslms.com/CommunityConsole?id=500A000000UPwauIAD) attachments are not well-documented and the Crocodoc attachments include an incomplete preview URL that works… but not in an IFRAME
                        if (empty($version['attachments'])) {
                        */
                            $versionData['preview_url'] = unborkPreviewUrl($version['preview_url']);
                        /*} else {
                            foreach ($version['attachments'] as $attachment) {
                                $versionData['attachments'][$attachment['id']] = unborkPreviewUrl($attachment['preview_url']);
                            }
                        }*/
                    }
                    $assignments[$submission['assignment_id']]['submissions'][$versionData['attempt']] = $versionData;
                }
            }
        }
    }
}

$toolbox->smarty_assign([
    'name' => 'See All Submissions',
    'category' => $user['name'],
    'assignments' => $assignments,
]);
$toolbox->smarty_display('submissions.tpl');
