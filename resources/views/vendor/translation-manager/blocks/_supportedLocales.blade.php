<div class="card mt-2">
    <div class="card-body">
        <fieldset>
            <legend>{{__('translation.supported_locales')}}</legend>
            <p>
                {{__('translation.current_locales')}}:
            </p>
            <form class="form-remove-locale" method="POST" role="form" action="{{action($controller.'@postRemoveLocale')}}"
                  data-confirm="{{__('translation.confirm_remove_locale')}}">
                @csrf()
                <ul class="list-locales list-unstyled list-inline">
                    @foreach($locales as $locale)
                        <li class="form-group list-inline-item mr-4">
                            <span>{{$locale}}</span>
                            <button type="submit" name="remove-locale[{{$locale}}]" class="btn btn-danger btn-sm ml-1" data-disable-with="...">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </li>
                    @endforeach
                </ul>
            </form>
            <form class="form-add-locale" method="POST" role="form" action="{{action($controller.'@postAddLocale')}}">
                @csrf()
                <div class="form-group">
                    <p>
                        {{__('translation.enter_new_locale')}}:
                    </p>
                    <div class="row">
                        <div class="col-auto">
                            <input type="text" name="new-locale" class="form-control"/>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn  btn-block btn-outline-success" data-disable-with="Adding..">{{__('translation.add_new_locale')}}</button>
                        </div>
                    </div>
                </div>
            </form>
        </fieldset>
    </div>
</div>
