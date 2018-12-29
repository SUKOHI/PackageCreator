{!! '<' !!}?php

namespace {!! studly_case($vendor) !!}\{!! studly_case($package) !!}\Facades;

use Illuminate\Support\Facades\Facade;

class {!! studly_case($package) !!} extends Facade {

    /**
    * Get the registered name of the component.
    *
    * {!! '@' !!}return string
    */
    protected static function getFacadeAccessor() { return '{!! str_slug($package) !!}'; }

}