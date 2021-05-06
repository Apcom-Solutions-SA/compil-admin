    <div class="alert alert-success success-import" style="display:none;">
        <p>{{__('translation.done_importing')}}, {{__('translation.processed')}} <strong class="counter">N</strong> {{__('translation.items')}}! {{__('translation.reload_page_refresh_groups')}}!</p>
    </div>
    <div class="alert alert-success success-find" style="display:none;">
        <p>{{__('translation.done_searching_translations')}}, {{__('translation.found')}} <strong class="counter">N</strong> {{__('translation.items')}}!</p>
    </div>
    <div class="alert alert-success success-publish" style="display:none;">
        <p>{{__('translation.done_publishing_group')}} '{{$group}}'!</p>
    </div>
    <div class="alert alert-success success-publish-all" style="display:none;">
        <p>{{__('translation.done_publishing_all')}}</p>
    </div>

    @if(Session::has('successPublish'))
        <div class="alert alert-info">
           {!! Session::get('successPublish') !!}
        </div>
    @endif
