<?php

require_once 'common.inc.php';

use smtech\ReflexiveCanvasLTI\LTI\ToolProvider;
use Battis\DataUtilities;

$user = $toolbox->api_get('/users/' . $_SESSION[ToolProvider::class]['canvas']['user_id']);

/* load students, if the current user is not a student, i.e. a teacher */
/* FIXME this is not an entirely safe assumption, of course */
$students = [];
if (!$_SESSION[ToolProvider::class]['isStudent']) {
    $students = $toolbox->api_get(
        '/courses/' . $_SESSION[ToolProvider::class]['canvas']['course_id'] . '/enrollments',
        [
            'type[]' => 'StudentEnrollment'
        ]
    );
}

$student = false;
if (empty($_REQUEST['user_id'])) {
    if ($_SESSION[ToolProvider::class]['isStudent']) {
        $student = $user;
    }
} else {
    $student = $toolbox->api_get("/users/{$_REQUEST['user_id']}");
}

$assignments = [];
if (!empty($student)) {
    $submissions = $toolbox->api_get(
        'courses/' . $_SESSION[ToolProvider::class]['canvas']['course_id'] . '/students/submissions',
        [
            'as_user_id' => $user['id'],
            'student_ids' => [$student['id']],
            'include' => ['submission_history']
        ]
    );

    foreach ($submissions as $submission) {
        $assignment = $toolbox->api_get(
            'courses/' . $_SESSION[ToolProvider::class]['canvas']['course_id'] .
            "/assignments/{$submission['assignment_id']}"
        );
        if (!in_array('not_graded', $assignment['submission_types'])) {
            $assignmentData['assignment'] = $assignment;
            foreach ($submission['submission_history'] as $version) {
                if (!empty($version['submitted_at'])) {
                    $versionData = [
                        'id' => $version['id'],
                        'attempt' => $version['attempt'],
                        'submitted_at' => $version['submitted_at']
                    ];
                    if ($version['submission_type'] == 'online_text_entry') {
                        $versionData['body'] = $version['body'];
                    } else {
                        if (empty($version['attachments'])) {
                            $versionData['type'] = DataUtilities::titleCase(
                                str_replace(
                                    '_',
                                    ' ',
                                    $version['submission_type']
                                )
                            );
                            $versionData['preview_url'] = $toolbox->unborkPreviewUrl($version['preview_url']);
                        } else {
                            foreach ($version['attachments'] as $attachment) {
                                $versionData['attachments'][$attachment['id']] = [
                                        'name' => $attachment['display_name'],
                                        'preview_url' => $toolbox->unborkPreviewUrl($attachment['preview_url'])
                                    ];
                            }
                        }
                    }
                    if (!empty($versionData['body']) ||
                        !empty($versionData['preview_url']) ||
                        !empty($versionData['attachments'])) {
                        $assignmentData['submissions'][$versionData['attempt']] = $versionData;
                    }
                }
            }
            if (!empty($assignmentData['submissions'])) {
                $assignments[$submission['assignment_id']] = $assignmentData;
            }
            unset($assignmentData);
            unset($versionData);
        }
    }
}

$toolbox->smarty_assign([
    'name' => 'See All Submissions',
    'category' => $student['name'],
    'assignments' => $assignments,
    'students' => $students,
    'student' => $student
]);

$toolbox->smarty_display('submissions.tpl');
