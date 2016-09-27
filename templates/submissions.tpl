{extends file="subpage.tpl"}

{block name="subcontent"}

    {foreach $assignments as $assignment}
        <div class="container">
            <h3>{$assignment['name']} <small>due {$assignment['due_at']|date_format:'%B %e, %Y'}</small></h3>
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
    {/foreach}

{/block}
