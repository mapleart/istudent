{extends 'layouts/layout.base.tpl'}


{block 'layout_options' append}
    {$mods = "$mods map"}
{/block}

{block 'layout_body' append}

    <div class="modal js-modalEmpty" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>

{/block}