<?php

require_once 'common.inc.php';

use smtech\ReflexiveCanvasLTI\LTI\ToolProvider;

$user = $toolbox->api_get('users/' . $_SESSION[ToolProvider::class]['canvas']['user_id']);

$submissionsRaw = $toolbox->api_get(
    'courses/' . $_SESSION[ToolProvider::class]['canvas']['course_id'] . '/students/submissions',
    [
        'student_ids' => [$user['id']],
        'include' => ['submission_history']
    ]
);

$assignments = [];
$submissions = [];
foreach ($submissionsRaw as $submission) {
    if (empty($assignments[$submission['assignment_id']])) {
        $assignment = $toolbox->api_get(
            'courses/' . $_SESSION[ToolProvider::class]['canvas']['course_id'] . "/assignments/{$submission['assignment_id']}"
        );
        if (!in_array('not_graded', $assignment['submission_types'])) {
            $assignments[$submission['assignment_id']] = $assignment;
            $submissions[$submission['assignment_id']][] = $submission;
        }
    }
}

$toolbox->smarty_assign([
    'name' => 'See All Submissions',
    'category' => $user['name'],
    'assignments' => $assignments,
    'submissions' => $submissions
]);
$toolbox->smarty_display('submissions.tpl');
