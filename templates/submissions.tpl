{extends file="subpage.tpl"}

{block name="subcontent"}

<div class="container">
    <div class="panel-group" id="assignments" role="tablist" aria-multiselectable="true">
        {foreach $assignments as $assignment}
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="assignment_{$assignment['assignment']['id']}_heading">
                    <p>
                        <a role="button" data-toggle="collapse" data-parent="#assignments" href="#assignment_{$assignment['assignment']['id']}" aria-expanded="false" aria-controls="assignment_{$assignment['assignment']['id']}">
                            {$assignment['assignment']['name']}
                            <span class="pull-right">
                                due {$assignment['assignment']['due_at']|date_format:'%B %e, %Y %l:%M %P'}
                            </span>
                        </a>
                    </p>
                </div>
                <div id="assignment_{$assignment['assignment']['id']}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="assignment_{$assignment['assignment']['id']}_heading">
                    <div class="panel-body">
                        <p>{$assignment['assignment']['description']}</p>
                        {foreach $assignment['submissions'] as $submission}
                            <p>Submitted {$submission['submitted_at']|date_format:'%B %e, %Y %l:%M %P'}</p>
                            {if !empty($submission['body'])}
                                <p>{$submission['body']}</p>
                            {else}
                                {if !empty($submission['preview_url'])}
                                    <iframe src="{$submission['preview_url']}"></iframe>
                                {else}
                                    {if !empty($submission['attachments'])}
                                        {foreach $submission['attachments'] as $attachment}
                                            <iframe src="{$attachment}"></iframe>
                                        {/foreach}
                                    {/if}
                                {/if}
                            {/if}
                        {/foreach}
                    </div>
                </div>
            </div>
        {/foreach}
    </div>
</div>

{/block}
