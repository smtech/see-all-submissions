{extends file="subpage.tpl"}

{block name="subcontent"}

<div class="container">
    <div class="panel-group" id="assignments" role="tablist" aria-multiselectable="true">
        {foreach $assignments as $assignment}
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="assignment_{$assignment['id']}_heading">
                    <p>
                        <a role="button" data-toggle="collapse" data-parent="#assignments" href="#assignment_{$assignment['id']}" aria-expanded="false" aria-controls="assignment_{$assignment['id']}">
                            {$assignment['name']}
                            <span class="pull-right">
                                due {$assignment['due_at']|date_format:'%B %e, %Y'}
                            </span>
                        </a>
                    </p>
                </div>
                <div id="assignment_{$assignment['id']}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="assignment_{$assignment['id']}_heading">
                    <div class="panel-body">
                        <p>{$assignment['description']}</p>
                        {foreach $submissions[$assignment['id']] as $submissionRoot}
                            {foreach $submissionRoot['submission_history'] as $submission}
                                {if $submission['submitted_at'] !== null}
                                    <p>Submitted {$submission['submitted_at']|date_format:'%B %e, %Y'}</p>
                                    {if $submission['submission_type'] == 'online_text_entry'}
                                        <p>{$submission['body']}</p>
                                    {else}
                                        <div class="embed-responsive embed-responsive-16by9">
                                            <iframe src="{$submission['preview_url']}" class="embed-responsive-item"></iframe>
                                        </div>
                                    {/if}
                                {/if}
                            {/foreach}
                        {/foreach}
                    </div>
                </div>
            </div>
        {/foreach}
    </div>
</div>

{/block}
