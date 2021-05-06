<div class="card">
    <div class="card-body">
        <p>{{__('translation.warning_publish')}}
            <code>php artisan translation:export</code> {{__('translation.command_or_publish_button')}}.</p>

        @if(!isset($group))
            @if (auth()->user()->isSuperAdmin())
            <form class="form-import" method="POST" action="<?php echo action($controller . '@postImport') ?>" data-remote="true" role="form">
                @csrf()
                <div class="row form-group">
                    <div class="col-auto">
                        <select name="replace" class="form-control">
                            <option value="0">{{__('translation.append_new_translations')}}</option>
                            <option value="1">{{__('translation.replace_existing_translations')}}</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-success btn-block" data-disable-with="Loading..">{{__('translation.import_groups')}}</button>
                    </div>
                </div>
            </form>
            <form class="form-find" method="POST" action="{{ action($controller.'@postFind') }}" data-remote="true" role="form"
                  data-confirm="{{__('translation.confirm_scan_folder')}}">
                @csrf()
                <div class="form-group">
                    <button type="submit" class="btn btn-info" data-disable-with="Searching..">{{__('translation.find_translations')}}</button>
                </div>
            </form>
            @endif
        @else
            <form class="form-inline form-publish" method="POST" action="{{ action('TranslateController@postPublish', $group) }}" data-remote="true"
                  role="form" data-confirm="{{__('translation.confirm_publish_group')}} {{$group}}? {{__('translation.will_orverwrite')}}">
                @csrf()
                <div class="btn-group" role="group">
                    <button type="submit" class="btn btn-primary" data-disable-with="Publishing..">{{__('translation.publish_translations')}}</button>
                    <a href="{{action($controller.'@getIndex') }}" class="btn btn-secondary">{{__('translation.back')}}</a>
                </div>
            </form>
        @endif
    </div>
</div>
