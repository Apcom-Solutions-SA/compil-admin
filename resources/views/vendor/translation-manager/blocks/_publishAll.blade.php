<div class="card mt-2">
    <div class="card-body">
        <fieldset>
            <legend>{{__('translation.export_all')}}</legend>
            <form class="form-inline form-publish-all" method="POST" action="{{ action('TranslateController@postPublish', '*') }}" data-remote="true" role="form"
                  data-confirm="{{__('translation.confirm_publish_all')}}">
                    @csrf()
                <button type="submit" class="btn btn-primary" data-disable-with="Publishing..">{{__('translation.publish_all')}}</button>
            </form>
        </fieldset>
    </div>
</div>
