<div class="card mt-2">
    <div class="card-body">
        <form role="form" method="POST" action="{{action($controller.'@postAddGroup') }}">
            @csrf()
            <div class="form-group">
                <p>{{__('translation.choose_group')}}</p>
                <select name="group" id="group" class="form-control group-select">
                    @foreach($groups as $key => $value)
                        <option value="{{$key}}" {{ $key == $group ? ' selected' : ''}}>{{$value}}</option>
                    @endforeach
                </select>
            </div>
            @if (auth()->user()->isSuperAdmin())
            <div class="form-group">
                <label>{{__('translation.enter_new_group_name')}}</label>
                <input type="text" class="form-control" name="new-group"/>
            </div>
             
            <div class="form-group">
                <input type="submit" class="btn btn-primary" name="add-group" value="{{__('translation.add_group')}}"/>
            </div>
            @endif
        </form>
    </div>
</div>
