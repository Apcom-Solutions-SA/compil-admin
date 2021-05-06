<div class="card mt-2">
    <div class="card-body">
        <form action="{{ action($controller.'@postAdd', array($group))}}" method="POST" role="form">
               @csrf()
          <div class="form-group">
                <label>{{__('translation.add_new_keys')}}</label>
                <textarea class="form-control" rows="3" name="keys" placeholder="{{__('translation.add_1_key_per_line')}}"></textarea>
            </div>
            <div class="form-group">
                <input type="submit" value="{{__('translation.add_keys')}}" class="btn btn-primary">
            </div>
        </form>
        <hr>
        <h4>{{__('translation.total')}}: {{ $numTranslations }}, {{__('translation.changed')}}: {{ $numChanged }}</h4>
        <translations :locales="{{ json_encode($locales) }}" :delete_enabled="{{ $deleteEnabled }}" :translations="{{ json_encode($translations) }}" group="{{$group}}"></translations>    
    </div>
</div>
