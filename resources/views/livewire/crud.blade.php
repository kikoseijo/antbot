<div>
    @if($updateMode)
        @include($viewsFolder .'.update')
    @else
        @include($viewsFolder .'.create')
    @endif
    @include($viewsFolder . '.table')
</div>
