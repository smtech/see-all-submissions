{extends file="subpage.tpl"}

{block name="post-bootstrap-stylesheets"}

    <link href="//cdn.rawgit.com/noelboss/featherlight/1.5.0/release/featherlight.min.css" type="text/css" rel="stylesheet" />

{/block}

{/block}

{block name="subcontent"}

<div class="container">
    <div class="panel-group" id="assignments">
        {foreach $assignments as $assignment}
            {assign var="assignment_id" value="assignment_{$assignment['assignment']['id']}"}
            <div class="panel panel-info">
                <div class="panel-heading" role="tab" id="{$assignment_id}_heading">
                    <p class="panel-title">
                        <a data-toggle="collapse" data-parent="#assignments" href="#{$assignment_id}">
                            {$assignment['assignment']['name']}
                            <small class="pull-right">
                                due at {$assignment['assignment']['due_at']|date_format:'%l:%M %P on %B %e, %Y'}
                            </small>
                        </a>
                    </p>
                </div>
                <div id="{$assignment_id}" class="panel-collapse collapse">
                    <div class="panel-body">
                        <p>{$assignment['assignment']['description']}</p>
                        <div class="panel-group" id="{$assignment_id}_submissions">
                            {foreach $assignment['submissions'] as $version => $submission}
                                {assign var="submission_version" value="{$assignment_id}_submission_{$version}"}
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="{$submission_version}_heading">
                                        <p class="panel-title">
                                            <a data-doggle="collapse" data-parent="#{$assignment_id}_submissions" href="#{$submission_version}">
                                                Submission {$version}
                                                <small class="pull-right">
                                                    submitted at {$submission['submitted_at']|date_format:'%l:%M %P on %B %e, %Y'}
                                                </small>
                                            </a>
                                        </p>
                                    </div>
                                    <div id="{$submission_version}" class="panel-collapse">
                                        <div class="panel-body">
                                            {if !empty($submission['body'])}
                                                <p>{$submission['body']}</p>
                                            {else}
                                                {if !empty($submission['preview_url'])}
                                                    <a href="{$submission['preview_url']}" data-featherlight="fiframe">See Submission</a>
                                                {else}
                                                    {if !empty($submission['attachments'])}
                                                        {foreach $submission['attachments'] as $attachment}
                                                            <a href="{$attachment}" data-featherlight="iframe">See Attachment</a>
                                                        {/foreach}
                                                    {/if}
                                                {/if}
                                            {/if}
                                        </div>
                                    </div>
                                </div>
                            {/foreach}
                        </div>
                    </div>
                </div>
            </div>
        {/foreach}
    </div>
</div>

{/block}

{block name="post-bootstrap-scripts"}

    <script src="//cdn.rawgit.com/noelboss/featherlight/1.5.0/release/featherlight.min.js" type="text/javascript" charset="utf-8"></script>

{/block}
